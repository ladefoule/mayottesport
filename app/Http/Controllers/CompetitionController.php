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
        $this->middleware(['sport', 'competition'])->except('journeeRender');
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
        $derniereJournee = $derniereJournee ? $derniereJournee->journeeRender() : '';
        $prochaineJournee = $prochaineJournee ? $prochaineJournee->journeeRender() : '';

        $variables = [
            'derniereJournee' => $derniereJournee,
            'prochaineJournee' => $prochaineJournee,
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
        $saison = $request->saison;
        $sport = strToUrl($request->sport->nom);
        $competition = Str::lower(index('competitions')[$saison->competition_id]->nom);
        $title = 'Football - Classement ' . $competition . ' ' . Str::lower($saison->annee('/'));
        $h1 = 'Classement ' . $competition . ' ' . Str::lower($saison->annee('/'));

        $classement = $saison->classement();
        return view($sport.'.classement', [
            'classement' => $classement,
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
    public function resultats(Request $request)
    {
        Log::info(" -------- CompetitionController : resultats -------- ");
        $saison = $request->saison;
        $journees = $saison->journees;
        $journee = Journee::whereSaisonId($saison->id)->where('date', '<', date('Y-m-d'))->orderBy('date', 'desc')->first();
        if(! $journee)
            $journee = Journee::whereSaisonId($saison->id)->where('date', '>=', date('Y-m-d'))->orderBy('date')->first();
        if(! $journee)
            abort(404);

        return view('competition.calendrier-resultats', [
            'calendrierJourneeHtml' => $journee->journeeRender(),
            'saison' => $saison,
            'journee' => $journee,
            'journees' => $journees,
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

    public function journeeRender(Request $request)
    {
        $journee = Journee::whereSaisonId($request['saison'])->whereNumero($request['journee'])->firstOrFail();
        return $journee->journeeRender();
    }
}
