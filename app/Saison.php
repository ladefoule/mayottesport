<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

class Saison extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['annee_debut', 'annee_fin', 'finie', 'nb_journees', 'bareme_id', 'competition_id'];

    /**
     * Les règles de validations
     *
     * @param Saison $saison
     * @return array
     */
    public static function rules(Saison $saison = null)
    {
        $unique = Rule::unique('saisons')->where(function ($query) {
            return $query->whereAnneeDebut(request()['annee_debut'])->whereCompetitionId(request()['competition_id']);
        })->ignore($saison);

        request()->finie = request()->has('finie');
        $rules = [
            'annee_debut' => ['required','integer','min:2000','max:3000',$unique],
            'annee_fin' => 'required|integer|min:2000|max:3000|gte:annee_debut',
            'nb_journees' => 'required|integer|min:1|max:100',
            'bareme_id' => 'nullable|exists:baremes,id',
            'competition_id' => 'required|exists:competitions,id',
            'finie' => 'boolean'
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

    public function classementSimpleRender(bool $complet = false)
    {
        $competition = index('competitions')[$this->competition_id];
        $sport = index('sports')[$competition->sport_id];
        $hrefClassementComplet = route('competition.classement', ['competition' => strToUrl($competition->nom), 'sport' => strToUrl($sport->nom)]);
        $classement = $this->classement();
        return view('competition.classement-simple', [
            'classement' => $classement,
            'hrefClassementComplet' => $hrefClassementComplet,
            'complet' => $complet
        ])->render();
    }

    /**
     * La fonction renvoie le classement s'il est déjà en cache. Sinon, elle fait appelle à la fonction generateRanking
     *
     * @return \Illuminate\Support\Collection
     */
    public function infos()
    {
        $key = 'saison-'.$this->id;
        if(! Config::get('constant.activer_cache'))
            Cache::forget($key);

        if (Cache::has($key))
            return Cache::get($key);

        return Cache::rememberForever($key, function (){
            $type = $this->competition->type;
            $collect = collect();
            if($type == 1){
                $collect['classement'] = $this->classement();
                $collect['classement_simple_render'] = $this->classementSimpleRender();
            }

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
        $bareme = $this->bareme;
        $sport = $bareme->sport;
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
            $hrefEquipe = route('equipe.index', ['sport' => strToUrl($sport->nom), 'equipe' => strToUrl($equipe->nom), 'uniqid' => $equipe->uniqid]);
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
        usort($classement , 'cmp');

        return $classement;
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
     * Définition de l'affichage dans le CRUD
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        return index('competitions')[$this->competition_id]->crud_name . ' ' . $this->annee('/');
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
}
