<?php

namespace App\Sharp;

use App\Equipe;
use App\Saison;
use App\Competition;
use App\Jobs\ProcessCrudTable;
use Code16\Sharp\Form\SharpForm;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Fields\SharpFormCheckField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;

class SaisonSharpForm extends SharpForm
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
        $saison = Saison::findOrFail($id); 
        // Les équipes liés
        $equipes = $saison->equipes->all();
        foreach ($equipes as $equipe)
            $equipe = [
                'id' => $equipe->id,
                'label' => $equipe->nom,
            ];

        return $this->setCustomTransformer("equipes", function ($equipes_, $saison) use($equipes) {
            return $equipes;
        })->transform(
            $saison
        );
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed the instance id
     */
    public function update($id, array $data)
    {
        $saison = $id ? Saison::where('uniqid', $id)->firstOrFail() : new Saison;    
        $ignore = ['equipes', 'saisons'];  

        $this->ignore($ignore)->save($saison, $data);

        $saison->equipes()->detach(); // Supprime toutes les relations equipe/saison concernant la saison
        foreach ($data['equipes'] as $equipe)
            $saison->equipes()->attach($equipe['id']);

        forgetCaches('saisons', $saison);
        ProcessCrudTable::dispatch('saisons', $saison->id);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $saison = Saison::findOrFail($id);

        forgetCaches('saisons', $saison);
        $saison->delete();
        ProcessCrudTable::dispatch('saisons');
    }

    /**
     * Build form fields using ->addField()
     *
     * @return void
     */
    public function buildFormFields()
    {
        $equipes = Equipe::join('sports', 'sport_id', 'sports.id')
                ->where('sports.slug', $this->sportSlug)
                ->select('equipes.*')
                ->orderBy('equipes.nom')->get()->map(function($equipe) {
                    return [
                        "id" => $equipe->id,
                        "label" => $equipe->nom
                    ];
                })->all();

        $competitions = Competition::join('sports', 'sport_id', 'sports.id')
            ->where('sports.slug', $this->sportSlug)
            ->select('competitions.*')
            ->orderBy('competitions.nom')->get()->map(function($competition) {
                return [
                    "id" => $competition->id,
                    "label" => $competition->nom
                ];
            })->all();

        $this
            ->addField(
                SharpFormSelectField::make("competition_id", $competitions)
                ->setLabel("Compétition")
                ->setDisplayAsDropdown()
            )->addField(
                SharpFormCheckField::make("finie", 'Finie')
                    ->setLabel("Finie")
            )->addField(
                SharpFormTextField::make("nb_journees")
                    ->setLabel("Nombre de journées")
            )->addField(
                SharpFormTextField::make("annee_debut")
                    ->setLabel("Année début")
            )->addField(
                SharpFormTextField::make("annee_fin")
                    ->setLabel("Année fin")
            )->addField(
                SharpFormSelectField::make("equipes", $equipes)
                ->setLabel("Equipes")
                ->setDisplayAsDropdown()
                ->setClearable(true)
                ->setMultiple(true)
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
            $column->withFields('competition_id|6', 'finie|6', 'annee_debut|6', 'annee_fin|6', 'nb_journees|6', 'equipes|6');
        });
    }
}
