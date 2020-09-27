<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Collection;

class ChampJournee extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['numero', 'date', 'champ_saison_id'];

    /**
     * Les règles de validations
     *
     * @param Request $request
     * @param ChampJournee $champJournee
     * @return array
     */
    public static function rules(Request $request, ChampJournee $champJournee = null)
    {
        $numero = $request['numero'] ?? '';
        $champSaisonId = $request['champ_saison_id'] ?? '';
        $champSaison = ChampSaison::find($champSaisonId);
        // Si $champSaison == null, la validation ne passera pas à cause de la règle 'champ_saison_id'
        // La valeur 0 ici n'a donc aucune importance, elle sert juste à éviter d'avoir null comme maximum
        $nbJournees = $champSaison->nb_journees ?? 0;
        $unique = Rule::unique('champ_journees')->where(function ($query) use ($numero, $champSaisonId) {
            return $query->whereNumero($numero)->whereChampSaisonId($champSaisonId);
        });

        if($champJournee){
            $id = $champJournee->id;
            $unique = $unique->ignore($id);
        }

        $rules = [
            'date' => 'required|date',
            'champ_saison_id' => 'required|exists:champ_saisons,id',
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
        if(!Config::get('constant.activer_cache', false))
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
     * @return Collection
     */
    public function genererCalendrier()
    {
        $champMatches = $this->champMatches->sortBy('date')->sortBy('heure');

        $i = 0;
        $calendrier = [];
        foreach ($champMatches as $champMatch) {
            $equipeDom = $champMatch->equipeDom;
            $equipeExt = $champMatch->equipeExt;
            $fanionDom = $equipeDom->fanion();
            $fanionExt = $equipeExt->fanion();
            $nomEquipeDom = $equipeDom->nom;
            $nomEquipeExt = $equipeExt->nom;

            $date = $champMatch->dateFormat();
            $score = $champMatch->score(); // Affiche soit l'heure soit le résultat du match
            $scoreEqDom = $champMatch->score_eq_dom;
            $scoreEqExt = $champMatch->score_eq_ext;

            $url = $champMatch->url();
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

        return new Collection($calendrier);
    }


    /**
     * Affiche le résultat du calendrier de la journée envoyé à la view 'football.calendrier-journee'
     */
    public function afficherCalendrier()
    {
        $dateJournee = date('d/m/Y', strtotime($this->date));
        $journee = niemeJournee($this->numero) . ' : ' . $dateJournee;
        return view('football.calendrier-journee', [
            'calendrier' => $this->calendrier(),
            'journee' => $journee
        ])->render();
    }

    /**
     * Définition de l'attribut nom qui affichera le numéro de la journée avec la saison accollée
     */
    public function getNomAttribute()
    {
        $saison = $this->champSaison->nom;
        $journee = str_pad($this->numero, 2, "0", STR_PAD_LEFT);
        return 'J' . $journee . ' - ' . $saison;
    }

    /**
     * Tous les matches de cette journée.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function champMatches()
    {
        return $this->hasMany('App\ChampMatch');
    }

    /**
     * La saison liée à cette journée.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function champSaison()
    {
        return $this->belongsTo('App\ChampSaison');
    }
}
