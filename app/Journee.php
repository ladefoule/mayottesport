<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Collection;

class Journee extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['numero', 'date', 'saison_id'];

    /**
     * Les règles de validations
     *
     * @param Journee $journee
     * @return array
     */
    public static function rules(Journee $journee = null)
    {
        $numero = request()->numero ?? '';
        $saisonId = request()->saison_id ?? '';
        $saison = Saison::find($saisonId);

        // Si $saison == null, la validation ne passera pas à cause de la règle 'saison_id' qui doit exister dans la base
        // La valeur 0 ici sert juste à éviter d'avoir null comme maximum si saison = null
        $nbJournees = $saison->nb_journees ?? 0;
        $unique = Rule::unique('journees')->where(function ($query) use ($numero, $saisonId) {
            return $query->whereNumero($numero)->whereSaisonId($saisonId);
        })->ignore($journee);

        $rules = [
            'date' => 'required|date',
            'saison_id' => 'required|exists:saisons,id',
            'numero' => ['required','integer',"max:$nbJournees",'min:1',$unique]
        ];
        $messages = ['numero.unique' => "Ce numéro de journée, associé à cette saison, existe déjà."];
        return ['rules' => $rules, 'messages' => $messages];
    }

    /**
     * Retourne une collection contenant toutes les infos des matches de la journée.
     *
     * @return Collection
     */
    public function calendrier()
    {
        $key = 'journee-'.$this->id;
        if(! Config::get('constant.activer_cache'))
            Cache::forget($key);

        if (Cache::has($key))
            return Cache::get($key);
        else
            return Cache::rememberForever($key, function () {
                return $this->genererCalendrier();
            });
    }

    /**
     * Génération du calendrier s'il n'est plus en Cache.
     * La fonction renvoie une collection qui contient les infos de tous les matches de la journée.
     *
     * @return \Illuminate\Support\Collection
     */
    public function genererCalendrier()
    {
        $matches = $this->matches->sortBy('date')->sortBy('heure');

        $i = 0;
        $calendrier = [];
        foreach ($matches as $match) {
            $equipeDom = $match->equipeDom;
            $equipeExt = $match->equipeExt;
            $fanionDom = $equipeDom->fanion();
            $fanionExt = $equipeExt->fanion();
            $nomEquipeDom = $equipeDom->nom;
            $nomEquipeExt = $equipeExt->nom;

            $date = $match->dateFormat();
            $score = $match->score(); // Affiche soit l'heure soit le résultat du match
            $scoreEqDom = $match->score_eq_dom;
            $scoreEqExt = $match->score_eq_ext;

            $url = $match->url();
            $calendrier[$i]['url'] = $url;
            $calendrier[$i]['fanion_eq_dom'] = $fanionDom;
            $calendrier[$i]['fanion_eq_ext'] = $fanionExt;
            $calendrier[$i]['date'] = $date;
            $calendrier[$i]['score'] = $score;
            $calendrier[$i]['nom_eq_dom'] = $nomEquipeDom;
            $calendrier[$i]['nom_eq_ext'] = $nomEquipeExt;
            $calendrier[$i]['score_eq_dom'] = $scoreEqDom;
            $calendrier[$i]['score_eq_ext'] = $scoreEqExt;
            $calendrier[$i]['url'] = $url;

            $i++;
        }

        return collect($calendrier);
    }

    /**
     * Affiche le résultat du calendrier de la journée envoyé à la view 'football.calendrier-journee'
     */
    // public function displayDay()
    // {
    //     // $sport = strToUrl($this->saison->championnat->sport->nom);
    //     $dateJournee = date('d/m/Y', strtotime($this->date));
    //     $journee = niemeJournee($this->numero) . ' : ' . $dateJournee;
    //     return view('competition.journee', [
    //         'calendrier' => $this->calendrier(),
    //         'journee' => $journee
    //     ])->render();
    // }

    /**
     * Définition de l'affichage dans le CRUD (back-office)
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        $saison = awesome('saisons')[$this->saison_id]['crud_name'];
        $journee = str_pad($this->numero, 2, "0", STR_PAD_LEFT);
        return $saison . ' - J' . $journee;
    }

    /**
     * Définition de l'attribut nom qui affichera le numéro de la journée avec la saison accollée
     */
    public function getNomAttribute()
    {
        $saison = $this->saison->nom;
        $journee = str_pad($this->numero, 2, "0", STR_PAD_LEFT);
        return 'J' . $journee . ' - ' . $saison;
    }

    /**
     * Retourne l'url de la page de la journée. Retourne false si la saison est déjà finie.
     *
     * @return string|false
     */
    public function url()
    {
        $saison = $this->saison;
        if($saison->finie)
            return false;

        $competition = $saison->competition;
        $sport = $competition->sport;
        return route('competition.calendrier-resultats', [
            'sport' => strToUrl($sport->nom),
            'competition' => strToUrl($competition->nom),
            'journee' => $this->numero
        ]);
    }

    /**
     * Tous les matches de cette journée.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matches()
    {
        return $this->hasMany('App\Match');
    }

    /**
     * La saison liée à cette journée.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function saison()
    {
        return $this->belongsTo('App\Saison');
    }
}
