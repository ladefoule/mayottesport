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
use Illuminate\Support\Facades\Config;
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

    /**
     * Calendriers des journées passées ($categorie = 1) ou à venir ($categorie = 2)
     *
     * @param int $sportId
     * @param integer $categorie
     * @param integer $competitionId
     * @return void
     */
    public static function calendriersRender(array $params)
    {
        $sportId = $params['sport_id'] ?? '';

        $competitionId = $params['competition_id'] ?? '';
        $position = $params['position'] ?? '';
        $categorie = ($params['categorie'] == '+1') ? '+1' : '-1'; // -1 => résultats, +1 => à venir

        $competitions = index('competitions')
            ->where('sport_id', $sportId);
        
        if($competitionId)
        $competitions = $competitions->where('id', $competitionId);

        if($position)
        $competitions = $competitions->where($position . '_position', '>=', $position)
                        ->sortBy($position . '_position');

        $journees = [];
        // dd($competitions);
        foreach ($competitions as $competition) {
            // $saison = Saison::whereCompetitionId($competition->id)->firstWhere('finie', '!=', 1); // On recherche s'il y a une saison en cours
            $saison = index('saisons')->where('competition_id', $competition->id)->where('finie', '!=', 1)->first();
            if ($saison) {
                $saison = saison($saison->id);
                $journeeId = ($categorie == '-1') ? $saison['derniere_journee_id'] : $saison['prochaine_journee_id'];
                // $journeeId = $saison['derniere_journee_id'] != '' ? $saison['derniere_journee_id'] : $saison['prochaine_journee_id'];
                if ($journeeId)
                    $journees[] = collect([
                        'competition_nom' => $competition->nom,
                        'journee_render' => journee($journeeId)->render,
                    ]);
            }
        }

        $journeesView = view('journee.sport-index', ['journees' => $journees, 'sport' => index('sports')[$sportId]])->render();
        return $journeesView;
    }

    /**
     * Définition de l'affichage dans le CRUD
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        $types = config('listes.types-journees');
        $journeeNom = 'J' . str_pad($this->numero, 2, "0", STR_PAD_LEFT);

        return indexCrud('saisons')[$this->saison_id]->crud_name . ' - ' . (!$this->type ? $journeeNom : $types[$this->type][1]);
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
