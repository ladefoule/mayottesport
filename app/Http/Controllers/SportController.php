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
        $res = Journee::calendriersRender(['sport_id' => $sport->id, 'categorie' => '-1', 'position' => 'index']);
        $proc = Journee::calendriersRender(['sport_id' => $sport->id, 'categorie' => '+1', 'position' => 'index']);

        $resultats = $res ? [$sport->nom => $res] : [];
        $prochains = $proc ? [$sport->nom => $proc] : [];

        $articles = $sport->articles;
        foreach ($articles as $key => $article)
            $articles[$key] = article($article->uniqid);

         $articlesView = view('article.render', ['articles' => $articles])->render();

        return view('sport.index', [
            'sport' => $sport,
            'resultats' => $resultats,
            'prochains' => $prochains,
            'articles' => $articlesView
        ]);
    }
}
