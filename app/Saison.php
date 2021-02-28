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
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Saison extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    // protected $fillable = ['annee_debut', 'annee_fin', 'finie', 'annulee', 'nb_journees', 'bareme_id', 'competition_id', 'nb_descentes', 'nb_montees'];

    /**
     * Les règles de validations
     *
     * @param Saison $saison
     * @return array
     */
    public static function rules(Saison $saison = null)
    {
        $competitionId = request()->input('competition_id');

        $unique = Rule::unique('saisons')->where(function ($query) use ($competitionId) {
            return $query->whereCompetitionId($competitionId);
        })->ignore($saison);

        $rules = [
            'annee_debut' => ['required','integer','min:1970',$unique],
            'annee_fin' => 'required|integer|min:1970|gte:annee_debut',
            'nb_journees' => 'nullable|integer|min:1|max:100',
            'nb_descentes' => 'nullable|integer|min:0|max:10',
            'nb_montees' => 'nullable|integer|min:0|max:10',
            'bareme_id' => 'nullable|exists:baremes,id',
            'competition_id' => 'required|exists:competitions,id',
            'equipe_id' => 'nullable|exists:equipes,id',
            'second' => 'nullable|exists:equipes,id',
            'finie' => 'nullable|boolean',
            'annulee' => 'nullable|boolean',
        ];
        $messages = ['annee_debut.unique' => "Le competition possède déjà une saison qui débute la même année."];
        return ['rules' => $rules, 'messages' => $messages];
    }

    /**
     * La fonction renvoie le classement s'il est déjà en cache. Sinon, elle fait appelle à la fonction generateRanking
     *
     * @return \Illuminate\Support\Collection
     */
    public function infos()
    {
        $key = 'saisons-'.$this->id;
        if (Cache::has($key))
            return Cache::get($key);

        return Cache::rememberForever($key, function (){
            Log::info('Rechargement du cache : saisons-' . $this->id);

            $type = $this->competition->type;
            $collect = collect();
            // On associe d'abord tous les attributs
            foreach ($this->attributes as $key => $value)
                $collect->$key = $value;
                
            if($type == 1)
                if($this->competition->sport->slug == 'volleyball')
                    $collect->classement = $this->classementVolley();
                else
                    $collect->classement = $this->classement();

            $collect->derniere_journee_id = $this->journees->where('date', '<', date('Y-m-d'))->sortByDesc('date')->first()->id ?? '';
            $collect->prochaine_journee_id = $this->journees->where('date', '>=', date('Y-m-d'))->sortBy('date')->first()->id ?? '';
            return $collect;
        });
    }

    /**
     * Génération du classement de la saison
     *
     * @param int $sportId
     * @return \Illuminate\Support\Collection
     */
    public function classement()
    {
        if(! $this->bareme_id)
            return [];

        $bareme = index('baremes')[$this->bareme_id];
        $sport = index('sports')[$bareme->sport_id];
        $matches = [];
        foreach($this->equipes as $equipe){
            $matchesAller = $this->matches->where('equipe_id_dom', $equipe->id);
            $matchesRetour = $this->matches->where('equipe_id_ext', $equipe->id);
            $matches[$equipe->id] = $matchesAller->merge($matchesRetour);
        }

        $classement = [];
        foreach ($matches as $equipeId => $matchesEquipe) {
            $equipe = index('equipes')[$equipeId];
            $sport = index('sports')[$sport->id];
            $hrefEquipe = route('equipe.index', ['sport' => $sport->slug, 'equipe' => $equipe->slug_complet]);
            $nomEquipe = $equipe->nom;
            $fanionEquipe = fanion($equipe->uniqid);

            $classement[$equipeId]['id'] = $equipeId;
            $classement[$equipeId]['nom'] = $nomEquipe;
            $classement[$equipeId]['hrefEquipe'] = $hrefEquipe;
            $classement[$equipeId]['fanion'] = $fanionEquipe;

            $classement[$equipeId]['points'] = 0;
            $classement[$equipeId]['joues'] = 0;
            $classement[$equipeId]['victoire'] = 0;
            $classement[$equipeId]['marques'] = 0;
            $classement[$equipeId]['encaisses'] = 0;

            if($sport->slug != 'basketball')
                $classement[$equipeId]['nul'] = 0;

            $classement[$equipeId]['defaite'] = 0;
            $classement[$equipeId]['forfaits'] = 0;

            foreach ($matchesEquipe as $match) {
                $resultat = $match->resultat($equipeId);
                if($resultat){
                    $classement[$equipeId]['joues']++;

                    $marques = $resultat['marques'];
                    $encaisses = $resultat['encaisses'];
                    $typeResultat = $resultat['type']; // Victoire/Nul ou Défaite
                    
                    // On regarde si l'équipe est forfait pour le match ou non
                    $infosSup = $match->infos();
                    $forfait = false;
                    if($equipeId == $match->equipe_id_dom && isset($infosSup->forfait_eq_dom))
                        $forfait = true;
                    if($equipeId == $match->equipe_id_ext && isset($infosSup->forfait_eq_ext))
                        $forfait = true;

                    $classement[$equipeId][$typeResultat]++;
                    $classement[$equipeId]['points'] += $bareme->$typeResultat;

                    $classement[$equipeId]['marques'] += $marques;
                    $classement[$equipeId]['encaisses'] += $encaisses;

                    // Si l'équipe est forfait, alors on applique le barème des matches forfaits
                    if($forfait && $bareme->forfait){
                        $classement[$equipeId]['forfaits']++;
                        $classement[$equipeId]['points'] -= $bareme->forfait;
                    }
                }
            }

            $classement[$equipeId]['diff'] = $classement[$equipeId]['marques'] - $classement[$equipeId]['encaisses'];
        };

        // Tri du classement : Points/Diff/Marques
        usort($classement , 'compare');

        return collect($classement);
    }

    /**
     * Génération du classement de la saison de volleyball
     *
     * @param int $sportId
     * @return \Illuminate\Support\Collection
     */
    public function classementVolley()
    {
        if(! $this->bareme_id)
            return [];

        $bareme = index('baremes')[$this->bareme_id];
        $proprietes = config('listes.proprietes-baremes');
        $baremeInfos = index('bareme_infos')->where('bareme_id', $bareme->id);

        foreach ($baremeInfos as $info)
            $infosSup[$proprietes[$info->propriete_id][0]] = $info->valeur;

        $bareme = collect($infosSup)->merge(['forfait' => $bareme->forfait, 'sport_id' => $bareme->sport_id]);

        $sport = index('sports')[$bareme['sport_id']];
        $matches = [];
        foreach($this->equipes as $equipe){
            $matchesAller = $this->matches->where('equipe_id_dom', $equipe->id);
            $matchesRetour = $this->matches->where('equipe_id_ext', $equipe->id);
            $matches[$equipe->id] = $matchesAller->merge($matchesRetour);
        }

        $classement = [];

        foreach ($matches as $equipeId => $matchesEquipe) {
            $equipe = index('equipes')[$equipeId];
            $sport = index('sports')[$sport->id];
            $hrefEquipe = route('equipe.index', ['sport' => $sport->slug, 'equipe' => $equipe->slug_complet]);
            $nomEquipe = $equipe->nom;
            $fanionEquipe = fanion($equipe->uniqid);

            $classement[$equipeId]['id'] = $equipeId;
            $classement[$equipeId]['nom'] = $nomEquipe;
            $classement[$equipeId]['hrefEquipe'] = $hrefEquipe;
            $classement[$equipeId]['fanion'] = $fanionEquipe;

            $classement[$equipeId]['points'] = 0;
            $classement[$equipeId]['joues'] = 0;
            $classement[$equipeId]['victoire'] = 0;
            $classement[$equipeId]['victoire_3_0'] = 0;
            $classement[$equipeId]['victoire_3_1'] = 0;
            $classement[$equipeId]['victoire_3_2'] = 0;
            $classement[$equipeId]['marques'] = 0;
            $classement[$equipeId]['encaisses'] = 0;

            $classement[$equipeId]['defaite'] = 0;
            $classement[$equipeId]['defaite_0_3'] = 0;
            $classement[$equipeId]['defaite_1_3'] = 0;
            $classement[$equipeId]['defaite_2_3'] = 0;
            $classement[$equipeId]['forfaits'] = 0;            

            foreach ($matchesEquipe as $match) {
                $resultat = $match->resultat($equipeId, 'volleyball');
                if($resultat){
                    $classement[$equipeId]['joues']++;

                    $marques = $resultat['marques'];
                    $encaisses = $resultat['encaisses'];
                    $typeResultat = $resultat['type']; // Victoire/Nul ou Défaite
                    $typeResultatAvecSets = $resultat['type_avec_sets'];
                    
                    // On regarde si l'équipe est forfait pour le match ou non
                    $infosSup = $match->infos();
                    $forfait = false;
                    if($equipeId == $match->equipe_id_dom && isset($infosSup->forfait_eq_dom))
                        $forfait = true;
                    if($equipeId == $match->equipe_id_ext && isset($infosSup->forfait_eq_ext))
                        $forfait = true;

                    $classement[$equipeId][$typeResultat]++;
                    $classement[$equipeId][$typeResultatAvecSets]++;
                    $classement[$equipeId]['points'] += $bareme[$typeResultatAvecSets];

                    $classement[$equipeId]['marques'] += $marques;
                    $classement[$equipeId]['encaisses'] += $encaisses;

                    // Si l'équipe est forfait, alors on applique le barème des matches forfaits
                    if($forfait && $bareme['forfait']){
                        $classement[$equipeId]['forfaits']++;
                        $classement[$equipeId]['points'] -= $bareme['forfait'];
                    }
                }
            }

            $classement[$equipeId]['coefficient'] = $classement[$equipeId]['encaisses'] ? round($classement[$equipeId]['marques'] / $classement[$equipeId]['encaisses'], 2) : 'MAX';
        };

        // Tri du classement : Points/Diff/Marques
        usort($classement , 'compareVolley');

        return collect($classement);
    }

    /**
     * Définition de l'attribut nom pour une saison
     *
     * @return string
     */
    public function getNomAttribute()
    {
        return $this->annee('/');
    }

    /**
     * Renvoie l'année en fonction du séparateur fourni.
     * Si par exemple : annee_debut == annee_fin => 2020, sinon renvoie 2020-2021
     *
     * @return string
     */
    public function annee($separateur = '-')
    {
        return ($this->annee_debut == $this->annee_fin) ? $this->annee_debut : $this->annee_debut. $separateur .$this->annee_fin;
    }

    /**
     * Les journées de la saison
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function journees()
    {
        return $this->hasMany('App\Journee');
    }

    /**
     * Les équipes participantes à la saison
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function equipes()
    {
        return $this->belongsToMany('App\Equipe')
                    ->using('App\EquipeSaison');
    }

    /**
     * Tous les matches de la saison
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function matches()
    {
        return $this->hasManyThrough('App\Match', 'App\Journee');
    }

    /**
     * Le competition lié à la saison
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function competition()
    {
        return $this->belongsTo('App\Competition');
    }

    /**
     * Le barème lié à la saison
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bareme()
    {
        return $this->belongsTo('App\Bareme');
    }

    /**
     * Le vainqueur
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipe()
    {
        return $this->belongsTo('App\Equipe');
    }
}
