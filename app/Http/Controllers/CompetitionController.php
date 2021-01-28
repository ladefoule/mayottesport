<?php

/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Controllers;

use App\Saison;
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
        $sport = $request->sport;

        // Recherche de la dernière saison de la compétition
        $saison = $competition->saisons()->orderBy('annee_debut', 'desc')->first();

        if($saison){
            $journeesSuivantes = $saison->journees()->where('date', '>=', date('Y-m-d'))->orderBy('date')->limit(2)->get();
            foreach ($journeesSuivantes as $key => $journee){
                $render = journee($journee->id)->render;
                $prochains[] = $render;
                if($key == 0)
                    $prochaineJourneeRender = $render;
            }

            $journeesPassees = $saison->journees()->where('date', '<', date('Y-m-d'))->orderBy('date', 'desc')->limit(2)->get();
            foreach ($journeesPassees as $key => $journee){
                $render = journee($journee->id)->render;
                $resultats[] = $render;
                if($key == 0)
                    $derniereJourneeRender = $render;
            }

            if($competition->type == 1) // Type compétition
               $classement = saison($saison->id)['classement'];
        }

        
        $articles = $request->articles;
        if($articles)
            $articlesView = view('article.render', ['articles' => $articles->slice(0, 5), 'affichage' => 'card'])->render();

        return view('competition.index', [
            'competition' => $competition,
            'sport' => $sport,
            'articles' => $articlesView ?? [],
            'resultats' => $resultats ?? [],
            'prochains' => $prochains ?? [],
            'derniereJourneeRender' => $derniereJourneeRender ?? '',
            'prochaineJourneeRender' => $prochaineJourneeRender ?? '',
            'classement' => $classement ?? [],
        ]);
    }

    public function classement(Request $request)
    {
        Log::info(" -------- Controller Competition : classement -------- ");
        $saison = $request->saison;
        $sport = $request->sport;

        $competition = index('competitions')[$saison->competition_id];
        $annee = annee($saison->annee_debut, $saison->annee_fin, '/');
        $title = $sport->nom . ' - Classement ' . Str::lower($competition->nom_complet) . ' ' . $annee;
        $h1 = 'Classement ' . Str::lower($competition->nom) . ' ' . $annee;

        $classement = saison($saison->id)['classement'];
        return view('competition.classement-' . Str::slug($sport->nom), [
            'classement' => $classement,
            'title' => $title,
            'h1' => $h1
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function actualite(Request $request)
    {
        Log::info(" -------- Controller Competition : index -------- ");
        $competition = $request->competition;
        $sport = $request->sport;
        
        $articles = $request->articles;
        if($articles)
            $articlesView = view('article.render', ['articles' => $articles])->render();

        return view('competition.actualite', [
            'competition' => $competition,
            'sport' => $sport,
            'articles' => $articlesView ?? [],
        ]);
    }

    /**
     * Accès à la page calendrier/résultat d'une compétition
     *
     * @param Request $request
     * @return \Illuminate\View\View|void
     */
    public function resultats(Request $request)
    {
        Log::info(" -------- Controller Competition : resultats -------- ");
        $saison = $request->saison;
        $competition = $request->competition;
        $sport = $request->sport;
        $journees = index('journees')->where('saison_id', $saison->id)->sortBy('numero');

        if (!$journees)
            abort(404);

        // On recherche la dernière journée jouée pour l'afficher
        $journee = $journees->where('date', '<', date('Y-m-d'))->sortByDesc('date')->first();

        // Si pas de journée passée, alors on recherche la journée à venir
        if (!$journee)
            $journee = $journees->where('date', '>=', date('Y-m-d'))->sortBy('date')->first();

        $title = $sport->nom . ' - ' . $competition->nom_complet . ' ' . $saison->nom . ' - Calendrier et résultats';
        return view('competition.calendrier-resultats', [
            'calendrierJourneeHtml' => journee($journee->id)->render,
            'saison' => $saison,
            'journee' => $journee,
            'journees' => $journees,
            'title' => $title,
            'matches' => journee($journee->id)->matches,
        ]);
    }

    /**
     * Accès à la page du palmarès d'une compétition
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function champions(Request $request)
    {
        Log::info(" -------- Controller Competition : champions -------- ");
        $sport = $request->sport;
        $competition = $request->competition;
        // $saisons = Saison::whereCompetitionId($competition->id)->orderBy('annee_debut', 'desc')->get();
        $saisons = index('saisons')->where('competition_id', $competition->id)->sortByDesc('annee_debut');
        return view('competition.palmares', [
            'saisons' => $saisons,
            'title' => $sport->nom . ' - ' . $competition->nom_complet . ' - Le palmarès',
            'competition' => $competition
        ]);
    }

    /**
     * Traitement d'une requète Ajax qui retourne le calendrier complet (tous les matches) d'une journée
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|void
     */
    public function journeeRender(Request $request)
    {
        $journee = Journee::whereSaisonId($request['saison'])->whereNumero($request['journee'])->firstOrFail();
        return journee($journee->id)->render;
    }
}
