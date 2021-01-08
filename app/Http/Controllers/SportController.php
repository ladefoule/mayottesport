<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Controllers;

use App\Journee;
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
        $resultats = Journee::calendriersRender($sport->id);

        $indexArticles = index('articles')->sortByDesc('created_at')->where('valide', 1)->where('sport_id', $sport->id)->slice(0,5);
        $articles = collect();
        foreach ($indexArticles as $id => $article)
            $articles[] = article($article->uniqid);

         $articlesView = view('article.render', ['articles' => $articles])->render();

        return view('sport.index', [
            'sport' => $sport,
            'journees' => $resultats,
            'articles' => $articlesView
        ]);
    }
}
