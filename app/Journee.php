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
    public function infos()
    {
        $key = 'journee-'.$this->id;
        if(! Config::get('constant.activer_cache'))
            Cache::forget($key);

        if (Cache::has($key))
            return Cache::get($key);

        return Cache::rememberForever($key, function (){
            // L'ensemble des matches de la journée
            $matches = $this->matches->sortBy('date')->sortBy('heure');

            $journee = collect();
            $matchesCollect = [];
            foreach ($matches as $match)
                $matchesCollect[] = $match->infos();

            $journee->matches = $matchesCollect ?? [];
            $dateJournee = date('d/m/Y', strtotime($this->date));
            $journee->render = view('competition.journee', [
                'matches' => $journee->matches,
                'journee' => niemeJournee($this->numero) . ' : ' . $dateJournee
            ])->render();

            return $journee;
        });
    }

    /**
     * Affiche le résultat du calendrier de la journée envoyé à la view 'football.calendrier-journee'
     */
    // public function journeeRender()
    // {
    //     $dateJournee = date('d/m/Y', strtotime($this->date));
    //     $journee = niemeJournee($this->numero) . ' : ' . $dateJournee;
    //     return view('competition.journee', [
    //         'matches' => $this->matchesInfos(),
    //         'journee' => $journee
    //     ])->render();
    // }

    /**
     * Définition de l'affichage dans le CRUD
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        $journeeNom = 'J' . str_pad($this->numero, 2, "0", STR_PAD_LEFT);
        return index('saisons')[$this->saison_id]->crud_name . ' - ' . $journeeNom;
    }

    /**
     * Définition de l'attribut nom qui affichera le numéro de la journée avec la saison accollée
     */
    public function getNomAttribute()
    {
        // $saison = index('saisons')[$this->saison_id]->nom;
        // dd($saison);
        return niemeJournee($this->numero);
        // $journee = str_pad($this->numero, 2, "0", STR_PAD_LEFT);
        // return 'J' . $journee;
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
