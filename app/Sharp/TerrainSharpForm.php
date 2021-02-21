<?php

namespace App\Sharp;

use App\Ville;
use App\Terrain;
use App\Jobs\ProcessCacheReload;
use Code16\Sharp\Form\SharpForm;
use Illuminate\Support\Facades\Validator;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;

class TerrainSharpForm extends SharpForm
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
        $terrain = Terrain::findOrFail($id); 
        return $this->transform(
            $terrain
        );
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed the instance id
     */
    public function update($id, array $data)
    {
        $terrain = $id ? Terrain::findOrFail($id) : new Terrain;    
        
        // On valide la requète
        $rules = Terrain::rules($terrain);
        $messages = $rules['messages'] ?? [];
        $rules = $rules['rules'];
        Validator::make($data, $rules, $messages)->validate();

        $terrain = $this->save($terrain, $data);

        forgetCaches('terrains', $terrain);
        ProcessCacheReload::dispatch('terrains', $terrain->id);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $terrain = Terrain::findOrFail($id);

        forgetCaches('saisons', $terrain);
        $terrain->delete();
        ProcessCacheReload::dispatch('saisons');
    }

    /**
     * Build form fields using ->addField()
     *
     * @return void
     */
    public function buildFormFields()
    {        
        $villes = Ville::orderBy('villes.nom')->get()->map(function($ville) {
                return [
                    "id" => $ville->id,
                    "label" => $ville->nom
                ];
            })->all();

        $this
            ->addField(
                SharpFormSelectField::make("ville_id", $villes)
                ->setLabel("Ville")
                ->setDisplayAsDropdown()
            )->addField(
                SharpFormTextField::make("nom")
                    ->setLabel("Nom")
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
            $column->withFields('nom|6', 'ville_id|6');
        });
    }
}
