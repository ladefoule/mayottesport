<?php

namespace App\Sharp;

use App\Sport;
use App\Bareme;
use App\BaremeInfo;
use Illuminate\Support\Str;
use App\Jobs\ProcessCacheReload;
use Code16\Sharp\Form\SharpForm;
use Illuminate\Support\Facades\Validator;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use App\Sharp\Formatters\TimestampSharpFormatter;
use Code16\Sharp\Form\Fields\SharpFormCheckField;
use Code16\Sharp\Form\Fields\SharpFormNumberField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;

class BaremeVolleySharpForm extends SharpForm
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
        $bareme = Bareme::findOrFail($id);
        $proprietes = config('listes.proprietes-baremes');

        foreach ($bareme->baremeInfos as $info)
            $bareme[$proprietes[$info->propriete_id][0]] = $info->valeur;

        return $this->transform(
            $bareme
        );
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed the instance id
     */
    public function update($id, array $data)
    {
        $bareme = $id ? Bareme::findOrFail($id) : new Bareme;    
        
        // On valide la requète
        // $rules = Bareme::rules($bareme);
        // $messages = $rules['messages'] ?? [];
        $rules = [
            'victoire_3_0' => 'required|integer|min:0|max:3',
            'victoire_3_1' => 'required|integer|min:0|max:3',
            'victoire_3_2' => 'required|integer|min:0|max:3',
            'defaite_0_3' => 'required|integer|min:0|max:3',
            'defaite_1_3' => 'required|integer|min:0|max:3',
            'defaite_2_3' => 'required|integer|min:0|max:3',
            'forfait' => 'required|integer|min:-1|max:0',
        ];

        Validator::make($data, $rules)->validate();

        $bareme->update([
            'forfait' => $data['forfait']
        ]);

        // On supprime toutes les infos supplémentaires du match : forfaits, pénalités, etc...
        $ids = $bareme->baremeInfos->pluck('id');
        BaremeInfo::destroy($ids);

        // On insère les nouvelles propriétés supplémentaires du match : pénalités, forfaits, etc...
        $proprietes = config('listes.proprietes-baremes');
        foreach ($proprietes as $id => $propriete){
            if(isset($data[$propriete[0]]) && $data[$propriete[0]] !== false && $data[$propriete[0]] !== NULL)
                BaremeInfo::create([
                    'bareme_id' => $bareme->id,
                    'propriete_id' => $id,
                    'valeur' => $data[$propriete[0]]
                ]);
        }

        forgetCaches('baremes', $bareme);
        ProcessCacheReload::dispatch('baremes', $bareme->id);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $bareme = Bareme::findOrFail($id);

        forgetCaches('baremes', $bareme);
        $bareme->delete();
        ProcessCacheReload::dispatch('baremes');
    }

    /**
     * Build form fields using ->addField()
     *
     * @return void
     */
    public function buildFormFields()
    {        
        $sports = Sport::orderBy('sports.nom')->get()->map(function($sport) {
                return [
                    "id" => $sport->id,
                    "label" => $sport->nom
                ];
            })->all();

        $this
            ->addField(
                SharpFormTextField::make("nom")
                    ->setLabel("Nom")
            )->addField(
                SharpFormNumberField::make("victoire_3_0")
                    ->setLabel("Victoire 3/0")
                    ->setShowControls()
                    ->setMin(0)
                    ->setMax(3)
            )->addField(
                SharpFormNumberField::make("victoire_3_1")
                    ->setLabel("Victoire 3/1")
                    ->setShowControls()
                    ->setMin(0)
                    ->setMax(3)
            )->addField(
                SharpFormNumberField::make("victoire_3_2")
                    ->setLabel("Victoire 3/2")
                    ->setShowControls()
                    ->setMin(0)
                    ->setMax(3)
            )->addField(
                SharpFormNumberField::make("defaite_0_3")
                    ->setLabel("Défaite 0/3")
                    ->setShowControls()
                    ->setMin(0)
                    ->setMax(3)
            )->addField(
                SharpFormNumberField::make("defaite_1_3")
                    ->setLabel("Défaite 1/3")
                    ->setShowControls()
                    ->setMin(0)
                    ->setMax(3)
            )->addField(
                SharpFormNumberField::make("defaite_2_3")
                    ->setLabel("Défaite 2/3")
                    ->setShowControls()
                    ->setMin(0)
                    ->setMax(3)
            )->addField(
                SharpFormNumberField::make("forfait")
                    ->setLabel("Forfait")
                    ->setShowControls()
                    ->setMax(0)
            )->addField(
                SharpFormSelectField::make("sport_id", $sports)
                ->setLabel("Sport")
                ->setDisplayAsDropdown()
                ->setReadOnly()
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
            $column->withFields('nom|6', 'sport_id|6', 'victoire_3_0|6', 'victoire_3_1|6', 'victoire_3_2|6', 'defaite_0_3|6', 'defaite_1_3|6', 'defaite_2_3|6', 'forfait|6');
        });
    }
}
