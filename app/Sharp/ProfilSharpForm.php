<?php

namespace App\Sharp;

use App\Role;
use App\User;
use App\Jobs\ProcessCacheReload;
use Code16\Sharp\Form\SharpSingleForm;
use Illuminate\Support\Facades\Validator;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;

class ProfilSharpForm extends SharpSingleForm
{
    use WithSharpFormEloquentUpdater;
    
    /**
     * Retrieve a Model for the form and pack all its data as JSON.
     *
     * @return array
     */
    public function findSingle(): array
    {
        $user = User::findOrFail(auth()->id());
        return $this->setCustomTransformer("role", function ($role, $user) {
            return $user->role->name;
        })->setCustomTransformer("region", function ($region, $user) {
            return $user->region->nom;
        })->transform(
            $user
        );
    }

    /**
     * Update the Model.
     *
     * @param array $data
     */
    public function updateSingle(array $data)
    {
        $user = User::findOrFail(auth()->id());

        // On valide la requète
        $rules = User::rules($user);
        $messages = $rules['messages'] ?? [];
        $rules = [
            'first_name' => $rules['rules']['first_name'],
            'name' => $rules['rules']['name'],
            'pseudo' => $rules['rules']['pseudo'],
        ];
        
        $data = Validator::make($data, $rules, $messages)->validate();
        $user->update($data);

        forgetCaches('users', $user);
        ProcessCacheReload::dispatch('users', $user->id);
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
            $column->withFields('first_name|6','name|6', 'email|6', 'pseudo|6', 'role_id|6');
        });
    }
}
