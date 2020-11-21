<?php

namespace App\Http\Controllers;

use App\Match;
use App\Saison;
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

        // Les compétitions dans lesquelles l'équipe à jouer
        $saisons = DB::table('competitions')
            ->join('saisons', 'competitions.id', 'competition_id')
            ->join('equipe_saison', 'saison_id', 'saisons.id')
            ->join('equipes', 'equipes.id', 'equipe_id')
            ->where('equipes.id', $equipe->id)
            ->select('competitions.*', 'saisons.*', 'competition_id', 'saison_id')
            // ->select('saisons.*')
            ->orderBy('saisons.finie')
            ->distinct()
            ->get();

        // $saisons = $request->competitions;
        // dd($saisons);
        $matches = collect();
        // $matches = $saison->matches;
        foreach($saisons as $saison){
            $saison = Saison::findOrFail($saison->saison_id);
            $matches = $matches->union($saison->matches->where('equipe_id_dom', $equipe->id));
            $matches = $matches->union($saison->matches->where('equipe_id_ext', $equipe->id));
        }

        foreach ($matches as $match) {
            $match['resultat'] = $match->resultat($equipe->id)['resultat'] ?? '';
            // $match = $match->infos();
        }
        // dd($matches);
        $title = $equipe->nom . ' - ' . $sport->nom;
        return view('equipe.index', [
            'equipe' => $equipe,
            'title' => $title,
            'saisons' => $saisons,
            'sport' => $sport,
            'matches' => $matches->sortBy('date')
        ]);
    }
}
