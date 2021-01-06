<?php

namespace App\Sharp;

use App\Role;
use App\User;
use Code16\Sharp\Form\SharpForm;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;

class UserSharpForm extends SharpForm
{

    public function find($id): array
    {
        $user = User::findOrFail($id)->makeVisible('secret');
        return $this->transform(
            $user
        );
    }

    public function update($id, array $data)
    {
        User::findOrFail($id)->update($data);
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
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
            )->addField(
                SharpFormSelectField::make("role_id",
                    Role::orderBy("name")
                    ->get()->map(function($role) {
                        return [
                            "id" => $role->id,
                            "label" => $role->name
                        ];
                    })->all()
                )
                ->setLabel("Role")
                ->setDisplayAsDropdown()
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
            $column->withFields('first_name|6','name|6', 'email|6', 'pseudo|6', 'role_id|6');
        });
    }
}
