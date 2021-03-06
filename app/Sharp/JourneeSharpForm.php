<?php

namespace App\Sharp;

use App\Journee;
use App\Jobs\ProcessCrudTable;
use Code16\Sharp\Form\SharpForm;
use App\Sharp\Formatters\DateSharpFormatter;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormDateField;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;

class JourneeSharpForm extends SharpForm
{
    /**
     * Retrieve a Model for the form and pack all its data as JSON.
     *
     * @param $id
     * @return array
     */
    public function find($id): array
    {
        $journee = Journee::findOrFail($id);        

        return $this->setCustomTransformer("saison", function ($saison, $journee) {
            return $journee->saison->crud_name;
        })->transform(
            $journee
        );
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed the instance id
     */
    public function update($id, array $data)
    {
        $journee = Journee::findOrFail($id);
        $journee->update($data);
        forgetCaches('articles', $journee);
        ProcessCrudTable::dispatch('articles', $journee->id);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $journee = Journee::findOrFail($id);

        forgetCaches('articles', $journee);
        ProcessCrudTable::dispatch('articles');
    }

    /**
     * Build form fields using ->addField()
     *
     * @return void
     */
    public function buildFormFields()
    {
        $typesConfig = config('listes.types-journees');
        foreach($typesConfig as $id => $type){
            $types[] = [
                "id" => $id,
                "label" => $type[1]
            ];
        };

        $this
            ->addField(
                SharpFormTextField::make("saison")
                    ->setLabel("Saison")
                    ->setReadOnly(true)
            )->addField(
                SharpFormTextField::make("numero")
                    ->setLabel("Journée (numéro)")
            )->addField(
                SharpFormDateField::make("date")
                    ->setDisplayFormat('DD/MM/YYYY')
                    ->setLabel("Date")
            )->addField(
                SharpFormSelectField::make("type", $types)
                ->setLabel("Type")
                ->setDisplayAsDropdown()
                ->setClearable(true)
                ->setMultiple(false)
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
            $column->withFields('saison|6', 'numero|6', 'date|6', 'type|6');
        });
    }
}
