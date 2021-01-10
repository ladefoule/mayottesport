<?php

/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Controllers;

use App\Journee;
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
        Log::info("Accès au controller Competition - Ip : " . request()->ip());
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
        Log::info(" -------- Controller Competition : index -------- ");
        $competition = $request->competition;
        $saison = $request->saison;
        $sport = $request->sport;

        // if ($saison) {
        //     $saisonInfosSup = saison($saison->id);
        //     $derniereJourneeId = $saisonInfosSup['derniere_journee_id'];
        //     $prochaineJourneeId = $saisonInfosSup['prochaine_journee_id'];
        //     $derniereJournee = $derniereJourneeId ? journee($derniereJourneeId)->render : '';
        //     $prochaineJournee = $prochaineJourneeId ? journee($prochaineJourneeId)->render : '';
        //     $journeesView = view('journee.competition-index', ['derniereJournee' => $derniereJournee, 'prochaineJournee' => $prochaineJournee])->render();
        // }
        $resultats = Journee::calendriersRender($sport->id, 1, $competition->id);
        $prochains = Journee::calendriersRender($sport->id, 2, $competition->id);

        $articles = $competition->articles;
        foreach ($articles as $key => $article)
            $articles[$key] = article($article->uniqid);
        
        $articlesView = view('article.render', ['articles' => $articles->slice(0, 5)])->render();

        return view('competition.index', [
            'competition' => $competition->nom,
            'sport' => $sport->nom,
            'articles' => $articlesView,
            'resultats' => $resultats ?? '',
            'prochains' => $prochains ?? '',
        ]);
    }

    public function classement(Request $request)
    {
        Log::info(" -------- Controller Competition : classement -------- ");
        $saison = $request->saison;
        $sport = Str::slug($request->sport->nom);
        $competition = Str::lower(index('competitions')[$saison->competition_id]->nom);
        $annee = annee($saison->annee_debut, $saison->annee_fin, '/');
        $title = 'Football - Classement ' . $competition . ' ' . $annee;
        $h1 = 'Classement ' . $competition . ' ' . $annee;

        $classement = saison($saison->id)['classement'];
        return view($sport . '.classement', [
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
        Log::info(" -------- Controller Competition : resultats -------- ");
        $saison = $request->saison;
        $journees = index('journees')->where('saison_id', $saison->id)->sortBy('numero');

        if (!$journees)
            abort(404);

        // On recherche la dernière journée jouée pour l'afficher
        $journee = $journees->where('date', '<', date('Y-m-d'))->sortByDesc('date')->first();

        // Si pas de journée passée, alors on recherche la journée à venir
        if (!$journee)
            $journee = $journees->where('date', '>=', date('Y-m-d'))->sortBy('date')->first();

        return view('competition.calendrier-resultats', [
            'calendrierJourneeHtml' => journee($journee->id)->render,
            'saison' => $saison,
            'journee' => $journee,
            'journees' => $journees,
            'matches' => journee($journee->id)->matches,
        ]);
    }

    public function champions(Request $request)
    {
        Log::info(" -------- Controller Competition : champions -------- ");
        $competition = $request->competition;
        $champions = index('saisons')->whereNotNull('vainqueur');
        return view('competition.palmares', [
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
