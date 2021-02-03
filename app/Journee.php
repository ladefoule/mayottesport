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
        $saisonId = request()->input('saison_id');
        $numero = request()->input('numero');

        $unique = Rule::unique('journees')->where(function ($query) use ($saisonId, $numero) {
            return $query->whereSaisonId($saisonId)->whereNumero($numero);
        })->ignore($journee);

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

            $saison = $this->saison;
            $competition = $saison->competition;
            $sport = $competition->sport;

            // L'ensemble des matches de la journée
            $matches = $this->matches->sortBy('date')->sortBy('heure');

            $journee = collect();
            // On associe d'abord tous les attributs
            foreach ($this->attributes as $key => $value)
                $journee->$key = $value;

            $matchesAvecInfos = [];
            foreach ($matches as $match)
                $matchesAvecInfos[] = $match->infos();

            $journee->matches = $matchesAvecInfos;
            $journee->nom = $this->nom;
            $dateJournee = date('d/m/Y', strtotime($this->date));
            $types = config('listes.types-journees');
            if($this->type && isset($types[$this->type]))
                $typeJournee = $types[$this->type][1];
            else
                $typeJournee = niemeJournee($this->numero);

            $journee->render_section_droite = view('journee.calendrier-section-droite', [
                'matches' => $journee->matches,
                'journee' => $typeJournee,
                'date' => $dateJournee,
            ])->render();

            $journee->render_main = view('journee.calendrier-main', [
                'matches' => $journee->matches,
                'journee' => $typeJournee,
                'date' => $dateJournee,
            ])->render();

            $journee->href = route('competition.calendrier-resultats', ['sport' => $sport->slug, 'competition' => $competition->slug_complet, 'annee' => $saison->annee(), 'journee' => $this->numero]);;

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
    public static function calendriersPageSport($sport, $page = 'home')
    {
        // $competitions = Competition::whereSportId($sport->id)->where('home_position', '>=', 1)->get();
        $competitions = index('competition')->where('sport_id', $sport->id)->where($page . '_position', '>=', 1)->sortBy($page . '_position');
        foreach ($competitions as $competition) {
            // $saison = $competition->saisons()->orderBy('annee_debut', 'desc')->first();
            $saison = index('saisons')->where('competition_id', $competition->id)->sortByDesc('annee_debut')->first();
            if($saison){
                // $derniereJournee = $saison->journees()->where('date', '<', date('Y-m-d'))->orderBy('date', 'desc')->first();
                $derniereJournee = index('journees')->where('saison_id', $saison->id)->where('date', '<', date('Y-m-d'))->sortByDesc('date')->first();
                if($derniereJournee)
                    $resultats[] = [
                        'competition_nom' => $competition->nom,
                        'competition_href' => route('competition.index', ['sport' => $sport->slug, 'competition' => $competition->slug_complet]),
                        'journee_render' => journee($derniereJournee->id)->render_section_droite
                    ];
    
                // $prochaineJournee = $saison->journees()->where('date', '>=', date('Y-m-d'))->orderBy('date')->first();
                $prochaineJournee = index('journees')->where('saison_id', $saison->id)->where('date', '>=', date('Y-m-d'))->sortBy('date')->first();
                if($prochaineJournee)
                    $prochains[] = [
                        'competition_nom' => $competition->nom,
                        'competition_href' => route('competition.index', ['sport' => $sport->slug, 'competition' => $competition->slug_complet]),
                        'journee_render' => journee($prochaineJournee->id)->render_section_droite
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
