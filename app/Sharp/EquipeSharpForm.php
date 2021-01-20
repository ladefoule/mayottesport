<?php

namespace App\Sharp;

use App\Sport;
use App\Ville;
use App\Equipe;
use Illuminate\Support\Str;
use App\Jobs\ProcessCrudTable;
use Code16\Sharp\Form\SharpForm;
use Illuminate\Support\Facades\Validator;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Fields\SharpFormCheckField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;

class EquipeSharpForm extends SharpForm
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
        $equipe = Equipe::findOrFail($id); 
        return $this->transform(
            $equipe
        );
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed the instance id
     */
    public function update($id, array $data)
    {
        $equipe = $id ? Equipe::findOrFail($id) : new Equipe;    
        
        $data['slug'] = Str::slug($data['nom']);
        $data['slug_complet'] = Str::slug($data['nom_complet']);

        
        // Si l'équipe existe déjà
        if(isset($equipe->id)){
            $data['uniqid'] = $equipe->uniqid;
        }else{
            $data['uniqid'] = uniqid();
        }
        
        // On valide la requète
        $rules = Equipe::rules($equipe);
        $messages = $rules['messages'] ?? [];
        $rules = $rules['rules'];
        Validator::make($data, $rules, $messages)->validate();

        $this->save($equipe, $data);

        forgetCaches('equipes', $equipe);
        ProcessCrudTable::dispatch('equipes', $equipe->id);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $equipe = Equipe::findOrFail($id);

        forgetCaches('saisons', $equipe);
        $equipe->delete();
        ProcessCrudTable::dispatch('saisons');
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
        
        $villes = Ville::orderBy('villes.nom')->get()->map(function($ville) {
                return [
                    "id" => $ville->id,
                    "label" => $ville->nom
                ];
            })->all();

        $this
            ->addField(
                SharpFormSelectField::make("sport_id", $sports)
                ->setLabel("Sport")
                ->setDisplayAsDropdown()
            )->addField(
                SharpFormSelectField::make("ville_id", $villes)
                ->setLabel("Ville")
                ->setDisplayAsDropdown()
            )/* ->addField(
                SharpFormCheckField::make("feminine", 'Féminine')
                    ->setLabel("Féminine")
            )->addField(
                SharpFormCheckField::make("non_mahoraise", 'Non mahoraise')
                    ->setLabel("Non mahoraise")
            ) */->addField(
                SharpFormTextField::make("nom")
                    ->setLabel("Nom")
            )->addField(
                SharpFormTextField::make("nom_complet")
                    ->setLabel("Nom complet")
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
            $column->withFields('sport_id|6', 'nom|6', 'ville_id|6', 'nom_complet|6'/* , 'feminine|3', 'non_mahoraise|3' */);
        });
    }
}
