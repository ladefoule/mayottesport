<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['date', 'heure', 'acces_bloque', 'nb_modifs', 'score_eq_dom', 'score_eq_ext',
                            'journee_id', 'terrain_id', 'equipe_id_dom', 'equipe_id_ext', 'uniqid'];

    /**
     * La fonction nous renvoie le résultat du matchpar rapport à l'équipe $equipeId
     * Elle renvoie false si le match ne s'est pas encore joué ou si l'id ne correspond pas aux équipes
     *
     * @param integer $equipe_id
     * @return void
     */
    public function resultat(int $equipeId)
    {
        // Si l'id saisi ne correspond à aucune des deux équipes
        if($this->equipe_id_dom != $equipeId && $this->equipe_id_ext != $equipeId)
            return false;

        $score_eq_dom = $this->score_eq_dom;
        $score_eq_ext = $this->score_eq_ext;

        // Si l'un des scores est null ou vide
        if(strlen($score_eq_dom) == 0 || strlen($score_eq_ext) == 0)
            return false;

        if($score_eq_dom > $score_eq_ext)
            $resultat = ($equipeId == $this->equipe_id_dom) ? 'victoire' : 'defaite';
        else if($score_eq_dom < $score_eq_ext)
            $resultat = ($equipeId == $this->equipe_id_dom) ? 'defaite' : 'victoire';
        else
            $resultat = 'nul';

        if($this->equipe_id_dom == $equipeId){
            $marques = $score_eq_dom;
            $encaisses = $score_eq_ext;
        }else{
            $encaisses = $score_eq_dom;
            $marques = $score_eq_ext;
        }

        return [
            'resultat' => $resultat,
            'marques' => $marques,
            'encaisses' => $encaisses
        ];
    }

    /**
     * Les règles de validations
     *
     * @param Match $match
     * @return array
     */
    public static function rules( Match $match = null)
    {
        $equipeIdDom = request()->equipe_id_dom;
        $equipeIdExt = request()->equipe_id_ext;
        $journeeId = request()->journee_id;
        $uniqueEquipeDom = Rule::unique('matches')->where(function ($query) use ($equipeIdDom, $journeeId) {
            return $query->whereJourneeId($journeeId)
                        ->whereEquipeIdDom($equipeIdDom);
        })->ignore($match);
        $uniqueEquipeExt = Rule::unique('matches')->where(function ($query) use ($equipeIdExt, $journeeId) {
            return $query->whereEquipeIdExt($equipeIdExt)->whereJourneeId($journeeId);
        })->ignore($match);

        request()->acces_bloque = request()->has('acces_bloque');
        $rules = [
            'journee_id' => 'required|exists:journees,id',
            'terrain_id' => 'required|exists:terrains,id',
            'date' => 'nullable|date|date_format:Y-m-d',
            'heure' => 'nullable|string|size:5',
            'equipe_id_dom' => ['required','integer','exists:equipes,id',$uniqueEquipeDom],
            'equipe_id_ext' => ['required','integer','exists:equipes,id',$uniqueEquipeExt],
            'score_eq_dom' => 'nullable|integer|min:0|required_with:score_eq_ext',
            'score_eq_ext' => 'nullable|integer|min:0|required_with:score_eq_dom',
            'acces_bloque' => 'boolean'
        ];
        $msg = "Cette équipe participe déjà à une rencontre de cette journée.";
        $messages = [
            'equipe_id_dom.unique' => $msg,
            'equipe_id_ext.unique' => $msg
        ];
        return ['rules' => $rules, 'messages' => $messages];
    }

    /**
     * Définition de l'affichage dans le CRUD (back-office)
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        return indexCrud('journees')[$this->journee_id]['crud_name'] . ' - ' . indexCrud('equipes')[$this->equipe_id_dom]['nom'] . ' # ' . indexCrud('equipes')[$this->equipe_id_ext]['nom'];
    }

    /**
     * Les informations du match dont ont besoin les views match/resultat et horaire
     *
     * @return void
     */
    public function infos()
    {
        $key = 'match-'.$this->uniqid;
        if(! Config::get('constant.activer_cache'))
            Cache::forget($key);

        if (Cache::has($key))
            return Cache::get($key);
        else
            return Cache::rememberForever($key, function () {
                return $this->genererInfos();
            });
    }

    /**
     * Génération des données du match
     *
     * @return array
     */
    public function genererInfos()
    {
        $equipeDom = $this->equipeDom;
        $equipeDomNomKebab = strToUrl($equipeDom->nom);
        $equipeExt = $this->equipeExt;
        $equipeExtNomKebab = strToUrl($equipeExt->nom);
        $journee = $this->journee;
        $saison = $this->journee->saison;
        $competition = $saison->competition;
        $competitionNomKebab = strToUrl($competition->nom);
        $sport = $competition->sport;
        $sportNomKebab = strToUrl($sport->nom);
        $commentaires = $this->commentaires->sortByDesc('created_at');
        foreach ($commentaires as $commentaire)
            $commentaire->pseudo = $commentaire->user->pseudo;


        return [
            'id' => $this->id,
            'id_eq_dom' => $this->id_eq_dom,
            'id_eq_ext' => $this->id_eq_ext,
            // 'crud_name' => $this->crud_name,
            'nom_eq_dom' => $equipeDom->nom,
            'href_eq_dom' => route('equipe.index', ['sport' => $sportNomKebab, 'equipe' => $equipeDomNomKebab, 'id' => $equipeDom->uniqid]),
            'fanion_eq_dom' => $equipeDom->fanion(),
            'nom_eq_ext' => $equipeExt->nom,
            'href_eq_ext' => route('equipe.index', ['sport' => $sportNomKebab, 'equipe' => $equipeExtNomKebab, 'id' => $equipeExt->uniqid]),
            'fanion_eq_ext' => $equipeExt->fanion(),
            'url' => $this->url(),
            'score' => $this->score(),
            'date_format' => $this->dateFormat(),
            'date' => $this->date,
            'heure' => $this->heure,
            'title' => "Match " . $equipeDom->nom . ' vs ' . $equipeExt->nom . ' - ' . $sport->nom . ' - ' . $competition->nom . ' ' . $saison->annee('/'),
            'acces_bloque' => $this->acces_bloque,
            'journee' => niemeJournee($journee->numero),
            'competition' => $competition->nom,
            'commentaires' => $commentaires,
            'score_eq_dom' => $this->score_eq_dom,
            'score_eq_ext' => $this->score_eq_ext,
            'href_resultat' => route('competition.match.resultat', ['sport' => $sportNomKebab, 'competition' => $competitionNomKebab,'id' => $this->uniqid]),
            'href_horaire' => route('competition.match.horaire', ['sport' => $sportNomKebab, 'competition' => $competitionNomKebab,'id' => $this->uniqid]),
            'href_match' => route('competition.match', [
                'id' => $this->uniqid,
                'sport' => $sportNomKebab,
                'competition' => $competitionNomKebab,
                'annee' => $saison->annee(),
                'equipeDom' => $equipeDomNomKebab,
                'equipeExt' => $equipeExtNomKebab
            ])
        ];
    }

    /**
     * Undocumented function
     *
     * @param Equipe $equipe
     * @return void
     */
    public function matchRender(Equipe $equipe)
    {
        $infos = $this->infos();
        $resultat = $this->resultat($equipe->id) ? $this->resultat($equipe->id)['resultat'] : '';
        return view('equipe.match', [
            'equipe' => $equipe,
            'match' => $infos,
            'resultat' => $resultat
        ])->render();
    }

    /**
     * L'url du match
     *
     * @return void
     */
    public function url()
    {
        $equipeDomKebabCase = strToUrl($this->equipeDom->nom);
        $equipeExtKebabCase = strToUrl($this->equipeExt->nom);
        $saison = $this->journee->saison;
        $annee = $saison->annee();
        $competition = $saison->competition;
        $sport = strToUrl($competition->sport->nom);
        $competition = strToUrl($competition->nom);

        return "/$sport/$competition/$annee/match-" . $equipeDomKebabCase ."_". $equipeExtKebabCase ."_" . $this->uniqid .".html";
    }

    /**
     * Affiche le score si renseigné, sinon affiche l'heure du match sinon affiche la date
     *
     * @return string
     */
    public function score()
    {
        $heure = $this->heure;
        $date = $this->dateFormat('d/m');
        if(strlen($this->score_eq_dom) == 0 || strlen($this->score_eq_ext) == 0){
            if($heure)
                return $heure;

            return $date;
        }
        return $this->score_eq_dom . ' - ' . $this->score_eq_ext;
    }

    /**
     * Retourne la date au format demandé.
     *
     * @param string $format
     * @return string
     */
    public function dateFormat(string $format = 'd/m/Y')
    {
        if($this->date == '')
            return '';

        return date($format, strtotime($this->date));
    }

    /**
     * Les infos supplémentaires liées au match
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matchInfos()
    {
        return $this->hasMany('App\MatchInfo');
    }

    /**
     * Les commentaires du match
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commentaires()
    {
        return $this->hasMany('App\Commentaire');
    }

    /**
     * La journée (dans la table journees) du match
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function journee()
    {
        return $this->belongsTo('App\Journee');
    }

    /**
     * Le terrain ou se joue le match
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function terrain()
    {
        return $this->belongsTo('App\Terrain');
    }

    /**
     * L'équipe qui reçoit ce match de competition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipeDom()
    {
        return $this->belongsTo('App\Equipe', 'equipe_id_dom');
    }

    /**
     * L'équipe qui se déplace pour ce match de competition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipeExt()
    {
        return $this->belongsTo('App\Equipe', 'equipe_id_ext');
    }
}
