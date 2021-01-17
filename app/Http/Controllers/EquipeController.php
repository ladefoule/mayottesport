<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Controllers;

use App\Equipe;
use App\Saison;
use App\Article;
use App\Journee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EquipeController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info("Accès au controller Equipe - Ip : " . request()->ip());
        $this->middleware(['sport', 'equipe'])->except('matchesAjax');
    }

    public function index(Request $request)
    {
        Log::info(" -------- Controller Equipe : index -------- ");
        $equipe = $request->equipe;
        $sport = $request->sport;

        $matches = $equipe->matches; // Tous les matches de l'équipe

        // On recherche le prochain match de l'équipe toute compétition confondue
        $prochainMatch = $matches->where('date', '>=', date('Y-m-d'))->sortBy('date')->first();
        $prochainMatchRender = '';
        if($prochainMatch){
            $prochainMatch->resultat = $prochainMatch->resultat($equipe->id)['resultat'] ?? '';
            $matchesCollect[] = $prochainMatch;
            $prochainMatchRender = view('equipe.matches-ajax', [
                'equipe' => $equipe,
                'matches' => $matchesCollect
            ])->render();
        }

        // On recherche le dernier match de l'équipe toute compétition confondue
        $dernierMatch = $matches->where('date', '<', date('Y-m-d'))->sortByDesc('date')->first();
        $dernierMatchRender = '';
        $matchesCollect = [];
        if($dernierMatch){
            $dernierMatch->resultat = $dernierMatch->resultat($equipe->id)['resultat'] ?? '';
            $matchesCollect[] = $dernierMatch;
            $dernierMatchRender = view('equipe.matches-ajax', [
                'equipe' => $equipe,
                'matches' => $matchesCollect
            ])->render();
        }

        // Toutes les saisons dans lesquelles l'équipe a joué
        $saisons = index('equipe_saison')->where('equipe_id', $equipe->id)->pluck('saison_id');

        // On récupère la liste des compétitions à partir de la liste des saisons
        $competitions = collect();
        foreach ($saisons as $id => $saisonId){
            $saison = index('saisons')[$saisonId];
            $competitions[] = index('competitions')[$saison->competition_id];
            $saisons[$id] = $saison;
        }

        // On filtre la collection pour garder que des résultats distincts
        $competitions = $competitions->unique();

        // Filtrage de la saison par année
        $saisons = $saisons->sortByDesc('annee_debut');

        $derniereSaison = $derniereCompetition = '';
        if(count($saisons) > 0){
            // On sélectionne la saison la plus récente non finie
            $derniereSaison = $saisons->sortBy('finie')->first();

            // On récupère la compétition de la dernière saison
            $derniereCompetition = index('competitions')[$derniereSaison->competition_id];

            // On affiche les saisons de la compétition sélectionnée dans le 2ème select
            $saisons = $saisons->where('competition_id', $derniereCompetition->id)->sortByDesc('annee_debut');

            // On récupère tous les matches de la saison
            $journees = index('journees')->where('saison_id', $derniereSaison->id)->pluck('id');
            $matches = $matches->whereIn('journee_id', $journees);
        }

        // On récupère les infos pour chaque match
        foreach ($matches as $id => $match)
            $matches[$id] = match($match->uniqid);

        $calendriers = Journee::calendriersPageSport($sport);
        $filActualites = Article::filActu($sport);

        $title = $equipe->nom . ' - ' . $sport->nom;
        return view('equipe.index', [
            'equipe' => $equipe,
            'title' => $title,
            'derniereSaison' => $derniereSaison,
            'saisons' => $saisons,
            'competitions' => $competitions,
            'derniereCompetition' => $derniereCompetition,
            'sport' => $sport,
            'matches' => $matches->sortBy('date'),
            'dernierMatch' => $dernierMatchRender,
            'prochainMatch' => $prochainMatchRender,
            'resultats' => $calendriers['resultats'],
            'prochains' => $calendriers['prochains'],
            'filActualites' => $filActualites
        ]);
    }

    public function matchesAjax(Request $request)
    {
        Log::info(" -------- Controller Equipe : matchesAjax -------- ");
        $equipeId = $request['equipe_id'];
        $saisonId = $request['saison_id'];

        $saison = Saison::findOrFail($saisonId);
        $equipe = Equipe::findOrFail($equipeId);

        $matches = $saison->matches->where('equipe_id_dom', $equipeId);
        $matches = $matches->union($saison->matches->where('equipe_id_ext', $equipeId))->sortBy('date');

        foreach ($matches as $match)
            $match['resultat'] = $match->resultat($equipeId)['resultat'] ?? '';

        return view('equipe.matches-ajax', [
            'equipe' => $equipe,
            'matches' => $matches,
        ])->render();
    }
}
