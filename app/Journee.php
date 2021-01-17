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
    protected $fillable = ['numero', 'date', 'saison_id', 'type'];

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
                'journee' => niemeJournee($this->numero) . ' : ' . $dateJournee
            ])->render();

            return $journee;
        });
    }

    public static function calendriersPageHome()
    {
        $sports = Sport::where('home_position', '>=', 1)->orderBy('home_position')->get();
        foreach ($sports as $sport){
            $competitions = Competition::whereSportId($sport->id)->where('home_position', '>=', 1)->get();
            foreach ($competitions as $competition) {
                $saison = $competition->saisons()->orderBy('annee_debut', 'desc')->first();
                if($saison){
                    $derniereJournee = $saison->journees()->where('date', '<', date('Y-m-d'))->orderBy('date', 'desc')->first();
                    if ($derniereJournee)
                        $resultats[$sport->nom][] = [
                            'competition_nom' => $competition->nom,
                            'competition_href' => route('competition.index', ['sport' => $sport->slug, 'competition' => $competition->slug]),
                            'journee_render' => journee($derniereJournee->id)->render
                        ];
        
                    $prochaineJournee = $saison->journees()->where('date', '>=', date('Y-m-d'))->orderBy('date')->first();
                    if ($prochaineJournee)
                        $prochains[$sport->nom][] = [
                            'competition_nom' => $competition->nom,
                            'competition_href' => route('competition.index', ['sport' => $sport->slug, 'competition' => $competition->slug]),
                            'journee_render' => journee($prochaineJournee->id)->render
                        ];
                }
            }
        }

        return [
            'resultats' => $resultats ?? [],
            'prochains' => $prochains ?? [],
        ];
    }

    /**
     * Undocumented function
     *
     * @param Sport|Collection $sport
     * @return void
     */
    public static function calendriersPageSport($sport)
    {
        $competitions = Competition::whereSportId($sport->id)->where('home_position', '>=', 1)->get();
        foreach ($competitions as $competition) {
            $saison = $competition->saisons()->orderBy('annee_debut', 'desc')->first();
            if($saison){
                $derniereJournee = $saison->journees()->where('date', '<', date('Y-m-d'))->orderBy('date', 'desc')->first();
                if($derniereJournee)
                    $resultats[$sport->nom][] = [
                        'competition_nom' => $competition->nom,
                        'competition_href' => route('competition.index', ['sport' => $sport->slug, 'competition' => $competition->slug]),
                        'journee_render' => journee($derniereJournee->id)->render
                    ];
    
                $prochaineJournee = $saison->journees()->where('date', '>=', date('Y-m-d'))->orderBy('date')->first();
                if($prochaineJournee)
                    $prochains[$sport->nom][] = [
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
