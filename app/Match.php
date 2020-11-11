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
     * @param Request $request
     * @param Match $match
     * @return array
     */
    public static function rules(Request $request, Match $match = null)
    {
        $equipeIdDom = $request['equipe_id_dom'] ?? 0;
        $equipeIdExt = $request['equipe_id_ext'] ?? 0;
        $journeeId = $request['journee_id'] ?? 0;
        $uniqueEquipeDomDom = Rule::unique('matches')->where(function ($query) use ($equipeIdDom, $journeeId) {
            return $query->whereEquipeIdDom($equipeIdDom)->whereJourneeId($journeeId);
        });
        $uniqueEquipeDomExt = Rule::unique('matches')->where(function ($query) use ($equipeIdDom, $journeeId) {
            return $query->whereEquipeIdExt($equipeIdDom)->whereJourneeId($journeeId);
        });
        $uniqueEquipeExtDom = Rule::unique('matches')->where(function ($query) use ($equipeIdExt, $journeeId) {
            return $query->whereEquipeIdDom($equipeIdExt)->whereJourneeId($journeeId);
        });
        $uniqueEquipeExtExt = Rule::unique('matches')->where(function ($query) use ($equipeIdExt, $journeeId) {
            return $query->whereEquipeIdExt($equipeIdExt)->whereJourneeId($journeeId);
        });

        if($match){
            $id = $match->id;
            $uniqueEquipeDomDom = $uniqueEquipeDomDom->ignore($id);
            // $uniqueEquipeDomExt = $uniqueEquipeDomExt->ignore($id);
            // $uniqueEquipeExtDom = $uniqueEquipeExtDom->ignore($id);
            $uniqueEquipeExtExt = $uniqueEquipeExtExt->ignore($id);
        }

        // dd(get_class_methods('Illuminate\Validation\Rules\Unique'));
        // dd($uniqueEquipeDomDom);

        $request['acces_bloque'] = $request->has('acces_bloque');
        $rules = [
            'journee_id' => 'required|exists:journees,id',
            'terrain_id' => 'required|exists:terrains,id',
            'date' => 'nullable|date|date_format:Y-m-d',
            'heure' => 'nullable|string|size:5',
            'equipe_id_dom' => ['required','integer','exists:equipes,id',$uniqueEquipeDomDom,$uniqueEquipeDomExt],
            'equipe_id_ext' => ['required','integer','exists:equipes,id',$uniqueEquipeExtDom,$uniqueEquipeExtExt],
            'score_eq_dom' => 'nullable|integer|min:0|required_with:score_eq_ext',
            'score_eq_ext' => 'nullable|integer|min:0|required_with:score_eq_dom',
            'acces_bloque' => 'boolean'
        ];
        $msg = "Cette équipe participe déjà à une rencontre de cette journée.";
        $messages = [
            'equipe_id_dom.unique' => $msg,
            'equipe_id_ext.unique' => $msg
        ];
        return ['rules' => $rules, 'messages' => $messages, 'request' => $request];
    }

    /**
     * Définition de l'affichage d'un objet de la classe
     *
     * @return string
     */
    public function getNomAttribute()
    {
        return $this->equipeDom->nom . ' # ' . $this->equipeExt->nom;
    }

    /**
     * Les informations du match dont ont besoin les views match/resultat et horaire
     *
     * @return void
     */
    public function infos()
    {
        $key = 'match-'.$this->uniqid;
        if(!Config::get('constant.activer_cache'))
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
        $equipeExt = $this->equipeExt;
        $journee = $this->journee;
        $saison = $this->journee->saison;
        $competition = $saison->competition;
        $sport = $competition->sport;
        $commentaires = $this->commentaires->sortByDesc('created_at');
        foreach ($commentaires as $commentaire)
            $commentaire->pseudo = $commentaire->user->pseudo;


        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'equipeDom' => $equipeDom->nom,
            'fanionDom' => $equipeDom->fanion(),
            'equipeExt' => $equipeExt->nom,
            'fanionExt' => $equipeExt->fanion(),
            'score' => $this->score(),
            'dateFormat' => $this->dateFormat(),
            'date' => $this->date,
            'heure' => $this->heure,
            'accesBloque' => $this->acces_bloque,
            'journee' => niemeJournee($journee->numero),
            'competition' => $competition->nom,
            'commentaires' => $commentaires,
            'scoreEqDom' => $this->score_eq_dom,
            'scoreEqExt' => $this->score_eq_ext,
            'lienResultat' => route('competition.match.resultat', ['sport' => strToUrl($sport->nom), 'competition' => strToUrl($competition->nom),'id' => $this->uniqid]),
            'lienHoraire' => route('competition.match.horaire', ['sport' => strToUrl($sport->nom), 'competition' => strToUrl($competition->nom),'id' => $this->uniqid]),
            'lienMatch' => route('competition.match', [
                'id' => $this->uniqid,
                'sport' => strToUrl($sport->nom),
                'competition' => strToUrl($competition->nom),
                'annee' => $saison->annee(),
                'equipeDom' => strToUrl($equipeDom->nom),
                'equipeExt' => strToUrl($equipeExt->nom)
            ])
        ];
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
