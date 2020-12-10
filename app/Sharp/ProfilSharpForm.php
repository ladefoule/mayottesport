<?php

namespace App\Sharp;

use App\User;
use Code16\Sharp\Form\SharpSingleForm;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;

class ProfilSharpForm extends SharpSingleForm
{
    use WithSharpFormEloquentUpdater;

    /**
     * Retrieve a Model for the form and pack all its data as JSON.
     *
     * @param $id
     * @return array
     */
    public function findSingle()
    {
        return $this->transform(User::findOrFail(auth()->id()));
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
                SharpFormTextField::make("first_name")
                    ->setLabel("Prénom")
            )->addField(
                SharpFormTextField::make("name")
                    ->setLabel("Nom")
            )->addField(
                SharpFormTextField::make("pseudo")
                    ->setLabel("Pseudo")
            )->addField(
                SharpFormTextField::make("email")
                    ->setLabel("Adresse mail")
                    ->setReadOnly(true)
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
            $column->withFields('first_name|6','name|6', 'email|6', 'pseudo|6');
        });
    }

    /**
     * @param array $data
     * @return mixed
     */
    protected function updateSingle(array $data)
    {
        $this->save(User::findOrFail(auth()->id()), $data);
    }
}
