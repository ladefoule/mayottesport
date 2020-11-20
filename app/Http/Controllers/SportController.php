<?php

namespace App\Http\Controllers;

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
        $liste = [];
        foreach ($competitions as $competition) {
            $saison = $competition->saisons->firstWhere('finie', '!=', 1); // On recherche s'il y a une saison en cours
            if($saison){
                $journee = $saison->derniereJournee();
                if(! $journee) $journee = $saison->prochaineJournee();
                if($journee){
                    $classement = '';
                    if($competition->type == 1)
                        $classement = view('competition.classement-simple', [
                            'classement' => $saison->classement(),
                            'hrefClassementComplet' => $saison->href()
                        ])->render();

                    $liste[] = [
                        'nom' => $competition->nom,
                        'journee' => $journee->displayDay(),
                        'classement' => $classement
                    ];
                }
            }
        }
        // dd($liste);
        return view('sport.index', [
            'sport' => $sport->nom,
            'liste' => $liste
        ]);
    }
}
