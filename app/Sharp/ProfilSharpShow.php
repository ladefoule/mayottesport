<?php

namespace App\Sharp;

use App\User;
use Code16\Sharp\Show\SharpSingleShow;
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
     * Build show fields using ->addField()
     *
     * @return void
     */
    public function buildShowFields()
    {
        $this->addField(
            SharpShowTextField::make("name")
                ->setLabel("Nom")
        )->addField(
            SharpShowTextField::make("first_name")
                ->setLabel("Prénom")
        )->addField(
            SharpShowTextField::make("pseudo")
                ->setLabel("Pseudo")
        )->addField(
            SharpShowTextField::make("email")
                ->setLabel("Email")
        )->addField(
            SharpShowTextField::make("role")
                ->setLabel("Rôle")
        )->addField(
            SharpShowTextField::make("region")
                ->setLabel("Région")
        );
    }

    /**
     * Build show layout using ->addTab() or ->addColumn()
     *
     * @return void
     */
    public function buildShowLayout()
    {
        $this->addSection(
            'PROFIL', 
            function(ShowLayoutSection $section) {
                $section->addColumn(
                    6, 
                    function(ShowLayoutColumn $column) {
                        $column->withSingleField("name")
                                ->withSingleField("first_name")
                                ->withSingleField("pseudo")
                                ->withSingleField("email")
                                ->withSingleField("role")
                                ->withSingleField("region")
                                ;
                    }
                );

                // $section->addColumn(
                //     6, 
                //     function(ShowLayoutColumn $column) {
                //         $column->withSingleField("name");
                //     }
                // );
            }
        );
    }

    function buildShowConfig()
    {
        //
    }
}
