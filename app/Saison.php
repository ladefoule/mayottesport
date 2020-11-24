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
        $anneeDebut = request()->annee_debut ?? '';
        $competitionId = request()->competition_id ?? '';
        $unique = Rule::unique('saisons')->where(function ($query) use ($anneeDebut, $competitionId) {
            return $query->whereAnneeDebut($anneeDebut)->whereCompetitionId($competitionId);
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
    public function derniereJournee()
    {
        return $this->journees->where('date', '<', date('Y-m-d'))->sortByDesc('date')->first();
    }

    /**
     * La prochaine journée à jouer
     *
     * @return Journee
     */
    public function prochaineJournee()
    {
        return $this->journees->where('date', '>=', date('Y-m-d'))->sortBy('date')->first();
    }

    public function classementSimpleRender(bool $complet = false)
    {
        $competition = index('competitions')[$this->competition_id];
        $sport = index('sports')[$competition['sport_id']];
        $hrefClassementComplet = route('competition.classement', ['competition' => $competition['nom'], 'sport' => $sport['nom']]);
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
    public function classement()
    {
        $key = 'classement-'.$this->id;
        if(! Config::get('constant.activer_cache'))
            Cache::forget($key);

        if (Cache::has($key))
            return Cache::get($key);

        return Cache::rememberForever($key, function (){
            return $this->genererClassement();
        });
    }

    /**
     * Génération du classement de la saison
     *
     * @param int $sportId
     * @return \Illuminate\Support\Collection
     */
    private function genererClassement()
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
        $idVolleyball = Sport::firstWhere('nom', 'like', 'volleyball')->id ?? 0;
        foreach ($matches as $equipeId => $matchesEquipe) {
            $equipe = Equipe::findOrFail($equipeId);
            $hrefEquipe = route('equipe.index', ['sport' => strToUrl($sport->nom), 'equipe' => strToUrl($equipe->nom), 'id' => $equipe->uniqid]);
            $nomEquipe = $equipe->nom;
            $fanionEquipe = fanion($equipe->id);
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

        $classement = new Collection($classement);
        return $classement->sortByDesc(function ($ligne, $key) {
            // Tri des classements par points/diff/buts marques/matches joués
            // \Log::info($ligne['points'] . $ligne['diff'] . $ligne['marques'] . $ligne['joues']);
            return $ligne['points'] . $ligne['diff'] . $ligne['marques'] . $ligne['joues'];
        });
    }

    /**
     * Définition de l'attribut nom pour une saison
     *
     * @return string
     */
    public function getNomAttribute()
    {
        return /* index('competitions')[$this->competition_id]->nom . ' ' . */ $this->annee('/');
    }

    /**
     * Définition de l'affichage dans le CRUD (back-office)
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        return index('competitions')[$this->competition_id]['crud_name'] . ' ' . $this->annee('/');
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
