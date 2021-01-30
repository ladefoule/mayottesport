<?php

namespace App\Http\Controllers;

use App\Sport;
use App\Article;
use App\Journee;
use App\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Page d'accueil
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        Log::info(" -------- Controller Home : index -------- ");
        $calendriers = Journee::calendriersPageHome();
        $filActualites = Article::filActu();

        $indexArticles = Article::where('valide', 1)
            ->where('home_visible', 1)
            ->orderBy('home_priorite', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $articles = collect();
        foreach ($indexArticles as $id => $article)
            $articles[] = article($article->uniqid);

         $articlesView = view('article.render', ['articles' => $articles])->render();

        return view('home', [
            'resultats' => $calendriers['resultats'],
            'prochains' => $calendriers['prochains'],
            'articles' => $articlesView,
            'filActualites' => $filActualites,
        ]);
    }

    /**
     * Notre politique de confidentialité
     *
     * @return \Illuminate\View\View
     */
    function politique(){
        return view('rgpd');
    }

    /**
     * Le sitemap du site
     *
     * @return \Illuminate\View\View
     */
    function sitemap(){
        return view('sitemap');
    }
}
