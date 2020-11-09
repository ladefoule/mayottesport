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
        $derniereJournee = $saison->derniereJournee();
        $prochaineJournee = $saison->prochaineJournee();

        $types = config('constant.type-competition');
        $type = $types[$competition->type][0];

        $derniereJournee = $derniereJournee ? $derniereJournee->afficherCalendrier() : '';
        $prochaineJournee = $prochaineJournee ? $prochaineJournee->afficherCalendrier() : '';

        $variables = [
            'derniereJournee' => $derniereJournee,
            'prochaineJournee' => $prochaineJournee,
            'competition' => $competition->nom,
            'sport' => Str::lower($sport->nom)
        ];

        if($type == 'championnat'){
            $classement = Saison::find($saisonId)->classement();
            $hrefClassement = route('classement', ['sport' => strToUrl($sport->nom), 'competition' => strToUrl($competition->nom)]);

            $variables['classement'] = $classement;
            $variables['hrefClassement'] = $hrefClassement;
        }

        return view('competition.index', $variables);
    }

    public function classement(string $sport, string $competition)
    {
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
        $classement = $saison->classement();
        return view('football.classement', [
            'classement' => $classement,
            'saison' => $saison->nom,
            'sport' => strToUrl($sport),
            'competition' => $competition->nom
        ]);
    }
}
