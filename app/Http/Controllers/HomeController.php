<?php

namespace App\Http\Controllers;

use App\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Page d'accueil
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        Log::info(" -------- HomeController : index -------- ");
        $sports = sportsEtCompetitions();
        $request->sports = $sports;
        foreach ($sports as $sport) {
            $competitions = $sport->competitions;
            $sport->competitions = $competitions;
            $liste = [];
            foreach ($competitions as $competition) {
                $saison = $competition->saisons->firstWhere('finie', '!=', 1); // On recherche s'il y a une saison en cours
                if($saison){
                    $journee = $saison->derniereJournee();
                    if(! $journee) $journee = $saison->prochaineJournee();
                    if($journee){
                        $classement = '';
                        if($competition->type == 1)
                            $classement = $saison->classementSimpleRender();

                        $liste[] = [
                            'nom' => $competition->nom,
                            'journee' => $journee->journeeRender(),
                            'classement' => $classement
                        ];
                    }
                }
            }
            $sport->liste = $liste;
        }
        // dd($request->sports);
        return view('home', [
            'sports' => $sports
        ]);
    }
}
