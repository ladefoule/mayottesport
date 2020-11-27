<?php

namespace App\Http\Controllers;

use App\Sport;
use App\Saison;
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
        $sports = index('sports')->where('home_position', '>=', 1)->sortBy('home_position');
        foreach ($sports as $sport) {
            $competitions = index('competitions')->where('sport_id', $sport->id)->where('home_position', '>=', 1)->sortBy('home_position');
            $listeDesJournees = [];
            foreach ($competitions as $competition) {
                $saison = Saison::whereCompetitionId($competition->id)->firstWhere('finie', '!=', 1); // On recherche s'il y a une saison en cours
                if($saison){
                    $journee = $saison->derniereJournee() ?? $saison->prochaineJournee();
                    if($journee){
                        $classement = '';
                        if($competition->type == 1) // Championnat
                            $classement = $saison->classementSimpleRender();

                        $listeDesJournees[] = collect([
                            'competition_nom' => $competition->nom,
                            'journee_render' => $journee->journeeRender(),
                            'saison_classement' => $classement
                        ]);
                    }
                }
            }
            $sport->journees = $listeDesJournees;
        }
        // dd($sports);

        return view('home', [
            'sports' => $sports
        ]);
    }
}
