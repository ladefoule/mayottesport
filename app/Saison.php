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
     * @param Request $request
     * @param Saison $saison
     * @return array
     */
    public static function rules(Request $request, Saison $saison = null)
    {
        $anneeDebut = $request['annee_debut'] ?? '';
        $competitionId = $request['competition_id'] ?? '';
        $unique = Rule::unique('saisons')->where(function ($query) use ($anneeDebut, $competitionId) {
            return $query->whereAnneeDebut($anneeDebut)->whereCompetitionId($competitionId);
        });

        if($saison){
            $id = $saison->id;
            $unique = $unique->ignore($id);
        }

        $request['finie'] = $request->has('finie');
        $rules = [
            'annee_debut' => ['required','integer','min:2000','max:3000',$unique],
            'annee_fin' => 'required|integer|min:2000|max:3000|gte:annee_debut',
            'nb_journees' => 'required|integer|min:1|max:100',
            'bareme_id' => 'nullable|exists:baremes,id',
            'competition_id' => 'required|exists:competitions,id',
            'finie' => 'boolean'
        ];
        $messages = ['annee_debut.unique' => "Le competition possède déjà une saison qui débute la même année."];
        return ['rules' => $rules, 'messages' => $messages, 'request' => $request];
    }

    public function derniereJournee()
    {
        return $this->journees->where('date', '<', date('Y-m-d'))->sortByDesc('date')->first();
    }

    public function prochaineJournee()
    {
        return $this->journees->where('date', '>=', date('Y-m-d'))->sortBy('date')->first();
    }

    /**
     * La fonction renvoie le classement s'il est déjà en cache. Sinon, elle fait appelle à la fonction genererClassement
     *
     * @return array
     */
    public function classement()
    {
        $key = 'classement-'.$this->id;
        if(!Config::get('constant.activer_cache'))
            Cache::forget($key);

        if (Cache::has($key))
            return Cache::get($key);

        return Cache::rememberForever($key, function (){
            return $this->genererClassement($this->competition->sport->id);
        });
    }

    public function afficherClassementSimplifie(bool $complet = false)
    {
        $sport = strToUrl($this->competition->sport->nom);
        $competition = strToUrl($this->competition->nom);
        $hrefClassementComplet = route('classement', ['competition' => $competition, 'sport' => $sport]);
        $classement = $this->classement();
        return view('football.classement-simplifie', [
            'classement' => $classement,
            'hrefClassementComplet' => $hrefClassementComplet,
            'complet' => $complet
        ]);

    }

    /**
     * Génération du classement de la saison (en fonction du sport)
     *
     * @param int $sportId
     * @return array
     */
    private function genererClassement(int $sportId)
    {
        $bareme = $this->bareme;
        $sportId = $bareme->sport_id;
        foreach($this->equipes as $equipe){
            $matchesAller = $this->matches->where('equipe_id_dom', $equipe->id);
            $matchesRetour = $this->matches->where('equipe_id_ext', $equipe->id);
            $matches[$equipe->id] = $matchesAller->merge($matchesRetour);
        }

        $classement = [];
        $idBasketball = Sport::firstWhere('nom', 'like', 'basketball')->id ?? 0;
        $idVolleyball = Sport::firstWhere('nom', 'like', 'volleyball')->id ?? 0;
        foreach ($matches as $equipeId => $matchesEquipe) {
            $instanceEquipe = Equipe::findOrFail($equipeId);
            $nomEquipe = $instanceEquipe->nom;
            $fanionEquipe = $instanceEquipe->fanion();
            $classement[$equipeId]['nom'] = $nomEquipe;
            $classement[$equipeId]['fanion'] = $fanionEquipe;
            $classement[$equipeId]['points'] = 0;
            $classement[$equipeId]['joues'] = 0;
            $classement[$equipeId]['victoire'] = 0;
            $classement[$equipeId]['marques'] = 0;
            $classement[$equipeId]['encaisses'] = 0;

            if($sportId != $idBasketball && $sportId != $idVolleyball)
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
     * Définition de l'attribut nom qui nous renvoie un affichage de l'objet
     *
     * @return string
     */
    public function getNomAttribute()
    {
        return $this->competition->nom.' '.$this->annee('/');
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
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
