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
    protected $fillable = ['annee_debut', 'annee_fin', 'finie', 'nb_journees', 'bareme_id', 'competition_id', 'nb_descentes', 'nb_montees'];

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
            'finie' => 'nullable|boolean'
        ];
        $messages = ['annee_debut.unique' => "Le competition possède déjà une saison qui débute la même année."];
        return ['rules' => $rules, 'messages' => $messages];
    }

    /**
     * La dernière journée jouée
     *
     * @return Journee
     */
    public function derniereJourneeId()
    {
        return index('journees')->where('saison_id', $this->id)->where('date', '<', date('Y-m-d'))->sortByDesc('date')->first()->id ?? '';
    }

    /**
     * La prochaine journée à jouer
     *
     * @return Journee
     */
    public function prochaineJourneeId()
    {
        return index('journees')->where('saison_id', $this->id)->where('date', '>=', date('Y-m-d'))->sortBy('date')->first()->id ?? '';
    }

    /**
     * La fonction renvoie le classement s'il est déjà en cache. Sinon, elle fait appelle à la fonction generateRanking
     *
     * @return \Illuminate\Support\Collection
     */
    public function infos()
    {
        $key = 'saison-'.$this->id;
        if (Cache::has($key))
            return Cache::get($key);

        return Cache::rememberForever($key, function (){
            Log::info('Rechargement du cache : saison-' . $this->id);

            $type = $this->competition->type;
            $collect = collect();
            if($type == 1)
                if($this->competition->sport->slug == 'volleyball')
                    $collect['classement'] = $this->classementVolley();
                else
                    $collect['classement'] = $this->classement();

            $collect['derniere_journee_id'] = $this->journees->where('date', '<', date('Y-m-d'))->sortByDesc('date')->first()->id ?? '';
            $collect['prochaine_journee_id'] = $this->journees->where('date', '>=', date('Y-m-d'))->sortBy('date')->first()->id ?? '';
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
        $idBasketball = Sport::firstWhere('nom', 'like', 'basketball')->id ?? 0;
        $idBasketball = index('sports')->filter(function ($value, $key) {
            return strcasecmp($value->nom, 'basketball') == 0; // strcasecmp renvoie 0 si les deux chaines sont semblables, sans respecter la casse
        })->first()->id ?? 0;
        $idVolleyball = Sport::firstWhere('nom', 'like', 'volleyball')->id ?? 0;
        foreach ($matches as $equipeId => $matchesEquipe) {
            $equipe = index('equipes')[$equipeId];
            $sport = index('sports')[$sport->id];
            $hrefEquipe = route('equipe.index', ['sport' => Str::slug($sport->nom), 'equipe' => Str::slug($equipe->nom), 'uniqid' => $equipe->uniqid]);
            $nomEquipe = $equipe->nom;
            $fanionEquipe = fanion($equipe->id);

            $classement[$equipeId]['id'] = $equipeId;
            $classement[$equipeId]['nom'] = $nomEquipe;
            $classement[$equipeId]['hrefEquipe'] = $hrefEquipe;
            $classement[$equipeId]['fanion'] = $fanionEquipe;

            $classement[$equipeId]['points'] = 0;
            $classement[$equipeId]['joues'] = 0;
            $classement[$equipeId]['victoire'] = 0;
            $classement[$equipeId]['marques'] = 0;
            $classement[$equipeId]['encaisses'] = 0;

            if($sport->id != $idBasketball && $sport->id != $idVolleyball)
                $classement[$equipeId]['nul'] = 0;

            $classement[$equipeId]['defaite'] = 0;
            foreach ($matchesEquipe as $match) {
                if($match->resultat($equipeId) != false){
                    $classement[$equipeId]['joues']++;

                    $resultat = $match->resultat($equipeId);
                    $marques = $resultat['marques'];
                    $encaisses = $resultat['encaisses'];
                    $resultat = $resultat['resultat'];

                    $classement[$equipeId][$resultat]++;
                    $classement[$equipeId]['points'] += $bareme->$resultat;

                    $classement[$equipeId]['marques'] += $marques;
                    $classement[$equipeId]['encaisses'] += $encaisses;
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
// dd($bareme);
        $sport = index('sports')[$bareme['sport_id']];
        $matches = [];
        foreach($this->equipes as $equipe){
            $matchesAller = $this->matches->where('equipe_id_dom', $equipe->id);
            $matchesRetour = $this->matches->where('equipe_id_ext', $equipe->id);
            $matches[$equipe->id] = $matchesAller->merge($matchesRetour);
        }

        $classement = [];
        $idBasketball = Sport::firstWhere('nom', 'like', 'basketball')->id ?? 0;
        $idBasketball = index('sports')->filter(function ($value, $key) {
            return strcasecmp($value->nom, 'basketball') == 0; // strcasecmp renvoie 0 si les deux chaines sont semblables, sans respecter la casse
        })->first()->id ?? 0;
        $idVolleyball = Sport::firstWhere('nom', 'like', 'volleyball')->id ?? 0;
        foreach ($matches as $equipeId => $matchesEquipe) {
            $equipe = index('equipes')[$equipeId];
            $sport = index('sports')[$sport->id];
            $hrefEquipe = route('equipe.index', ['sport' => Str::slug($sport->nom), 'equipe' => Str::slug($equipe->nom), 'uniqid' => $equipe->uniqid]);
            $nomEquipe = $equipe->nom;
            $fanionEquipe = fanion($equipe->id);

            $classement[$equipeId]['id'] = $equipeId;
            $classement[$equipeId]['nom'] = $nomEquipe;
            $classement[$equipeId]['hrefEquipe'] = $hrefEquipe;
            $classement[$equipeId]['fanion'] = $fanionEquipe;

            $classement[$equipeId]['points'] = 0;
            $classement[$equipeId]['joues'] = 0;
            $classement[$equipeId]['victoire'] = 0;
            $classement[$equipeId]['marques'] = 0;
            $classement[$equipeId]['encaisses'] = 0;

            if($sport->id != $idBasketball && $sport->id != $idVolleyball)
                $classement[$equipeId]['nul'] = 0;

            $classement[$equipeId]['defaite'] = 0;
            foreach ($matchesEquipe as $match) {
                if($match->resultat($equipeId) != false){
                    $classement[$equipeId]['joues']++;

                    $resultat = $match->resultat($equipeId);
                    $marques = $resultat['marques'];
                    $encaisses = $resultat['encaisses'];
                    $resultat = $resultat['resultat'];

                    $classement[$equipeId][$resultat]++;
                    $classement[$equipeId]['points'] += $bareme->$resultat;

                    $classement[$equipeId]['marques'] += $marques;
                    $classement[$equipeId]['encaisses'] += $encaisses;
                }
            }

            $classement[$equipeId]['diff'] = $classement[$equipeId]['marques'] - $classement[$equipeId]['encaisses'];
        };

        // Tri du classement : Points/Diff/Marques
        usort($classement , 'compare');

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
