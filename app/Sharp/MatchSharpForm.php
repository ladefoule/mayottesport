<?php

namespace App\Sharp;

use App\User;
use App\Match;
use App\Equipe;
use App\Journee;
use App\MatchInfo;
use App\Jobs\ProcessCrudTable;
use Code16\Sharp\Form\SharpForm;
use Illuminate\Support\Facades\Log;
use App\Sharp\Auth\SharpCheckHandler;
use Illuminate\Support\Facades\Validator;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormListField;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Fields\SharpFormCheckField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Fields\SharpFormAutocompleteField;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;
use Code16\Sharp\Form\Fields\SharpFormAutocompleteListField;

class MatchSharpForm extends SharpForm
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
        $match = Match::where('uniqid', $id)->firstOrFail();
        $matchInfos = $match->infos();
        return $this->setCustomTransformer("saison", function ($label, $match) {
            return $match->journee->saison->crud_name;
        })->setCustomTransformer("forfait_eq_dom", function ($label, $match) use($matchInfos){
            return isset($matchInfos['forfait_eq_dom']);
        })->setCustomTransformer("forfait_eq_ext", function ($label, $match) use($matchInfos){
            return isset($matchInfos['forfait_eq_ext']);
        })->setCustomTransformer("penalite_eq_dom", function ($label, $match) use($matchInfos){
            return isset($matchInfos['penalite_eq_dom']);
        })->setCustomTransformer("penalite_eq_ext", function ($label, $match) use($matchInfos){
            return isset($matchInfos['penalite_eq_ext']);
        })->transform($match);
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed the instance id
     */
    public function update($id, array $data)
    {
        $ignore = ['saison', 'uniqid', 'forfait_eq_dom', 'forfait_eq_ext', 'penalite_eq_dom', 'penalite_eq_ext'];
        $match = Match::where('uniqid', $id)->firstOrFail();

        // On valide la requète
        Validator::make($data, Match::rules($match)['rules'])->validate();

        // On supprime toutes les infos supplémentaires du match : forfaits, pénalités, etc...
        MatchInfo::destroy($match->matchInfos->pluck('id'));

        // On insère les nouvelles propriétés supplémentaires
        $proprietes = config('constant.match');
        foreach ($proprietes as $id => $propriete){
            if($data[$propriete[0]])
                MatchInfo::create([
                    'match_id' => $match->id,
                    'propriete_id' => $id,
                    'valeur' => $data[$propriete[0]]
                ]);
        }

        //     $collect[$correspondances[$info->propriete_id][0]] = $info->valeur;

        $this->ignore($ignore)->save($match, $data);

        // Rechargement des caches liés au match
        forgetCaches('matches', $match);
        ProcessCrudTable::dispatch('matches', $match->id);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $match = Match::where('uniqid', $id)->firstOrFail();
        // Suppression des caches liés au match
        forgetCaches('matches', $match);

        $match->delete();

        // Rechargement du cache index-matches
        index('matches');
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
                SharpFormTextField::make("uniqid")
                    ->setLabel("Id")
                    ->setReadOnly()
            )->addField(
                SharpFormTextField::make("saison")
                    ->setLabel("saison")
                    ->setReadOnly()
            )->addField(
                SharpFormCheckField::make("acces_bloque", "Accès bloqué")
                    ->setLabel("Accès bloqué")
                    // ->setReadOnly()
            )->addField(
                SharpFormTextField::make("score_eq_dom")
                    ->setLabel("Score (domicile)")
            )->addField(
                SharpFormTextField::make("score_eq_ext")
                    ->setLabel("Score (extérieur)")
            )->addField(
                SharpFormCheckField::make("forfait_eq_dom", "Forfait (domicile)")
                    ->setLabel("Forfait (domicile)")
            )->addField(
                SharpFormCheckField::make("forfait_eq_ext", "Forfait (exterieur)")
                    ->setLabel("Forfait (extérieur)")
            )->addField(
                SharpFormCheckField::make("penalite_eq_dom", "penalite (domicile)")
                    ->setLabel("Pénalité (domicile)")
            )->addField(
                SharpFormCheckField::make("penalite_eq_ext", "penalite (exterieur)")
                    ->setLabel("Pénalité (extérieur)")
            )->addField(
                SharpFormSelectField::make("journee_id",
                    Journee::orderBy("saison_id")->orderBy('numero')->get()->map(function($journee) {
                        return [
                            "id" => $journee->id,
                            "label" => $journee->crud_name
                        ];
                    })->all()
                )
                ->setLabel("Journée")
                ->setDisplayAsDropdown()
                ->setMultiple(false)
            )->addField(
                SharpFormSelectField::make("equipe_id_dom",
                    Equipe::orderBy("sport_id")->orderBy('nom')->get()->map(function($equipe) {
                        return [
                            "id" => $equipe->id,
                            "label" => $equipe->crud_name
                        ];
                    })->all()
                )
                ->setLabel("Domicile")
                ->setDisplayAsDropdown()
                ->setMultiple(false)
            )->addField(
                SharpFormSelectField::make("equipe_id_ext",
                    Equipe::orderBy("sport_id")->orderBy('nom')->get()->map(function($equipe) {
                        return [
                            "id" => $equipe->id,
                            "label" => $equipe->crud_name
                        ];
                    })->all()
                )
                ->setLabel("Exterieur")
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
            $column->withFields('saison|6', 'uniqid|6', 'journee_id|6', 'acces_bloque|6', 'equipe_id_dom|6', 'equipe_id_ext|6');
            $column->withFields('score_eq_dom|6', 'score_eq_ext|6', 'forfait_eq_dom|3', 'penalite_eq_dom|3', 'forfait_eq_ext|3', 'penalite_eq_ext|3');
        });

    }
}
