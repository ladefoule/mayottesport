<?php

/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Journee extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['numero', 'date', 'saison_id', 'type', 'acces_bloque'];

    /**
     * Les règles de validations
     *
     * @param Journee $journee
     * @return array
     */
    public static function rules(Journee $journee = null)
    {
        $unique = Rule::unique('journees', 'numero', 'saison_id')->ignore($journee);

        $rules = [
            'date' => 'nullable|date',
            'saison_id' => 'required|exists:saisons,id',
            'type' => 'nullable|integer|min:0',
            'acces_bloque' => 'nullable|boolean',
            'numero' => ['required', 'integer', 'min:1', "max:100", $unique]
        ];
        $messages = ['numero.unique' => "Cette journée existe déjà dans cette saison."];
        return ['rules' => $rules, 'messages' => $messages];
    }

    /**
     * Retourne une collection contenant toutes les infos des matches de la journée.
     *
     * @return Collection
     */
    public function infos()
    {
        $key = 'journee-' . $this->id;
        if (Cache::has($key))
            return Cache::get($key);

        return Cache::rememberForever($key, function () {
            Log::info('Rechargement du cache : journee-' . $this->id);

            // L'ensemble des matches de la journée
            $matches = $this->matches->sortBy('date')->sortBy('heure');

            $journee = collect();
            $matchesInfos = [];
            foreach ($matches as $match)
                $matchesInfos[] = $match->infos();

            $journee->matches = $matchesInfos;
            $dateJournee = date('d/m/Y', strtotime($this->date));
            $journee->render = view('journee.calendrier', [
                'matches' => $journee->matches,
                'journee' => niemeJournee($this->numero),
                'date' => $dateJournee,
            ])->render();

            return $journee;
        });
    }

    /**
     * Affichage des calendriers pour la page d'accueil
     *
     * @return array
     */
    public static function calendriersPageHome()
    {
        // $sports = Sport::where('home_position', '>=', 1)->orderBy('home_position')->get();
        $sports = index('sports')->where('home_position', '>=', 1)->sortBy('home_position');
        foreach ($sports as $sport){
            $calendriers = Journee::calendriersPageSport($sport);
            if(count($calendriers['resultats']) > 0)
                $resultats[$sport->nom] = $calendriers['resultats'];
            if(count($calendriers['prochains']) > 0)
                $prochains[$sport->nom] = $calendriers['prochains'];
        }

        return [
            'resultats' => $resultats ?? [],
            'prochains' => $prochains ?? [],
        ];
    }

    /**
     * Affichage des calendriers pour un sport (1 journée par compétition)
     *
     * @param Sport|Collection $sport
     * @return array
     */
    public static function calendriersPageSport($sport)
    {
        // $competitions = Competition::whereSportId($sport->id)->where('home_position', '>=', 1)->get();
        $competitions = index('competition')->where('sport_id', $sport->id)->where('home_position', '>=', 1);
        foreach ($competitions as $competition) {
            // $saison = $competition->saisons()->orderBy('annee_debut', 'desc')->first();
            $saison = index('saisons')->where('competition_id', $competition->id)->sortByDesc('annee_debut')->first();
            if($saison){
                // $derniereJournee = $saison->journees()->where('date', '<', date('Y-m-d'))->orderBy('date', 'desc')->first();
                $derniereJournee = index('journees')->where('saison_id', $saison->id)->where('date', '<', date('Y-m-d'))->sortBy('date')->first();
                if($derniereJournee)
                    $resultats[] = [
                        'competition_nom' => $competition->nom,
                        'competition_href' => route('competition.index', ['sport' => $sport->slug, 'competition' => $competition->slug]),
                        'journee_render' => journee($derniereJournee->id)->render
                    ];
    
                // $prochaineJournee = $saison->journees()->where('date', '>=', date('Y-m-d'))->orderBy('date')->first();
                $prochaineJournee = index('journees')->where('saison_id', $saison->id)->where('date', '>=', date('Y-m-d'))->sortBy('date')->first();
                if($prochaineJournee)
                    $prochains[] = [
                        'competition_nom' => $competition->nom,
                        'competition_href' => route('competition.index', ['sport' => $sport->slug, 'competition' => $competition->slug]),
                        'journee_render' => journee($prochaineJournee->id)->render
                    ];
            }
        }

        return [
            'resultats' => $resultats ?? [],
            'prochains' => $prochains ?? [],
        ];
    } 

    /**
     * Définition de l'attribut nom qui affichera le numéro de la journée avec la saison accollée
     */
    public function getNomAttribute()
    {
        $types = config('listes.types-journees');
        $journeeNom = niemeJournee($this->numero);

        return !$this->type ? $journeeNom : $types[$this->type][1];
    }

    /**
     * Retourne l'url de la page de la journée. Retourne false si la saison est déjà finie.
     *
     * @return string|false
     */
    public function url()
    {
        $saison = $this->saison;
        if ($saison->finie)
            return false;

        $competition = $saison->competition;
        $sport = $competition->sport;
        return route('competition.calendrier-resultats', [
            'sport' => Str::slug($sport->nom),
            'competition' => Str::slug($competition->nom),
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
