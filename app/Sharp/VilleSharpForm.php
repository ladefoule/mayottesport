<?php

namespace App\Sharp;

use App\Ville;
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

class VilleSharpForm extends SharpForm
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
        $ville = Ville::findOrFail($id);

        return $this->transform(
            $ville
        );
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed the instance id
     */
    public function update($id, array $data)
    {
        $ville = $id ? Ville::findOrFail($id) : new Ville;    
        
        // On valide la requète
        $rules = Ville::rules($ville);
        $rules = $rules['rules'];

        $dataUpdate = Validator::make($data, $rules)->validate();

        $this->save($ville, $dataUpdate);

        forgetCaches('villes', $ville);
        ProcessCacheReload::dispatch('villes', $ville->id);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $ville = Ville::findOrFail($id);
        forgetCaches('villes', $ville);

        $ville->navbar()->detach();
        $ville->delete();

        ProcessCacheReload::dispatch('villes');
    }

    /**
     * Build form fields using ->addField()
     *
     * @return void
     */
    public function buildFormFields()
    {        
        $this
            ->addField(
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
            $column->withFields('nom|6');
        });
    }
}
