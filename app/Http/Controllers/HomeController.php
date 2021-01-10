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
        foreach ($sports as $sport){
            $res = Journee::calendriersRender($sport->id);
            $proc = Journee::calendriersRender($sport->id, 2);
            if($res) $resultats[$sport->nom] = $res;
            if($proc) $prochains[$sport->nom] = $proc;
        }

        $indexArticles = index('articles')->sortByDesc('created_at')->where('valide', 1)->slice(0,5);
        $articles = collect();
        foreach ($indexArticles as $id => $article)
            $articles[] = article($article->uniqid);

         $articlesView = view('article.render', ['articles' => $articles])->render();
        //  $journeesView = view('journee.home', ['sports' => $sports])->render();

        return view('home', [
            'resultats' => $resultats,
            'prochains' => $prochains,
            'sports' => $sports,
            'articles' => $articlesView,
        ]);
    }
}
