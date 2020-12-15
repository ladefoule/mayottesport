<?php

namespace App\Sharp;

use App\Match;
use App\Journee;
use Code16\Sharp\Form\SharpForm;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Show\Layout\ShowLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormListField;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Show\Fields\SharpShowListField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;

class MatchSharpForm extends SharpForm
{
    /**
     * Retrieve a Model for the form and pack all its data as JSON.
     *
     * @param $id
     * @return array
     */
    public function find($id): array
    {
        $match = Match::findOrFail($id);
        $journee = $match->journee;
        $saison = $journee->saison;
        $competition = $saison->competition;
        $sport = $competition->sport;

        $match->sport = $sport->nom;
        $match->competition = $competition->nom;
        $match->saison = $saison->nom;
        $match->journee = $journee->nom;
        return $this->transform(
            $match
        );
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed the instance id
     */
    public function update($id, array $data)
    {
        Match::findOrFail($id)->update($data);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        Match::findOrFail($id)->delete();
    }

    /**
     * Build form fields using ->addField()
     *
     * @return void
     */
    public function buildFormFields()
    {
        $journees = Journee::get()->all();
        $this
            ->addField(
                SharpFormTextField::make("uniqid")
                    ->setLabel("Id")
                    ->setReadOnly()
            )->addField(
                SharpFormTextField::make("sport")
                    ->setLabel("Sport")
            )->addField(
                SharpFormTextField::make("competition")
                    ->setLabel("Competition")
            )->addField(
                SharpFormTextField::make("saison")
                    ->setLabel("saison")
            )/* ->addField(
                SharpFormListField::make("journees",
                    $column->withSingleField("pieces", function(FormLayoutColumn $listItem) {
                        $listItem->withSingleField("acquisition_date")
                            ->withSingleField("title")
                            ->withSingleField("artist_id");
                    });
                )
                    ->setLabel("Journée")
            ) */;
    }

    /**
     * Build form layout using ->addTab() or ->addColumn()
     *
     * @return void
     */
    public function buildFormLayout()
    {
        $this->addColumn(12, function (FormLayoutColumn $column) {
            $column->withFields('uniqid|6','sport|6', 'competition|6', 'saison|6', 'journees|6');
        });

    }
}
