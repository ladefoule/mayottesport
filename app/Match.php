<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
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
        $uniqueEquipeDom = Rule::unique('matches')->where(function ($query) {
            return $query->whereJourneeId(request()['journee_id'])
                        ->whereEquipeIdDom(request()['equipe_id_dom']);
        })->ignore($match);

        $uniqueEquipeExt = Rule::unique('matches')->where(function ($query) {
            return $query->whereEquipeIdExt(request()['equipe_id_ext'])->whereJourneeId(request()['journee_id']);
        })->ignore($match);

        request()->acces_bloque = request()->has('acces_bloque');
        $rules = [
            'journee_id' => 'required|exists:journees,id',
            'terrain_id' => 'nullable|exists:terrains,id',
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
    public function getNomAttribute()
    {
        return index('equipes')[$this->equipe_id_dom]->nom . ' # ' . index('equipes')[$this->equipe_id_ext]->nom;
    }

    /**
     * Définition de l'affichage dans le CRUD (back-office)
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        return $this->uniqid . ' - ' . index('equipes')[$this->equipe_id_dom]->nom . ' # ' . index('equipes')[$this->equipe_id_ext]->nom;
    }

    /**
     * Les informations du match dont ont besoin les views match/resultat et horaire
     *
     * @return \Illuminate\Support\Collection
     */
    public function infos()
    {
        $key = 'match-'.$this->uniqid;
        if(! Config::get('constant.activer_cache'))
            Cache::forget($key);

        if (Cache::has($key))
            return Cache::get($key);

        return Cache::rememberForever($key, function (){
            Log::info('Rechargement du cache : match-' . $this->uniqid);

            $equipeDom = index('equipes')[$this->equipe_id_dom];
            $equipeDomNomKebab = \Str::slug($equipeDom->nom);
            $equipeExt = index('equipes')[$this->equipe_id_ext];
            $equipeExtNomKebab = \Str::slug($equipeExt->nom);
            // $journee = $this->journee;
            $journee = index('journees')[$this->journee_id];
            $saison = index('saisons')[$journee->saison_id];
            // $saison = Saison::findOrFail($saison->id); // On en a besoin pour pouvoir utiliser la méthode annee() de la classe Saison
            $annee = ($saison->annee_debut == $saison->annee_fin) ? $saison->annee_debut : $saison->annee_debut. '/' .$saison->annee_fin;
            $competition = index('competitions')[$saison->competition_id];
            $competitionNomKebab = \Str::slug($competition->nom);
            $sport = index('sports')[$competition->sport_id];
            $sportNomKebab = \Str::slug($sport->nom);

            $collect = [
                'id' => $this->id,
                'equipe_id_dom' => $this->equipe_id_dom,
                'equipe_id_ext' => $this->equipe_id_ext,
                'equipe_dom' => $equipeDom,
                'journee_id' => $this->journee_id,
                'equipe_dom_nom' => $equipeDom->nom,
                'equipe_dom_nom_kebab' => \Str::slug($equipeDom->nom),
                'href_equipe_dom' => route('equipe.index', ['sport' => $sportNomKebab, 'equipe' => $equipeDomNomKebab, 'uniqid' => $equipeDom->uniqid]),
                'fanion_equipe_dom' => fanion($equipeDom->id),
                'equipe_ext' => $equipeExt,
                'equipe_ext_nom' => $equipeExt->nom,
                'equipe_ext_nom_kebab' => \Str::slug($equipeExt->nom),
                'href_equipe_ext' => route('equipe.index', ['sport' => $sportNomKebab, 'equipe' => $equipeExtNomKebab, 'uniqid' => $equipeExt->uniqid]),
                'fanion_equipe_ext' => fanion($equipeExt->id),
                'url' => route('competition.match', [
                    'sport' => $sportNomKebab,
                    'annee' => str_replace('/', '-', $annee),
                    'competition' => $competitionNomKebab,
                    'equipeDom' => $equipeDomNomKebab,
                    'equipeExt' => $equipeExtNomKebab,
                    'uniqid' => $this->uniqid
                ]),
                'score' => $this->score(),
                'date_format' => $this->dateFormat(),
                'date' => $this->date,
                'heure' => $this->heure,
                'title' => "Match " . $equipeDom->nom . ' vs ' . $equipeExt->nom . ' - ' . $sport->nom . ' - ' . $competition->nom . ' ' . $annee,
                'acces_bloque' => $this->acces_bloque,
                'journee' => niemeJournee($journee->numero),
                'competition' => $competition->nom,
                'score_eq_dom' => $this->score_eq_dom,
                'score_eq_ext' => $this->score_eq_ext,
                'resultat_eq_dom' => $this->resultat($this->equipe_id_dom),
                'resultat_eq_ext' => $this->resultat($this->equipe_id_ext),
                'href_resultat' => route('competition.match.resultat', ['sport' => $sportNomKebab, 'competition' => $competitionNomKebab,'uniqid' => $this->uniqid]),
                'href_horaire' => route('competition.match.horaire', ['sport' => $sportNomKebab, 'competition' => $competitionNomKebab,'uniqid' => $this->uniqid]),
                'href_match' => route('competition.match', [
                    'uniqid' => $this->uniqid,
                    'sport' => $sportNomKebab,
                    'competition' => $competitionNomKebab,
                    'annee' => str_replace('/', '-', $annee),
                    'equipeDom' => $equipeDomNomKebab,
                    'equipeExt' => $equipeExtNomKebab
                ])
            ];

            // $collect['render_eq_dom'] = $this->matchRender($collect, $equipeDom);
            // $collect['render_eq_ext'] = $this->matchRender($collect, $equipeExt);
            return collect($collect);
        });
    }

    /**
     * Undocumented function
     *
     * @param Equipe $equipe
     * @return void
     */
    public function matchRender($infos, $equipe)
    {
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
    // public function url()
    // {
    //     $equipeDom = index('equipes')[$this->equipe_id_dom];
    //     $equipeExt = index('equipes')[$this->equipe_id_ext];
    //     $equipeDomKebabCase = \Str::slug($equipeDom->nom);
    //     $equipeExtKebabCase = \Str::slug($equipeExt->nom);
    //     $journee = index('journees')[$this->journee_id];
    //     $saison = index('saisons')[$journee->saison_id];
    //     $annee = ($saison->annee_debut == $saison->annee_fin) ? $saison->annee_debut : $saison->annee_debut. '-' .$saison->annee_fin;
    //     $competition = index('competitions')[$saison->competition_id];
    //     $sport = index('sports')[$competition->sport_id];
    //     $sportKebabCase = \Str::slug($sport->nom);
    //     $competitionKebabCase = \Str::slug($competition->nom);

    //     return config('app.url') . "/$sportKebabCase/$competitionKebabCase/$annee/match-" . $equipeDomKebabCase ."_". $equipeExtKebabCase ."_" . $this->uniqid .".html";
    // }

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
