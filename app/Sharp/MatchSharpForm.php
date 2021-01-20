<?php

namespace App\Sharp;

use App\Match;
use App\Equipe;
use App\Journee;
use App\MatchInfo;
use App\Jobs\ProcessCacheReload;
use Code16\Sharp\Form\SharpForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Fields\SharpFormDateField;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use App\Sharp\Formatters\TimestampSharpFormatter;
use Code16\Sharp\Form\Fields\SharpFormCheckField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;

class MatchSharpForm extends SharpForm
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
        $match = Match::findOrFail($id);
        $matchInfos = $match->infos();

        // On insère les propriétés supplémentaires dans l'objet $match
        $proprietes = config('listes.proprietes-matches');
        foreach ($proprietes as $id => $propriete){
            $match[$propriete[0]] = '';
            if(isset($matchInfos[$propriete[0]]))
                if(in_array($propriete[0], ['tab_eq_dom', 'tab_eq_ext']))
                    $match[$propriete[0]] = $matchInfos[$propriete[0]];
                else
                    $match[$propriete[0]] = true;

        }

        return $this->setCustomTransformer("saison", function ($saison, $match) {
            $saison = $match->journee->saison;
            return $saison->competition->nom .' '. $saison->nom;
        })->setCustomTransformer("user", function ($user, $article) {
            return $article->user->pseudo ?? '';
        })->transform($match);
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed the instance id
     */
    public function update($id, array $data)
    {
        $match = $id ? Match::findOrFail($id) : new Match;
        $ignore = ['saison', 'forfait_eq_dom', 'forfait_eq_ext', 'penalite_eq_dom', 'penalite_eq_ext', 'avec_tirs_au_but', 'tab_eq_dom', 'tab_eq_ext'];

        // Si le match existe déjà
        if(isset($match->id)){
            $ignore[] = 'uniqid';
        }else{
            $data['uniqid'] = uniqid();
        }
        
        // On valide la requète
        $rules = Match::rules($match);
        $messages = $rules['messages'];
        $rules = $rules['rules'];
        $data['user_id'] = Auth::id();
        $data = Validator::make($data, $rules, $messages)->validate();

        // On supprime toutes les infos supplémentaires du match : forfaits, pénalités, etc...
        $ids = $match->matchInfos->pluck('id');
        // foreach ($ids as $id)
            // forgetCaches('match_infos', $id); // Le cache sera rechargé avec la méthode cacheLiee depuis les Jobs
        MatchInfo::destroy($ids);

        // On insère les nouvelles propriétés supplémentaires du match : pénalités, forfaits, etc...
        $proprietes = config('listes.proprietes-matches');
        foreach ($proprietes as $id => $propriete){
            if(isset($data[$propriete[0]]) && $data[$propriete[0]] !== false && $data[$propriete[0]] !== NULL) // Pour prendre en compte le tab à 0 par exemple
                MatchInfo::create([
                    'match_id' => $match->id,
                    'propriete_id' => $id,
                    'valeur' => $data[$propriete[0]]
                ]);
        }
        // ProcessCacheReload::dispatch('match_infos');

        $this->ignore($ignore)->save($match, $data);

        // Rechargement des caches liés au match
        forgetCaches('matches', $match);
        ProcessCacheReload::dispatch('matches', $match->id);
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $match = Match::findOrFail($id);
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
        $timestampFormatter = new TimestampSharpFormatter;
        $equipes = Equipe::join('sports', 'sport_id', 'sports.id')
            ->where('sports.slug', $this->sportSlug)
            ->select('equipes.*')
            ->orderBy('equipes.nom')->get()->map(function($equipe) {
            return [
                "id" => $equipe->id,
                "label" => $equipe->nom
            ];
        })->all();

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
                SharpFormDateField::make("date")
                    ->setLabel("Date")
                    ->setDisplayFormat('DD/MM/YYYY')
            )->addField(
                SharpFormTextField::make("heure")
                    ->setLabel("Heure")
            )->addField(
                SharpFormCheckField::make("forfait_eq_dom", "Forfait (domicile)")
                    ->setLabel("Forfait (domicile)")
            )->addField(
                SharpFormCheckField::make("forfait_eq_ext", "Forfait (extérieur)")
                    ->setLabel("Forfait (extérieur)")
            )->addField(
                SharpFormCheckField::make("avec_tirs_au_but", "Tirs au but?")
                    ->setLabel("Tirs au but?")
            )->addField(
                SharpFormTextField::make("tab_eq_dom", "Tirs au but (domicile)")
                    ->setLabel("Tirs au but (domicile)")
            )->addField(
                SharpFormTextField::make("tab_eq_ext", "Tirs au but (extérieur)")
                    ->setLabel("Tirs au but (extérieur)")
            )->addField(
                SharpFormCheckField::make("penalite_eq_dom", "Pénalité (domicile)")
                    ->setLabel("Pénalité (domicile)")
            )->addField(
                SharpFormCheckField::make("penalite_eq_ext", "Pénalité (extérieur)")
                    ->setLabel("Pénalité (extérieur)")
            )->addField(
                SharpFormSelectField::make("journee_id",
                    Journee::orderBy("saison_id")->orderBy('numero')
                    ->join('saisons', 'saison_id', 'saisons.id')
                    ->join('competitions', 'competition_id', 'competitions.id')
                    ->join('sports', 'sport_id', 'sports.id')
                    ->where('sports.slug', $this->sportSlug)
                    ->where('finie', 0)
                    ->select('journees.*')
                    ->get()->map(function($journee) {
                        $saison = $journee->saison;
                        $competition = $saison->competition;
                        return [
                            "id" => $journee->id,
                            "label" => $competition->nom . ' ' . $saison->nom . ' - ' . $journee->nom
                        ];
                    })->all()
                )
                ->setLabel("Journée")
                ->setDisplayAsDropdown()
                ->setMultiple(false)
            )->addField(
                SharpFormSelectField::make("equipe_id_dom",
                    $equipes
                )
                ->setLabel("Domicile")
                ->setDisplayAsDropdown()
                ->setMultiple(false)
            )->addField(
                SharpFormSelectField::make("equipe_id_ext",
                    $equipes
                )
                ->setLabel("Extérieur")
                ->setDisplayAsDropdown()
                ->setMultiple(false)
            )->addField(
                SharpFormTextField::make("updated_at")
                    ->setLabel("Modifié le")
                    ->setFormatter($timestampFormatter)
                    ->setReadOnly(true)
            )->addField(
                SharpFormTextField::make("user")
                    ->setLabel("Modifié par")
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
            $column->withFields('saison|6', 'uniqid|6', 'journee_id|6', 'acces_bloque|6', 'date|6', 'heure|6', 'equipe_id_dom|6', 'equipe_id_ext|6');
            $column->withFields('score_eq_dom|6', 'score_eq_ext|6', 'forfait_eq_dom|3', 'penalite_eq_dom|3', 'forfait_eq_ext|3', 'penalite_eq_ext|3');
            $column->withFields('avec_tirs_au_but', 'tab_eq_dom|6', 'tab_eq_ext|6', 'updated_at|6', 'user|6');
        });

    }
}
