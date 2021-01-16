<?php

namespace App\Sharp;

use App\Sport;
use App\Competition;
use Illuminate\Support\Str;
use App\Jobs\ProcessCrudTable;
use Code16\Sharp\Form\SharpForm;
use Illuminate\Support\Facades\Validator;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use App\Sharp\Formatters\TimestampSharpFormatter;
use Code16\Sharp\Form\Fields\SharpFormCheckField;
use Code16\Sharp\Form\Fields\SharpFormNumberField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;

class CompetitionSharpForm extends SharpForm
{
    use WithSharpFormEloquentUpdater;
    
    protected $sportSlug;

    /**
     * Retrieve a Model for the form and pack all its data as JSON.
     *
     * @param $id
     * @return array
     */
    public function find($id): array
    {
        $competition = Competition::findOrFail($id); 

        return $this->transform(
            $competition
        );
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed the instance id
     */
    public function update($id, array $data)
    {
        $competition = $id ? Competition::findOrFail($id) : new Competition;    
        
        // On valide la requète
        $rules = Competition::rules($competition);
        $messages = $rules['messages'];
        $rules = $rules['rules'];

        $data['slug'] = Str::slug($data['nom']);
        
        $ignore = ['updated_at'];
        // $rules = array_diff_key($rules, array_flip($ignore));

        Validator::make($data, $rules, $messages)->validate();

        $this->ignore($ignore)->save($competition, $data);

        forgetCaches('competitions', $competition);
        ProcessCrudTable::dispatch('competitions', $competition->id);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $competition = Competition::findOrFail($id);

        forgetCaches('competitions', $competition);
        $competition->delete();
        ProcessCrudTable::dispatch('competitions');
    }

    /**
     * Build form fields using ->addField()
     *
     * @return void
     */
    public function buildFormFields()
    {        
        $timestampFormatter = new TimestampSharpFormatter;

        $sports = Sport::orderBy('sports.nom')->get()->map(function($sport) {
                return [
                    "id" => $sport->id,
                    "label" => $sport->nom
                ];
            })->all();

        $typesConfig = config('listes.types-competitions');
        foreach ($typesConfig as $id => $type) {
            $types[] = [
                "id" => $id,
                "label" => $type[1]
            ];
        }

        $this
            ->addField(
                SharpFormTextField::make("nom")
                    ->setLabel("Nom")
            )->addField(
                SharpFormTextField::make("nom_complet")
                    ->setLabel("Nom complet")
            )->addField(
                SharpFormNumberField::make("home_position")
                    ->setLabel("Position (accueil)")
                    ->setShowControls()
            )->addField(
                SharpFormNumberField::make("index_position")
                    ->setLabel("Position (page sport)")
                    ->setShowControls()
            )->addField(
                SharpFormTextField::make("updated_at")
                    ->setLabel("Modifié le")
                    ->setFormatter($timestampFormatter)
                    ->setReadOnly(true)
            )->addField(
                SharpFormSelectField::make("sport_id", $sports)
                ->setLabel("Sport")
                ->setDisplayAsDropdown()
            )->addField(
                SharpFormSelectField::make("type",
                    $types ?? []
                )->setLabel("Priorité (page d'accueil)")
                ->setDisplayAsDropdown()
            );
    }

    /**
     * Build form layout using ->addTab() or ->addColumn()
     *
     * @return void
     */
    public function buildFormLayout()
    {
        $this->addColumn(12, function (FormLayoutColumn $column) {
            $column->withFields('nom|6', 'sport_id|6', 'nom_complet|6', 'type|6', 'home_position|6', 'index_position|6', 'updated_at|6');
        });
    }
}
