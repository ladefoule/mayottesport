<?php

namespace App\Http\Controllers;

use App\Match;
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
        $competitions = DB::table('competitions')
            ->join('saisons', 'competitions.id', 'competition_id')
            ->join('equipe_saison', 'saison_id', 'saisons.id')
            ->join('equipes', 'equipes.id', 'equipe_id')
            ->where('equipes.id', $equipe->id)
            ->select('competitions.*')
            // ->select('saisons.*')
            ->orderBy('saisons.finie')
            ->distinct()
            ->get();

        // $competitions = $request->competitions;
        $matches = Match::whereEquipeIdDom($equipe->id)->get();
        // dd($competitions);
        $title = $equipe->nom . ' - ' . $sport->nom;
        return view('equipe.index', [
            'equipe' => $equipe,
            'title' => $title,
            'competitions' => $competitions,
            'sport' => $sport,
            'matches' => $matches
        ]);
    }
}
