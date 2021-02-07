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
        $this->middleware(['saison'])->only(['classementSaison', 'resultats']);
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
        // $saison = $competition->saisons()->orderBy('annee_debut', 'desc')->first();
        $saison = $request->derniereSaison;

        if($saison && $competition->type == 1) // Type compétition
            $classement = infos('saisons', $saison->id)['classement'];

        
        $articles = $request->articles;
        if($articles)
            $articlesView = view('article.render', ['articles' => $articles->slice(0, 5), 'affichage' => 'card'])->render();

        return view('competition.index', [
            'competition' => $competition,
            'sport' => $sport,
            'articles' => $articlesView ?? [],
            'resultats' => $request->resultats,
            'prochains' => $request->prochains,
            'derniereJourneeRender' => $request->resultats_main,
            'prochaineJourneeRender' => $request->prochains_main,
            'classement' => $classement ?? [],
        ]);
    }

    /**
     * Page classement des championnats
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function classement(Request $request)
    {
        Log::info(" -------- Controller Competition : classement -------- ");
        $derniereSaison = $request->derniereSaison;
        $competition = $request->competition;
        $sport = $request->sport;

        // On vérifie qu'il s'agit bien d'un championnat
        if($competition->type != 1)
            abort(404);
        
        $title = $sport->nom . ' - Classement ' . Str::lower($competition->nom_complet);
        $h1 = 'Classement ' . Str::lower($competition->nom);
        $classement = [];
        if($derniereSaison){
            $annee = annee($derniereSaison->annee_debut, $derniereSaison->annee_fin, '/');
            $title = $sport->nom . ' - Classement ' . Str::lower($competition->nom_complet) . ' ' . $annee;
            $h1 = 'Classement ' . Str::lower($competition->nom) . ' ' . $annee;

            $classement = saison($derniereSaison->id)['classement'];
        }
        return view('competition.classement-' . $sport->slug, [
            'classement' => $classement,
            'title' => $title,
            'h1' => $h1
        ]);
    }

    /**
     * Accès au classement d'une saison de championnat
     *
     * @param Request $request
     * @param string $sport
     * @param string $competition
     * @param string $annee
     * @return \Illuminate\View\View
     */
    public function classementSaison(Request $request, $sport, $competition, $annee)
    {
        Log::info(" -------- Controller Competition : classementSaison -------- ");
        $saison = $request->saison;
        $sport = $request->sport;
        $competition = $request->competition;

        // On vérifie qu'il s'agit bien d'un championnat
        if($competition->type != 1)
            abort(404);

        $annee = $saison->annee('/');
        $title = $sport->nom . ' - Classement ' . Str::lower($competition->nom_complet) . ' ' . $annee;
        $h1 = 'Classement ' . Str::lower($competition->nom) . ' ' . $annee;

        $classement = infos('saisons', $saison->id)['classement'];
        return view('competition.classement-' . $sport->slug, [
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
    public function resultats(Request $request, $sport, $competition, $annee, $journee)
    {
        Log::info(" -------- Controller Competition : resultats -------- ");
        $saison = $request->saison;
        $competition = $request->competition;
        $sport = $request->sport;

        $journees = index('journees')->where('saison_id', $saison->id);
        $journee = $journees->where('numero', $journee)->first();

        if (!$journee)
            abort(404);
            
        foreach ($journees as $key => $journee_)
            $journees[$key] = infos('journees', $journee_->id);
        

        $journeePrecedente = ($journee->numero > 1) ? $journees->where('numero', $journee->numero-1)->first() : '';
        $journeeSuivante = ($journee->numero < $saison->nb_journees) ? $journees->where('numero', $journee->numero+1)->first() : '';
        
        $hrefJourneePrecedente = ($journeePrecedente) ? $journeePrecedente->href : '';
        $hrefJourneeSuivante = ($journeeSuivante) ? $journeeSuivante->href : '';

        $title = $sport->nom . ' - ' . $competition->nom_complet . ' ' . $saison->nom . ' - Calendrier et résultats - ' . $journee->nom;
        return view('competition.calendrier-resultats', [
            'calendrierJourneeHtml' => infos('journees', $journee->id)->render_main,
            'hrefJourneePrecedente' => $hrefJourneePrecedente,
            'hrefJourneeSuivante' => $hrefJourneeSuivante,
            'saison' => $saison,
            'journeeActuelle' => $journee,
            'journees' => $journees,
            'title' => $title,
            'matches' => infos('journees', $journee->id)->matches,
            'competition' => $competition
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
            'competition' => $competition,
            'sport' => $sport,
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
        return infos('journees', $journee->id)->render;
    }
}
