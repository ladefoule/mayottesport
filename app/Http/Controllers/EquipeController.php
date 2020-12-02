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
        // $matches = $equipe->matches; // Tous les matches de l'équipe
        $matches = index('matches')->where('equipe_id_dom', $equipe->id);
        $matches = $matches->union(index('matches')->where('equipe_id_ext', $equipe->id));

        // On recherche le prochain match de l'équipe toute compétition confondue
        $prochainMatch = $matches->where('date', '>=', date('Y-m-d'))->sortBy('date')->first();
        $prochainMatchRender = '';
        if($prochainMatch)
            $prochainMatchRender = ($prochainMatch->equipe_id_dom == $equipe->id) ? match($prochainMatch->id)['render_eq_dom'] : match($prochainMatch->id)->render_eq_ext;

        // On recherche le dernier match de l'équipe toute compétition confondue
        $dernierMatch = $matches->where('date', '<', date('Y-m-d'))->sortByDesc('date')->first();
        $dernierMatchRender = '';
        if($dernierMatch)
            $dernierMatchRender = ($dernierMatch->equipe_id_dom == $equipe->id) ? match($dernierMatch->id)['render_eq_dom'] : match($dernierMatch->id)->render_eq_ext;

        // Toutes les saisons dans lesquelles l'équipe a joué
        $saisons = $equipe->saisons;
        $saisons = index('equipe_saison')->where('equipe_id', $equipe->id)->pluck('saison_id');
        // dd($saisons);

        // On récupère la liste des compétitions à partir de la liste des saisons
        $competitions = collect();
        foreach ($saisons as $id => $saisonId){
            $saison = index('saisons')[$saisonId];
            $competitions[] = index('competitions')[$saison->competition_id];
            $saisons[$id] = $saison;
        }

        $competitions = $competitions->unique();

        $derniereSaison = $derniereCompetition = '';
        if(count($competitions) > 0){
            // On affiche en priorité la dernière saison de championnat
            $derniereCompetition = $competitions->where('type', 1)->first();
            if(! $derniereCompetition)
                $derniereCompetition = $competitions->first();

            // On affiche les saisons de la compétition sélectionnée dans le 2ème select
            $saisons = $saisons->where('competition_id', $derniereCompetition->id);
            $derniereSaison = $saisons->sortBy('annee_debut')->first();

            $journees = index('journees')->where('saison_id', $saison->id)->pluck('id');
            $matches = $matches->whereIn('journee_id', $journees);
            // dd($matchesDeLaSaison);
            // $matchesAller = $matchesDeLaSaison->where('saison_id', $derniereSaison->id)->where('equipe_id_dom', $equipe->id);
            // $matchesRetour = $matchesDeLaSaison->where('saison_id', $derniereSaison->id)->where('equipe_id_ext', $equipe->id);
            // $matches = $matchesAller->union($matchesRetour)->sortBy('date');


            foreach ($matches as $id => $match){
                $matches[$id] = match($match->id);
                // $match->resultat = Match::find($match->id)->resultat($equipe->id)['resultat'] ?? '';
                // dd($match);
            }
        }

        // dd($matches);
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
