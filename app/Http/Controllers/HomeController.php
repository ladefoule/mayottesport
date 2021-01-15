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
        $sports = index('sports')->where('home_visible', '>=', 1)->sortBy('home_priorite');
        foreach ($sports as $sport){
            $res = Journee::calendriersRender(['sport_id' => $sport->id, 'categorie' => '-1', 'position' => 'home']);
            $proc = Journee::calendriersRender(['sport_id' => $sport->id, 'categorie' => '+1', 'position' => 'home']);
            if($res) $resultats[$sport->nom] = $res;
            if($proc) $prochains[$sport->nom] = $proc;
        }

        $indexArticles = index('articles')
            ->where('valide', 1)
            ->where('home_visible', '>', 0)
            ->sortBy('home_priorite')
            ->slice(0,5);

        $articles = collect();
        foreach ($indexArticles as $id => $article)
            $articles[] = article($article->uniqid);

         $articlesView = view('article.render', ['articles' => $articles])->render();

        return view('home', [
            'resultats' => $resultats ?? [],
            'prochains' => $prochains ?? [],
            'sports' => $sports,
            'articles' => $articlesView,
        ]);
    }
}
