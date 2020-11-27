<?php

namespace App\Http\Controllers;

use App\Saison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SportController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info(" -------- SportController : __construct -------- ");
        $this->middleware('sport');
    }

    public function index(Request $request)
    {
        Log::info(" -------- SportController : index -------- ");
        $sport = $request->sport;
        $competitions = $sport->competitions;
        $journees = [];
        foreach ($competitions as $competition) {
            $saison = Saison::whereCompetitionId($competition->id)->firstWhere('finie', '!=', 1); // On recherche s'il y a une saison en cours
            if($saison){
                $journee = $saison->derniereJournee() ?? $saison->prochaineJournee();
                if($journee){
                    $classement = '';
                    if($competition->type == 1) // Championnat
                        $classement = $saison->classementSimpleRender();

                    $journees[] = collect([
                        'competition_nom' => $competition->nom,
                        'journee_render' => $journee->journeeRender(),
                        'saison_classement' => $classement
                    ]);
                }
            }
        }

        return view('sport.index', [
            'sport' => $sport,
            'journees' => $journees
        ]);
    }
}
