<?php

namespace App\Http\Controllers;

use App\Match;
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
        $this->middleware(['sport', 'equipe']);
    }

    public function index(Request $request)
    {
        $equipe = $request->equipe;
        $sport = $request->sport;

        // On recherche le prochain match de l'équipe toute compétition confondue
        $prochainMatch = Match::whereEquipeIdDom($equipe->id)->where('date', '>=', date('Y-m-d'))
                ->orWhere(function($query) use($equipe) {
                    $query->whereEquipeIdExt($equipe->id)
                        ->where('date', '>=', date('Y-m-d'));
                })
                ->orderBy('date')
                ->first();
        $prochainMatchRender = $prochainMatch ? $prochainMatch->matchRender($equipe) : '';

        // On recherche le dernier match de l'équipe toute compétition confondue
        $dernierMatch = Match::whereEquipeIdDom($equipe->id)->where('date', '<', date('Y-m-d'))
                ->orWhere(function($query) use($equipe) {
                    $query->whereEquipeIdExt($equipe->id)
                        ->where('date', '<', date('Y-m-d'));
                })
                ->orderBy('date', 'desc')
                ->first();
        $dernierMatchRender = $dernierMatch ? $dernierMatch->matchRender($equipe) : '';

        // Jointures entre les tables pour recherche les matches de l'équipe, les saisons, etc...
        $saisons = DB::table('competitions')
            ->join('saisons', 'competitions.id', 'competition_id')
            ->join('equipe_saison', 'saison_id', 'saisons.id')
            ->join('equipes', 'equipes.id', 'equipe_id')
            ->where('equipes.id', $equipe->id);

        // Les compétitions dans lesquelles l'équipe à jouer
        $competitions = $saisons
            ->select('competitions.*')
            ->orderBy('saisons.finie')
            ->distinct()
            ->get();

        $matches = '';
        if(count($competitions) > 0){
            // On affiche en priorité la dernière saison de championnat
            $derniereCompetition = $competitions->where('type', 1)->first();
            if(! $derniereCompetition)
                $derniereCompetition = $competitions->first();

            $saisons = Competition::find($derniereCompetition->id)->saisons;

            $matches = collect();
            $derniereSaison = $saisons->first();

            $matches = $derniereSaison->matches->where('equipe_id_dom', $equipe->id);
            $matches = $matches->union($derniereSaison->matches->where('equipe_id_ext', $equipe->id));

            foreach ($matches as $match)
                $match['resultat'] = $match->resultat($equipe->id)['resultat'] ?? '';
        }
        $title = $equipe->nom . ' - ' . $sport->nom;
        return view('equipe.index', [
            'equipe' => $equipe,
            'title' => $title,
            'derniereSaison' => $derniereSaison ?? '',
            'saisons' => $saisons ?? [],
            'competitions' => $competitions ?? [],
            'derniereCompetition' => $derniereCompetition ?? '',
            'sport' => $sport,
            'matches' => $matches ? $matches->sortBy('date') : '',
            'dernierMatch' => $dernierMatchRender ?? '',
            'prochainMatch' => $prochainMatchRender ?? '',
        ]);
    }
}
