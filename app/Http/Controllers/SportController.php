<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Controllers;

use App\Article;
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

    /**
     * Page d'accueil du sport
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        Log::info(" -------- Controller Sport : index -------- ");
        $sport = $request->sport;
        $calendriers = Journee::calendriersPageSport($sport, 'index');

        $indexArticles = $sport->articles
            ->where('valide', 1)
            ->where('pivot.visible', 1)
            ->where('fil_actu', '!=', 1)
            // ->sortByDesc('pivot.priorite')
            ->sortByDesc('created_at');

        $filActualites = Article::filActu($sport);

        $articles = collect();
        foreach ($indexArticles as $id => $article)
            $articles[] = article($article->uniqid);

         $articlesView = view('article.render', ['articles' => $articles])->render();

        return view('sport.index', [
            'sport' => $sport,
            'resultats' => $calendriers['resultats'] ? [$sport->nom => $calendriers['resultats']] : [],
            'prochains' => $calendriers['prochains'] ? [$sport->nom => $calendriers['prochains']] : [],
            'articles' => $articlesView,
            'filActualites' => $filActualites,
        ]);
    }
}
