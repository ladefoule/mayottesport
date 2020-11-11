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
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('competition');

        // $this->middleware('log')->only('index');

        // $this->middleware('subscribed')->except('store');
    }

    public function index(Request $request, string $sport, string $competition)
    {
        $competition = $request->competition;
        $saison = $request->saison;
        $sport = $request->sport;

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
            'sport' => $sport->nom,
        ];

        if($type == 'championnat'){
            $classement = Saison::find($saison->id)->classement();
            $hrefClassement = route('competition.classement', [
                'sport' => strToUrl($sport->nom),
                'competition' => strToUrl($competition->nom)
            ]);

            $variables['classement'] = $classement;
            $variables['hrefClassement'] = $hrefClassement;
        }

        return view('competition.index', $variables);
    }

    public function classement(Request $request, string $sport, string $competition)
    {
        $competition = $request->competition;
        $saison = $request->saison;
        $sport = strToUrl($request->sport->nom);

        $classement = $saison->classement();
        return view($sport.'.classement', [
            'classement' => $classement,
            'saison' => $saison->nom,
            'sport' => $sport,
            'competition' => $competition->nom
        ]);
    }

    public function journee(Request $request, string $sport, string $competition, int $journee)
    {
        $competition = $request->competition;
        $competitionNom = $competition->nom;
        $saison = $request->saison;
        $journees = $saison->journees;
        $journee = Journee::whereSaisonId($saison->id)->whereNumero($journee)->firstOrFail();

        $sport = strToUrl($request->sport->nom);
        $competition = strToUrl($request->competition->nom);

        $journeePrecedente = ($journee->numero > 1) ? Journee::whereSaisonId($saison->id)->whereNumero($journee->numero - 1)->firstOrFail() : '';
        $journeeSuivante = ($journee->numero < $saison->nb_journees) ? Journee::whereSaisonId($saison->id)->whereNumero($journee->numero + 1)->firstOrFail() : '';

        // $hrefPrecedente = ($journee->numero > 1) ?
        // $hrefPrecedente = route('competition.journee', ['sport' => $sport, 'competition' => $competition, 'journee' => ])

        return view('competition.journee', [
            'calendrierJournee' => $journee->afficherCalendrier(),
            'journee' => $journee,
            'journees' => $journees,
            'sport' => $sport,
            'competition' => $competitionNom,
            'journeePrecedente' => $journeePrecedente,
            'journeeSuivante' => $journeeSuivante,
        ]);
    }
}
