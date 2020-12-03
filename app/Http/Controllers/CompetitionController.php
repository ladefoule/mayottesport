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
        $saisonInfosSup = saison($saison->id);
        $sport = $request->sport;
        // dd($saisonInfosSup);

        $types = config('constant.type-competition');
        $type = $types[$competition->type][0];

        $derniereJourneeId = $saisonInfosSup['derniere_journee_id'];
        $prochaineJourneeId = $saisonInfosSup['prochaine_journee_id'];
        $derniereJournee = $derniereJourneeId ? journee($derniereJourneeId)->render : '';
        $prochaineJournee = $prochaineJourneeId ? journee($prochaineJourneeId)->render : '';

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
                $classement = saison($saison->id)['classement'];
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
        $annee = annee($saison->annee_debut, $saison->annee_fin, '/');
        $title = 'Football - Classement ' . $competition . ' ' . $annee;
        $h1 = 'Classement ' . $competition . ' ' . $annee;

        $classement = saison($saison->id)['classement'];
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
        $journees = index('journees')->where('saison_id', $saison->id)->sortBy('numero');

        if(! $journees)
            abort(404);

        // On recherche la dernière journée jouée pour l'afficher
        $journee = $journees->where('date', '<', date('Y-m-d'))->sortByDesc('date')->first();

        // Si pas de journée passée, alors on recherche la journée à venir
        if(! $journee)
            $journee = $journees->where('date', '>=', date('Y-m-d'))->sortBy('date')->first();

        return view('competition.calendrier-resultats', [
            'calendrierJourneeHtml' => journee($journee->id)->render,
            'saison' => $saison,
            'journee' => $journee,
            'journees' => $journees,
        ]);
    }

    public function champions(Request $request)
    {
        Log::info(" -------- CompetitionController : champions -------- ");
        $competition = $request->competition;
        $champions = index('saisons')->whereNotNull('vainqueur');
        return view('competition.champions', [
            'champions' => $champions,
            'competition' => $competition->nom
        ]);
    }

    public function journeeRender(Request $request)
    {
        $journee = Journee::whereSaisonId($request['saison'])->whereNumero($request['journee'])->firstOrFail();
        return journee($journee->id)->render;
    }
}
