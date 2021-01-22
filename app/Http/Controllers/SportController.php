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
        $calendriers = Journee::calendriersPageSport($sport);

        $articles = $sport->articles()
            ->where('valide', 1)
            ->where('visible', 1)
            ->whereNull('fil_actu')->orWhere('fil_actu', 0)
            ->orderBy('priorite', 'desc')
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->limit(5)->get();

        $filActualites = Article::filActu($sport);

        foreach ($articles as $key => $article)
            $articles[$key] = article($article->uniqid);

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
