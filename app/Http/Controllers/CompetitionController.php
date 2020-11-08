<?php

namespace App\Http\Controllers;

use App\Sport;
use App\Saison;
use App\Journee;
use App\Competition;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function index(string $sport, string $competition)
    {
        // dd(strToUrl('Coupe de mayotte'));
        $sport = Sport::where('nom', 'like', $sport)->firstOrFail();
        $competitions = $sport->competitions;
        $find = false;
        foreach($competitions as $compet)
            if(strToUrl($compet->nom) == $competition){
                $competition = $compet;
                $find = true;
            break;
            }
        if(!$find)
            abort(404);

        $saison = Saison::where('competition_id', $competition->id)->where('finie', '!=',1)->orWhereNull('finie')->firstOrFail();
        $saisonId = $saison->id;
        $derniereJournee = Journee::whereSaisonId($saisonId)->whereNumero(6)->first();
        $prochaineJournee = Journee::whereSaisonId($saisonId)->whereNumero(7)->first();
        // dd($derniereJournee);
        $classement = Saison::find($saisonId)->afficherClassementSimplifie(true);
        $derniereJournee = $derniereJournee->afficherCalendrier();
        $prochaineJournee = $prochaineJournee->afficherCalendrier();

        return view('competition.index', [
            'classement' => $classement,
            'derniereJournee' => $derniereJournee,
            'prochaineJournee' => $prochaineJournee,
            'competition' => $competition->nom,
            'sport' => Str::lower($sport)
        ]);


    }
}
