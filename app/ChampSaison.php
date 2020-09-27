<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

class ChampSaison extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['annee_debut', 'annee_fin', 'finie', 'nb_journees', 'champ_bareme_id', 'championnat_id'];

    /**
     * Les règles de validations
     *
     * @param Request $request
     * @param ChampSaison $champSaison
     * @return array
     */
    public static function rules(Request $request, ChampSaison $champSaison = null)
    {
        $anneeDebut = $request['annee_debut'] ?? '';
        $championnatId = $request['championnat_id'] ?? '';
        $unique = Rule::unique('champ_saisons')->where(function ($query) use ($anneeDebut, $championnatId) {
            return $query->whereAnneeDebut($anneeDebut)->whereChampionnatId($championnatId);
        });

        if($champSaison){
            $id = $champSaison->id;
            $unique = $unique->ignore($id);
        }

        $request['finie'] = $request->has('finie');
        $rules = [
            'annee_debut' => ['required','integer','min:2000','max:3000',$unique],
            'annee_fin' => 'required|integer|min:2000|max:3000|gte:annee_debut',
            'nb_journees' => 'required|integer|min:1|max:100',
            'champ_bareme_id' => 'required|exists:champ_baremes,id',
            'championnat_id' => 'required|exists:championnats,id',
            'finie' => 'boolean'
        ];
        $messages = ['annee_debut.unique' => "Le championnat possède déjà une saison qui débute la même année."];
        return ['rules' => $rules, 'messages' => $messages, 'request' => $request];
    }

    /**
     * La fonction renvoie le classement s'il est déjà en cache. Sinon, elle fait appelle à la fonction genererClassement
     *
     * @return array
     */
    public function classement()
    {
        $key = 'classement-'.$this->id;
        if(!Config::get('constant.activer_cache', false))
            Cache::forget($key);

        if (Cache::has($key))
            return Cache::get($key);

        return Cache::rememberForever($key, function () {
            return $this->genererClassement($this->championnat->sport->id);
        });
    }

    public function afficherClassementSimplifie()
    {
        $annee = $this->annee();
        $championnat = $this->championnat->nom;
        $championnat = stripAccents(Str::kebab(str_replace(' ', '-', $championnat)));
        $hrefClassementComplet = route('classement', ['championnat' => $championnat, 'annee' => $annee]);
        $classement = $this->classement();
        return view('football.classement-simplifie', [
            'classement' => $classement,
            'hrefClassementComplet' => $hrefClassementComplet
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
        $champBareme = $this->champBareme;
        $sportId = $champBareme->sport_id;
        foreach($this->equipes as $equipe){
            $matchesAller = $this->champMatches->where('equipe_id_dom', $equipe->id);
            $matchesRetour = $this->champMatches->where('equipe_id_ext', $equipe->id);
            $matches[$equipe->id] = $matchesAller->merge($matchesRetour);
        }

        $classement = [];
        $idBasketball = Sport::firstWhere('nom', 'like', 'basketball')->id ?? 0;
        $idVolleyball = Sport::firstWhere('nom', 'like', 'volleyball')->id ?? 0;
        foreach ($matches as $equipeId => $matchesEquipe) {
            $equipe = Equipe::findOrFail($equipeId);
            $nomEquipe = $equipe->nom;
            $fanionEquipe = $equipe->fanion();
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
                    $classement[$equipeId]['points'] += $champBareme->$resultat;

                    $classement[$equipeId]['marques'] += $marques;
                    $classement[$equipeId]['encaisses'] += $encaisses;
                }
            }

            $classement[$equipeId]['diff'] = $classement[$equipeId]['marques'] - $classement[$equipeId]['encaisses'];
        };

        $classement = new Collection($classement);
        return $classement->sortByDesc('points');
    }

    /**
     * Définition de l'attribut nom qui nous renvoie un affichage de l'objet
     *
     * @return string
     */
    public function getNomAttribute()
    {
        return $this->championnat->nom.' '.$this->annee('/');
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
    public function champJournees()
    {
        return $this->hasMany('App\ChampJournee');
    }

    /**
     * Les équipes participantes à la saison
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipes()
    {
        return $this->belongsToMany('App\Equipe')
                    ->using('App\ChampSaisonEquipe');
    }

    /**
     * Tous les matches de la saison
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function champMatches()
    {
        return $this->hasManyThrough('App\ChampMatch', 'App\ChampJournee');
    }

    /**
     * Le championnat lié à la saison
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function championnat()
    {
        return $this->belongsTo('App\Championnat');
    }

    /**
     * Le barème lié à la saison
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function champBareme()
    {
        return $this->belongsTo('App\ChampBareme');
    }
}
