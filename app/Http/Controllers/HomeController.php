<?php

namespace App\Http\Controllers;

use App\Journee;
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
        $sports = index('sports')->where('home_position', '>=', 1)->sortBy('home_position');
        foreach ($sports as $sport) {
            // $competitions = index('competitions')->where('sport_id', $sport->id)->where('home_position', '>=', 1)->sortBy('home_position');
            // $listeDesJournees = [];
            // foreach ($competitions as $competition) {
            //     // $saison = Saison::whereCompetitionId($competition->id)->firstWhere('finie', '!=', 1); // On recherche s'il y a une saison en cours
            //     $saison = index('saisons')->where('competition_id', $competition->id)->where('finie', '!=', 1)->first();
            //     if($saison){
            //         $saison = saison($saison->id);
            //         $journeeId = $saison['derniere_journee_id'] != '' ? $saison['derniere_journee_id'] : $saison['prochaine_journee_id'];                    // $journeeId = $saison->derniereJourneeId() ?? $saison->prochaineJourneeId();
            //         if($journeeId)
            //             $listeDesJournees[] = collect([
            //                 'competition_nom' => $competition->nom,
            //                 'journee_render' => journee($journeeId)->render,
            //             ]);
            //     }
            // }
            $sport->journees = Journee::calendriersRender($sport->id);
        }

        $indexArticles = index('articles')->sortByDesc('created_at')->where('valide', 1)->slice(0,5);
        $articles = collect();
        foreach ($indexArticles as $id => $article)
            $articles[] = article($article->uniqid);

         $articlesView = view('article.render', ['articles' => $articles])->render();
        //  $journeesView = view('journee.home', ['sports' => $sports])->render();

        return view('home', [
            // 'journees' => $journeesView,
            'sports' => $sports,
            'articles' => $articlesView,
        ]);
    }
}
