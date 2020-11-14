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
        $this->middleware(['sport', 'competition']);

        // $this->middleware('log')->only('index');

        // $this->middleware('subscribed')->except('store');
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

        $derniereJournee = $saison ? $saison->derniereJournee() : '';
        $prochaineJournee = $saison ? $saison->prochaineJournee() : '';
        $derniereJourneeHtml = $derniereJournee ? $derniereJournee->afficherCalendrier() : '';
        $prochaineJourneeHtml = $prochaineJournee ? $prochaineJournee->afficherCalendrier() : '';

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
                $classement = Saison::find($saison->id)->classement();
                $hrefClassement = route('competition.classement', [
                    'sport' => strToUrl($sport->nom),
                    'competition' => strToUrl($competition->nom)
                ]);
            }

            $variables['classement'] = $classement;
            $variables['hrefClassement'] = $hrefClassement;
        }

        return view('competition.index', $variables);
    }

    public function classement(Request $request)
    {
        Log::info(" -------- CompetitionController : classement -------- ");
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

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param string $sport
     * @param string $competition
     * @param string $journee
     * @return \Illuminate\View\View|void
     */
    public function journee(Request $request, $sport, $competition, $journee)
    {
        Log::info(" -------- CompetitionController : journee -------- ");
        $saison = $request->saison;
        $journees = $saison->journees;
        $journee = Journee::whereSaisonId($saison->id)->whereNumero($journee)->first();
        if($journee == null)
            abort(404);

        $journeePrecedente = ($journee->numero > 1) ? Journee::whereSaisonId($saison->id)->whereNumero($journee->numero - 1)->first() : '';
        $journeeSuivante = ($journee->numero < $saison->nb_journees) ? Journee::whereSaisonId($saison->id)->whereNumero($journee->numero + 1)->first() : '';

        return view('competition.journee', [
            'calendrierJourneeHtml' => $journee->afficherCalendrier(),
            'journee' => $journee,
            'journees' => $journees,
            'hrefJourneePrecedente' => $journeePrecedente ? $journeePrecedente->url() : '',
            'hrefJourneeSuivante' => $journeeSuivante ? $journeeSuivante->url() : ''
        ]);
    }
}
