<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

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
        Log::info(" -------- Controller Sport : __construct -------- ");
        $this->middleware('sport');
    }

    public function index(Request $request)
    {
        Log::info(" -------- Controller Sport : index -------- ");
        $sport = $request->sport;
        $competitions = index('competitions')->where('sport_id', $sport->id)->where('index_position', '>=', 1)->sortBy('index_position');;
        $journees = [];
        foreach ($competitions as $competition) {
            // $saison = Saison::whereCompetitionId($competition->id)->firstWhere('finie', '!=', 1); // On recherche s'il y a une saison en cours
            $saison = index('saisons')->where('competition_id', $competition->id)->where('finie', '!=', 1)->first();
            if($saison){
                $saison = saison($saison->id);
                $journeeId = $saison['derniere_journee_id'] != '' ? $saison['derniere_journee_id'] : $saison['prochaine_journee_id'];
                if($journeeId){
                    $classement = '';
                    if($competition->type == 1) // Championnat
                        $classement = $saison['classement_simple_render'];

                    $journees[] = collect([
                        'competition_nom' => $competition->nom,
                        'journee_render' => journee($journeeId)->render,
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
