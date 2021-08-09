<?php

namespace App\Sharp;

use App\Sport;
use App\Competition;
use App\CompetitionSport;
use Illuminate\Support\Str;
use App\Jobs\ProcessCacheReload;
use Code16\Sharp\Form\SharpForm;
use Illuminate\Support\Facades\Validator;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormListField;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use App\Sharp\Formatters\TimestampSharpFormatter;
use Code16\Sharp\Form\Fields\SharpFormCheckField;
use Code16\Sharp\Form\Fields\SharpFormNumberField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;

class SportSharpForm extends SharpForm
{
    use WithSharpFormEloquentUpdater;

    /**
     * Retrieve a Model for the form and pack all its data as JSON.
     *
     * @param $id
     * @return array
     */
    public function find($id): array
    {
        $sport = Sport::findOrFail($id);

        // Les compétitions liés
        $competitions = $sport->competitionsNavbar()->orderBy('position')->get();
        foreach ($competitions as $key => $competition)
            $competitions[$key] = [
                'id' => $competition->id,
                'competition_id' => $competition->id,
                'position' => $competition->pivot->position,
            ];

        return $this->setCustomTransformer("competitions", function () use($competitions) {
            return $competitions;
        })->transform(
            $sport
        );
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed the instance id
     */
    public function update($id, array $data)
    {
        $sport = $id ? Sport::findOrFail($id) : new Sport;

        // On valide la requète
        $rules = Sport::rules($sport);
        $rules = $rules['rules'];
        $data['slug'] = Str::slug($data['nom']);

        $pluck = ['nom', 'home_position', 'navbar_position', 'slug'];
        $rules = array_intersect_key($rules, array_flip($pluck));

        $dataUpdate = Validator::make($data, $rules)->validate();

        $sport->competitionsNavbar()->detach(); // Supprime toutes les relations sport/navbar
        foreach ($data['competitions'] as $competition){
            if($competition){
                // On valide la requète des pivots
                $rules = CompetitionSport::rules();
                $rules = $rules['rules'];
                $competition['sport_id'] = $sport->id;
                Validator::make($competition, $rules)->validate();

                $sport->competitionsNavbar()->attach($competition['competition_id'], [
                    'position' => $competition['position'],
                ]);
            }
        }
        // On recharge le cache index de la table associative
        forgetCaches('competition_sport');
        ProcessCacheReload::dispatch('competition_sport');

        $sport = $this->save($sport, $dataUpdate);

        forgetCaches('sports', $sport);
        ProcessCacheReload::dispatch('sports', $sport->id);
    }

    /**
     * @param $id
     */
    public function delete($id): void
    {
        $sport = Sport::findOrFail($id);
        forgetCaches('sports', $sport);

        $sport->navbar()->detach();
        $sport->delete();

        ProcessCacheReload::dispatch('sports');
    }

    /**
     * Build form fields using ->addField()
     *
     * @return void
     */
    public function buildFormFields(): void
    {
        $timestampFormatter = new TimestampSharpFormatter;

        $competitions = Competition::join('sports', 'sport_id', 'sports.id')
                                    ->orderBy('sports.nom')->orderBy("competitions.nom")
                                    // ->where('sports.id')
                                    ->select('competitions.*')
                                    ->get()->map(function($competition) {
                                        return [
                                            "id" => $competition->id,
                                            "label" => $competition->sport->nom . ' - ' . $competition->nom
                                        ];
                                    })->all();

        $this
            ->addField(
                SharpFormTextField::make("nom")
                    ->setLabel("Nom")
            )->addField(
                SharpFormNumberField::make("home_position")
                    ->setLabel("Position (résultats)")
                    ->setShowControls()
            )->addField(
                SharpFormNumberField::make("navbar_position")
                    ->setLabel("Position (navbar)")
                    ->setShowControls()
            )->addField(
                SharpFormTextField::make("updated_at")
                    ->setLabel("Modifié le")
                    ->setFormatter($timestampFormatter)
                    ->setReadOnly(true)
            )->addField(
                SharpFormListField::make("competitions")
                    ->setLabel("Compétitions visible dans la navbar")
                    ->setAddable()
                    ->setMaxItemCount(20) // 20 compétitions max par sport
                    ->setRemovable()
                    ->addItemField(
                        SharpFormSelectField::make("competition_id",
                            $competitions
                        )->setDisplayAsDropdown()
                        ->setLabel("Compétition")
                    )->addItemField(
                        SharpFormNumberField::make("position")
                            ->setLabel("Position")
                            ->setMin(1)
                            ->setShowControls()
                    )
            );
    }

    /**
     * Build form layout using ->addTab() or ->addColumn()
     *
     * @return void
     */
    public function buildFormLayout(): void
    {
        $this->addColumn(12, function (FormLayoutColumn $column) {
            $column->withFields('nom|6', 'updated_at|6', 'home_position|6', 'navbar_position|6');
        });

        $this->addColumn(12, function(FormLayoutColumn $column) {
            $column->withSingleField("competitions", function(FormLayoutColumn $listItem) {
                 $listItem->withFields("competition_id|6", "position|6");
            });
        });
    }
}
