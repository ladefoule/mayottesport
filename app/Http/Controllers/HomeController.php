<?php

namespace App\Http\Controllers;

use App\Sport;
use App\Article;
use App\Journee;
use App\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Page d'accueil
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        Log::info(" -------- Controller Home : index -------- ");
        $sports = Sport::where('home_position', '>=', 1)->orderBy('home_position')->get();
        foreach ($sports as $sport){
            $competitions = Competition::whereSportId($sport->id)->where('home_position', '>=', 1)->get();
            foreach ($competitions as $competition) {
                $saison = $competition->saisons()->orderBy('annee_debut', 'desc')->first();
                if($saison){
                    $derniereJournee = $saison->journees()->where('date', '<', date('Y-m-d'))->orderBy('date', 'desc')->first();
                    if ($derniereJournee)
                        $resultats[$sport->nom][] = [
                            'competition_nom' => $competition->nom,
                            'competition_href' => route('competition.index', ['sport' => $sport->slug, 'competition' => $competition->slug]),
                            'journee_render' => journee($derniereJournee->id)->render
                        ];
        
                    $prochaineJournee = $saison->journees()->where('date', '>=', date('Y-m-d'))->orderBy('date')->first();
                    if ($prochaineJournee)
                        $prochains[$sport->nom][] = [
                            'competition_nom' => $competition->nom,
                            'competition_href' => route('competition.index', ['sport' => $sport->slug, 'competition' => $competition->slug]),
                            'journee_render' => journee($prochaineJournee->id)->render
                        ];
                }
            }
        }
        
        $filActualites = Article::where('valide', 1)
            ->where('fil_actu', 1)
            ->where('home_visible', '>', 0)
            ->orderBy('home_priorite', 'desc')
            ->orderBy('created_at')
            ->get();

        $indexArticles = Article::where('valide', 1)
            ->where('home_visible', '>', 0)
            ->orderBy('home_priorite')
            ->get();

        $articles = collect();
        foreach ($indexArticles as $id => $article)
            $articles[] = article($article->uniqid);

         $articlesView = view('article.render', ['articles' => $articles])->render();

        return view('home', [
            'resultats' => $resultats ?? [],
            'prochains' => $prochains ?? [],
            'sports' => $sports,
            'articles' => $articlesView,
            'filActualites' => $filActualites,
        ]);
    }
}
