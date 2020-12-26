<?php

namespace App\Sharp;

use App\Match;
use App\Equipe;
use App\Journee;
use App\MatchInfo;
use App\Jobs\ProcessCrudTable;
use Code16\Sharp\Form\SharpForm;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Fields\SharpFormCheckField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;

class MatchFootSharpForm extends SharpForm
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

        // On insère les propriétés supplémentaires dans l'objet $match
        $proprietes = config('constant.matches');
        foreach ($proprietes as $id => $propriete){
            $match[$propriete[0]] = '';
            if(isset($matchInfos[$propriete[0]]))
                if(in_array($propriete[0], ['tab_eq_dom', 'tab_eq_ext']))
                    $match[$propriete[0]] = $matchInfos[$propriete[0]];
                else
                    $match[$propriete[0]] = true;

        }

        return $this->setCustomTransformer("saison", function ($saison, $match) {
            return $match->journee->saison->crud_name;
        })->transform($match);
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed the instance id
     */
    public function update($id, array $data)
    {
        $ignore = ['saison', 'uniqid', 'forfait_eq_dom', 'forfait_eq_ext', 'penalite_eq_dom', 'penalite_eq_ext', 'tab_eq_dom', 'tab_eq_ext'];
        $match = Match::where('uniqid', $id)->firstOrFail();

        // On valide la requète
        $rules = Match::rules($match)['rules'];
        $rules['forfait_eq_dom'] = 'boolean';
        $rules['forfait_eq_ext'] = 'boolean';
        $rules['penalite_eq_dom'] = 'boolean';
        $rules['penalite_eq_ext'] = 'boolean';
        $rules['tab_eq_dom'] = 'nullable|required_with:tab_eq_ext|integer|min:0|max:20';
        $rules['tab_eq_ext'] = 'nullable|required_with:tab_eq_dom|integer|min:0|max:20';
        Validator::make($data, $rules)->validate();

        // On supprime toutes les infos supplémentaires du match : forfaits, pénalités, etc...
        MatchInfo::destroy($match->matchInfos->pluck('id'));

        // On insère les nouvelles propriétés supplémentaires du match : pénalités, forfaits, etc...
        $proprietes = config('constant.matches');
        foreach ($proprietes as $id => $propriete){
            if($data[$propriete[0]] !== false) // Pour prendre en compte le tab à 0 par exemple
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
                SharpFormTextField::make("tab_eq_dom", "Tirs au but (domicile)")
                    ->setLabel("Tirs au but (domicile)")
            )->addField(
                SharpFormTextField::make("tab_eq_ext", "Tirs au but (exterieur)")
                    ->setLabel("Tirs au but (extérieur)")
            )->addField(
                SharpFormCheckField::make("penalite_eq_dom", "penalite (domicile)")
                    ->setLabel("Pénalité (domicile)")
            )->addField(
                SharpFormCheckField::make("penalite_eq_ext", "penalite (exterieur)")
                    ->setLabel("Pénalité (extérieur)")
            )->addField(
                SharpFormSelectField::make("journee_id",
                    // indexCrud('journees')->map(function($journee, $key){
                    //     return [
                    //         'id' => $journee->id,
                    //         'label' => $journee->crud_name
                    //     ];
                    // })->all()
                    Journee::orderBy("saison_id")->orderBy('numero')
                    // ->join('saisons', 'saison_id', 'saisons.id')
                    // ->join('competitions', 'competition_id', 'competitions.id')
                    // ->join('sports', 'sport_id', 'sports.id')
                    // ->where('sports.nom', 'like', 'football')
                    // ->select('journees.*')
                    ->get()->map(function($journee) {
                        // $saison = index('saisons')[$journee->saison_id];//$journee->saison;
                        // $competition = index('competitions')[$saison->competition_id];//$saison->competition;
                        return [
                            "id" => $journee->id,
                            "label" => $journee->crud_name
                            // "label" => $competition->nom . ' ' . $saison->nom . ' - ' . $journee->nom
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
                            "label" => $equipe->nom
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
                            "label" => $equipe->nom
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
            $column->withFields('tab_eq_dom|6', 'tab_eq_ext|6');
        });

    }
}
