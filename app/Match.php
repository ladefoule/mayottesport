<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

use Illuminate\Support\Str;
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
                            'journee_id', 'terrain_id', 'equipe_id_dom', 'equipe_id_ext', 'uniqid', 'user_id'];

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
    public static function rules(Match $match = null)
    {
        $unique = Rule::unique('matches')->ignore($match);
        $uniqueEqJourneeDom = Rule::unique('matches', 'journee_id', 'equipe_id_dom')->ignore($match);
        $uniqueEqJourneeExt = Rule::unique('matches', 'journee_id', 'equipe_id_ext')->ignore($match);

        // request()['acces_bloque'] = request()->has('acces_bloque');
        // request()['forfait_eq_dom'] = request()->has('forfait_eq_dom');
        // request()['forfait_eq_ext'] = request()->has('forfait_eq_ext');
        // request()['penalite_eq_dom'] = request()->has('penalite_eq_dom');
        // request()['penalite_eq_ext'] = request()->has('penalite_eq_ext');
        // request()['avec_tirs_au_but'] = request()->has('avec_tirs_au_but');
        $rules = [
            'journee_id' => 'required|exists:journees,id',
            'terrain_id' => 'nullable|exists:terrains,id',
            'user_id' => 'nullable|exists:users,id',
            'date' => 'nullable|date|date_format:Y-m-d',
            'heure' => 'nullable|string|size:5',
            'equipe_id_dom' => ['required','integer','exists:equipes,id',$uniqueEqJourneeDom,],
            'equipe_id_ext' => ['required','integer','exists:equipes,id','different:equipe_id_dom',$uniqueEqJourneeExt],
            'uniqid' => ['required','string','size:13',$unique],
            'score_eq_dom' => 'nullable|integer|min:0|required_with:score_eq_ext',
            'score_eq_ext' => 'nullable|integer|min:0|required_with:score_eq_dom',
            'acces_bloque' => 'boolean',
            'forfait_eq_dom' => 'nullable|boolean',
            'forfait_eq_ext' => 'nullable|boolean',
            'penalite_eq_dom' => 'nullable|boolean',
            'penalite_eq_ext' => 'nullable|boolean',
            'avec_tirs_au_but' => 'nullable|boolean',
            'tab_eq_dom' => ['required_with:tab_eq_ext','exclude_if:avec_tirs_au_but,false','required_if:avec_tirs_au_but,true','integer','min:0','max:20'],
            'tab_eq_ext' => ['required_with:tab_eq_dom','exclude_if:avec_tirs_au_but,false','required_if:avec_tirs_au_but,true','integer','min:0','max:20'],
        ];

        $messages = [
            'equipe_id_dom.unique' => "Cette équipe participe déjà à une rencontre de cette journée.",
            'equipe_id_ext.unique' => "Cette équipe participe déjà à une rencontre de cette journée.",
            'tab_eq_dom.required_with' => "Merci de renseigner le score aux tirs au but.",
            'tab_eq_ext.required_with' => "Merci de renseigner le score aux tirs au but.",
            'score_eq_dom.required_with' => "Merci de renseigner le score.",
            'score_eq_ext.required_with' => "Merci de renseigner le score.",
            'required_if' => "Merci de renseigner le score aux tirs au but.",
            'different' => "Les deux équipes doivent être différentes.",

        ];
        return ['rules' => $rules, 'messages' => $messages];
    }

    /**
     * Les informations du match dont ont besoin les views match/resultat et horaire
     *
     * @return \Illuminate\Support\Collection
     */
    public function infos()
    {
        $key = 'match-'.$this->uniqid;
        if (Cache::has($key))
            return Cache::get($key);

        return Cache::rememberForever($key, function (){
            Log::info('Rechargement du cache : match-' . $this->uniqid);

            $infos = collect();
            // On associe d'abord tous les attributs
            foreach ($this->attributes as $key => $value)
                $infos->$key = $value;

            // Les infos supplémentaires
            $equipeDom = index('equipes')[$this->equipe_id_dom];
            $equipeDomNomSlug = Str::slug($equipeDom->nom);
            $equipeExt = index('equipes')[$this->equipe_id_ext];
            $equipeExtNomSlug = Str::slug($equipeExt->nom);
            $journee = index('journees')[$this->journee_id];
            $saison = index('saisons')[$journee->saison_id];
            $annee = ($saison->annee_debut == $saison->annee_fin) ? $saison->annee_debut : $saison->annee_debut. '/' .$saison->annee_fin;
            $competition = index('competitions')[$saison->competition_id];
            $competitionNomSlug = Str::slug($competition->nom);
            $sport = index('sports')[$competition->sport_id];
            $sportNomSlug = Str::slug($sport->nom);

            $infosPlus = [
                'id' => $this->id,
                'equipe_dom' => $equipeDom,
                'href_equipe_dom' => route('equipe.index', ['sport' => $sportNomSlug, 'equipe' => Str::slug($equipeDom->nom_complet)]),
                'fanion_equipe_dom' => fanion($equipeDom->id),
                'equipe_ext' => $equipeExt,
                'href_equipe_ext' => route('equipe.index', ['sport' => $sportNomSlug, 'equipe' => Str::slug($equipeExt->nom_complet)]),
                'fanion_equipe_ext' => fanion($equipeExt->id),
                'url' => route('competition.match', [
                    'sport' => $sportNomSlug,
                    'annee' => str_replace('/', '-', $annee),
                    'competition' => $competitionNomSlug,
                    'equipeDom' => $equipeDomNomSlug,
                    'equipeExt' => $equipeExtNomSlug,
                    'uniqid' => $this->uniqid
                ]),
                'score' => $this->score(),
                'date_format' => $this->dateFormat(),
                'title' => "Match " . $equipeDom->nom . ' vs ' . $equipeExt->nom . ' - ' . $sport->nom . ' - ' . $competition->nom . ' ' . $annee,
                'acces_bloque' => $this->acces_bloque,
                'journee' => niemeJournee($journee->numero),
                'competition' => $competition->nom,
                'resultat_eq_dom' => $this->resultat($this->equipe_id_dom),
                'resultat_eq_ext' => $this->resultat($this->equipe_id_ext),
                'href_resultat' => route('competition.match.resultat', ['sport' => $sportNomSlug, 'competition' => $competitionNomSlug,'uniqid' => $this->uniqid]),
                'href_horaire' => route('competition.match.horaire', ['sport' => $sportNomSlug, 'competition' => $competitionNomSlug,'uniqid' => $this->uniqid]),
                'href_match' => route('competition.match', [
                    'uniqid' => $this->uniqid,
                    'sport' => $sportNomSlug,
                    'competition' => $competitionNomSlug,
                    'annee' => str_replace('/', '-', $annee),
                    'equipeDom' => $equipeDomNomSlug,
                    'equipeExt' => $equipeExtNomSlug
                ])
            ];

            // On insère les infos supplémentaires
            foreach ($infosPlus as $key => $value)
                $infos->$key = $value;

            // On ajoute les infos supplémentaires du match : forfaits, pénalités, tab, etc...
            $infosSup = $this->matchInfos;
            $correspondances = config('listes.proprietes-matches');
            foreach ($infosSup as $info){
                $prop = $correspondances[$info->propriete_id][0];
                $infos->$prop = $info->valeur;
                // $infos->$correspondances[$info->propriete_id][0] = $info->valeur; // Génère une erreur
            }
            return $infos;
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
     * Définition de l'attribut nom du match
     *
     * @return string
     */
    public function getNomAttribute()
    {
        return $this->uniqid /* . ' - ' . index('equipes')[$this->equipe_id_dom]->nom . ' # ' . index('equipes')[$this->equipe_id_ext]->nom */;
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

    /**
     * Le dernier utilisateur ayant modifié le match.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
