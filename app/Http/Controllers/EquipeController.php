<?php

namespace App\Http\Controllers;

use App\Match;
use App\Equipe;
use App\Saison;
use App\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        Log::info(" -------- EquipeController : __construct -------- ");
        $this->middleware(['sport', 'equipe'])->except('matchesAjax');
    }

    public function index(Request $request)
    {
        $equipe = $request->equipe;
        $sport = $request->sport;
        $matches = $equipe->matches; // Tous les matches de l'équipe

        // On recherche le prochain match de l'équipe toute compétition confondue
        $prochainMatch = $matches->where('date', '>=', date('Y-m-d'))->sortBy('date')->first();
        $prochainMatchRender = $prochainMatch ? $prochainMatch->matchRender($equipe) : '';

        // On recherche le dernier match de l'équipe toute compétition confondue
        $dernierMatch = $matches->where('date', '<', date('Y-m-d'))->sortByDesc('date')->first();
        $dernierMatchRender = $dernierMatch ? $dernierMatch->matchRender($equipe) : '';

        // Toutes les saisons dans lesquelles l'équipe a joué
        $saisons = $equipe->saisons;

        // On récupère la liste des compétitions à partir de la liste des saisons
        $competitions = collect();
        foreach ($saisons as $saison)
            $competitions[] = index('competitions')[$saison->competition_id];

        $competitions = $competitions->unique();

        $derniereSaison = $derniereCompetition = '';
        if(count($competitions) > 0){
            // On affiche en priorité la dernière saison de championnat
            $derniereCompetition = $competitions->where('type', 1)->first();
            if(! $derniereCompetition)
                $derniereCompetition = $competitions->first();

            // On affiche les saisons de la compétition sélectionnée dans le 2ème select
            $saisons = $saisons->where('competition_id', $derniereCompetition['id']);
            $derniereSaison = $saisons->sortBy('annee_debut')->first();

            $matches = $derniereSaison->matches->where('equipe_id_dom', $equipe->id);
            $matches = $matches->union($derniereSaison->matches->where('equipe_id_ext', $equipe->id))->sortBy('date');

            foreach ($matches as $match)
                $match['resultat'] = $match->resultat($equipe->id)['resultat'] ?? '';
        }

        $title = $equipe->nom . ' - ' . $sport->nom;
        return view('equipe.index', [
            'equipe' => $equipe,
            'title' => $title,
            'derniereSaison' => $derniereSaison,
            'saisons' => $saisons,
            'competitions' => $competitions,
            'derniereCompetition' => $derniereCompetition,
            'sport' => $sport,
            'matches' => $matches,
            'dernierMatch' => $dernierMatchRender,
            'prochainMatch' => $prochainMatchRender,
        ]);
    }

    public function matchesAjax(Request $request)
    {
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
