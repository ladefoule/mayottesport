<?php

namespace App\Http\Controllers;

use App\Sport;
use App\Saison;
use App\Journee;
use App\Competition;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompetitionController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info(" -------- CompetitionController : __construct -------- ");
        $this->middleware(['sport', 'competition'])->except('calendar');
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        Log::info(" -------- CompetitionController : index -------- ");
        $competition = $request->competition;
        $saison = $request->saison;
        $sport = $request->sport;


        $types = config('constant.type-competition');
        $type = $types[$competition->type][0];

        $derniereJournee = $saison ? $saison->lastDay() : '';
        $prochaineJournee = $saison ? $saison->nextDay() : '';
        $derniereJourneeHtml = $derniereJournee ? $derniereJournee->displayDay() : '';
        $prochaineJourneeHtml = $prochaineJournee ? $prochaineJournee->displayDay() : '';

        $variables = [
            'derniereJourneeHtml' => $derniereJourneeHtml,
            'prochaineJourneeHtml' => $prochaineJourneeHtml,
            'competition' => $competition->nom,
            'sport' => $sport->nom,
        ];

        if($type == 'championnat'){
            $classement = [];
            $hrefClassement = '';
            if($saison){
                $classement = Saison::find($saison->id)->ranking();
                $hrefClassement = route('competition.ranking', [
                    'sport' => strToUrl($sport->nom),
                    'competition' => strToUrl($competition->nom)
                ]);
            }

            $variables['classement'] = $classement;
            $variables['hrefClassement'] = $hrefClassement;
        }

        return view('competition.index', $variables);
    }

    public function ranking(Request $request)
    {
        Log::info(" -------- CompetitionController : ranking -------- ");
        // $competition = $request->competition;
        $saison = $request->saison;
        $sport = strToUrl($request->sport->nom);
        $title = $h1 = 'Football - Classement ' . Str::lower($saison->nom);

        $classement = $saison->ranking();
        return view($sport.'.ranking', [
            'classement' => $classement,
            // 'saison' => $saison,
            // 'sport' => $sport,
            'title' => $title,
            'h1' => $h1
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param string $sport
     * @param string $competition
     * @param string $journee
     * @return \Illuminate\View\View|void
     */
    public function day(Request $request, $sport, $competition, $journee)
    {
        Log::info(" -------- CompetitionController : day -------- ");
        $saison = $request->saison;
        $journees = $saison->journees;
        $currentDay = Journee::whereSaisonId($saison->id)->whereNumero($journee)->first();
        if($currentDay == null)
            abort(404);

        foreach ($journees as $journee)
            $journee->url = $journee->numero.'e-journee.html';

        $journeePrecedente = ($currentDay->numero > 1) ? Journee::whereSaisonId($saison->id)->whereNumero($currentDay->numero - 1)->first() : '';
        $journeeSuivante = ($currentDay->numero < $saison->nb_journees) ? Journee::whereSaisonId($saison->id)->whereNumero($currentDay->numero + 1)->first() : '';

        return view('competition.day', [
            'calendrierJourneeHtml' => $journee->displayDay(),
            'calendrier' => $journee->calendrier(),
            'currentDay' => $currentDay,
            'journees' => $journees,
            'hrefJourneePrecedente' => $journeePrecedente ? $journeePrecedente->numero.'e-journee.html' : '',
            'hrefJourneeSuivante' => $journeeSuivante ? $journeeSuivante->numero.'e-journee.html' : ''
        ]);
    }

    public function champions(Request $request)
    {
        Log::info(" -------- CompetitionController : champions -------- ");
        $competition = $request->competition;
        $champions = $competition->champions;
        return view('competition.champions', [
            'champions' => $champions,
            'competition' => $competition->nom
        ]);
    }

    public function calendar(Request $request)
    {
        $saisonId = $request['saison'];
        $journee = $request['journee'];
        $journee = Journee::whereSaisonId($saisonId)->whereNumero($journee)->firstOrFail();
        return $journee->calendrier();
    }
}
