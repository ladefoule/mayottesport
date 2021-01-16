<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Controllers;

use App\Journee;
use App\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SportController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info("Accès au controller Sport - Ip : " . request()->ip());
        $this->middleware('sport');
    }

    public function index(Request $request)
    {
        Log::info(" -------- Controller Sport : index -------- ");
        $sport = $request->sport;
        $competitions = Competition::whereSportId($sport->id)->where('home_position', '>=', 1)->get();
        foreach ($competitions as $competition) {
            $saison = $competition->saisons()->orderBy('annee_debut', 'desc')->first();
            if($saison){
                $derniereJournee = $saison->journees()->where('date', '<', date('Y-m-d'))->orderBy('date', 'desc')->first();
                if($derniereJournee)
                    $resultats[$sport->nom][] = [
                        'competition_nom' => $competition->nom,
                        'competition_href' => route('competition.index', ['sport' => $sport->slug, 'competition' => $competition->slug]),
                        'journee_render' => journee($derniereJournee->id)->render
                    ];
    
                $prochaineJournee = $saison->journees()->where('date', '>=', date('Y-m-d'))->orderBy('date')->first();
                if($prochaineJournee)
                    $prochains[$sport->nom][] = [
                        'competition_nom' => $competition->nom,
                        'competition_href' => route('competition.index', ['sport' => $sport->slug, 'competition' => $competition->slug]),
                        'journee_render' => journee($prochaineJournee->id)->render
                    ];
            }
        }

        $articles = $sport->articles()
            ->where('valide', 1)
            ->where('visible', 1)
            ->whereNull('fil_actu')->orWhere('fil_actu', 0)
            ->orderBy('priorite', 'desc')
            ->orderBy('created_at')
            ->limit(5)->get();

        $filActualites = $sport->articles()
            ->where('valide', 1)
            ->where('visible', 1)
            ->where('fil_actu', 1)
            ->orderBy('priorite', 'desc')
            ->orderBy('created_at')
            ->limit(10)->get();

        foreach ($articles as $key => $article)
            $articles[$key] = article($article->uniqid);

         $articlesView = view('article.render', ['articles' => $articles])->render();

        return view('sport.index', [
            'sport' => $sport,
            'resultats' => $resultats ?? [],
            'prochains' => $prochains ?? [],
            'articles' => $articlesView,
            'filActualites' => $filActualites,
        ]);
    }
}
