<?php

namespace App\Http\Controllers;

use App\Cache;
use App\Article;
use App\Journee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\ProcessCrudTable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info("Accès au controller Article - Ip : " . request()->ip());
        $this->middleware('sport')->only(['showSport']);
        $this->middleware('article')->only(['show', 'showSport', 'showAdmin', 'updateForm', 'updatePost']);
    }

    public function createForm()
    {
        $sports = index('sports')->sortBy('nom');
        return view('article.create', [
            'images' => imagesList(),
            'sports' => $sports
        ]);
    }

    public function createPost(Request $request)
    {
        $rules = Article::rules()['rules'];
        $request['uniqid'] = uniqid();
        $request['valide'] = $request->has('valide');
        $request['user_id'] = Auth::id();
        $request['slug'] = Str::slug($request['titre']);
        $data = Validator::make($request->all(), $rules)->validate();
        $article = Article::create($data);

        forgetCaches('articles', $article);
        ProcessCrudTable::dispatch('articles', $article->id);
        return redirect()->route('article.show.admin', ['uniqid' => $article->uniqid]);
    }

    public function show(Request $request, $uniqid, $titre)
    {
        $article = $request->article;
        if (! $article->valide)
            abort(404);

        foreach (index('sports') as $sport){
            $res = Journee::calendriersRender(['sport_id' => $sport->id, 'categorie' => '-1', 'position' => 'home']);
            $proc = Journee::calendriersRender(['sport_id' => $sport->id, 'categorie' => '+1', 'position' => 'home']);
            if($res) $resultats[$sport->nom] = $res;
            if($proc) $prochains[$sport->nom] = $proc;
        }

        return view('article.show', [
            'article' => article($article->uniqid), 
            'resultats' => $resultats,
            'prochains' => $prochains
        ]);
    }

    public function showSport(Request $request, $sport, $uniqid, $titre)
    {
        $article = $request->article;
        $sport = $request->sport;
        if (!$article->valide)
            abort(404);

        $res = Journee::calendriersRender(['sport_id' => $article->sport_id, 'categorie' => '-1', 'position' => 'index']);
        $proc = Journee::calendriersRender(['sport_id' => $article->sport_id, 'categorie' => '+1', 'position' => 'index']);

        $resultats = $res ? [$sport->nom => $res] : [];
        $prochains = $proc ? [$sport->nom => $proc] : [];

        return view('article.show', [
            'article' => article($article->uniqid), 
            'resultats' => $resultats,
            'prochains' => $prochains
        ]);
    }

    public function showAdmin(Request $request, $uniqid)
    {
        $article = $request->article;
        return view('article.show-admin', ['article' => article($article->uniqid), 'admin' => 'admin']);
    }

    public function updateForm(Request $request, $uniqid)
    {
        $article = $request->article;
        $sports = index('sports')->sortBy('nom');

        return view('article.update', [
            'article' => $article,
            'images' => imagesList(),
            'sports' => $sports
        ]);
    }

    public function updatePost(Request $request, $uniqid)
    {
        $article = Article::findOrFail($request->article->id);
        $request['valide'] = $article->valide;
        $request['user_id'] = $article->user_id;
        $request['uniqid'] = $article->uniqid;
        $rules = Article::rules($article)['rules'];
        $data = Validator::make($request->all(), $rules)->validate();
        $article->update($data);

        forgetCaches('articles', $article);
        ProcessCrudTable::dispatch('articles', $article->id);
        return redirect()->route('article.show.admin', [
            'uniqid' => $article->uniqid,
        ]);
    }

    public function selectForm()
    {
        $articles = index('articles');
        return view('article.select', [
            'articles' => $articles
        ]);
    }

    public function selectPost(Request $request)
    {
        $rules = [
            'uniqid' => 'required|exists:articles,uniqid'
        ];
        $request = Validator::make($request->all(), $rules)->validate();
        $article = Article::whereUniqid($request['uniqid'])->firstOrFail();
        return redirect()->route('article.update', [
            'uniqid' => $article->uniqid,
        ]);
    }
}
