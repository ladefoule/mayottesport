<?php

namespace App\Sharp;

use App\Role;
use App\User;
use App\Region;
use Code16\Sharp\Show\SharpSingleShow;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Show\Layout\ShowLayoutColumn;
use Code16\Sharp\Show\Layout\ShowLayoutSection;
use Code16\Sharp\Show\Fields\SharpShowTextField;

class ProfilSharpShow extends SharpSingleShow
{
    /**
     * Retrieve a Model for the form and pack all its data as JSON.
     *
     * @return array
     */
    function findSingle(): array
    {
        return $this->setCustomTransformer(
            "role",
            function ($value, $user) {
                return Role::where("id", $user->role_id)->get()->pluck('nom')->first();
            }
        )->setCustomTransformer(
            "region",
            function ($value, $user) {
                return Region::where("id", $user->region_id)->get()->pluck('nom')->first();
            }
        )->setCustomTransformer(
            "nomprenom",
            function ($value, $user) {
                return $user->name . ' ' . $user->first_name;
            }
        )->transform(User::findOrFail(auth()->id()));
    }

    /**
     * Build show fields using ->addField()
     *
     * @return void
     */
    public function buildShowFields()
    {
        $this
            ->addField(
                SharpShowTextField::make("nomprenom")
                    ->setLabel("Nom:")
            )->addField(
                SharpShowTextField::make("email")
                    ->setLabel("Email:")
            )->addField(
                SharpShowTextField::make("pseudo")
                    ->setLabel("Pseudo:")
            )->addField(
                SharpShowTextField::make("role")
                    ->setLabel("Role:")
            )->addField(
                SharpShowTextField::make("region")
                    ->setLabel("Région:")
            );
    }

    /**
     * Build show layout using ->addTab() or ->addColumn()
     *
     * @return void
     */
    public function buildShowLayout()
    {
        $this
        ->addSection('Mon compte', function (ShowLayoutSection $section) {
            $section
                ->addColumn(8, function (ShowLayoutColumn $column) {
                    $column
                        ->withSingleField("nomprenom")
                        ->withSingleField("email")
                        ->withSingleField("pseudo")
                        ->withSingleField("role")
                        ->withSingleField("region");
                });
        });
    }

    function buildShowConfig()
    {

    }
}
