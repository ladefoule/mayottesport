<?php

namespace App\Http\Controllers;

use App\Cache;
use App\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $this->middleware('article')->only(['show', 'showSport', 'showAdmin', 'updateForm', 'updateStore']);
    }

    public function createForm()
    {
        $sports = index('sports')->sortBy('nom');
        return view('article.create', [
            'sports' => $sports
        ]);
    }

    public function createStore(Request $request)
    {
        $rules = Article::rules()['rules'];
        $request['uniqid'] = uniqid();
        $request = Validator::make($request->all(), $rules)->validate();
        $article = Article::create($request);

        Cache::forget('index-articles');
        Cache::forget('indexcrud-articles');

        return redirect()->route('article.show.admin', ['uniqid' => $article->uniqid]);
    }

    public function show(Request $request, $uniqid, $titre)
    {
        $article = $request->article;
        if(! $article->valide)
            abort(404);
        return view('article.show', ['article' => $article]);
    }

    public function showSport(Request $request, $sport, $uniqid, $titre)
    {
        $article = $request->article;
        if(! $article->valide)
            abort(404);
        return view('article.show', ['article' => $article]);
    }

    public function showAdmin(Request $request, $uniqid)
    {
        $article = $request->article;
        return view('article.show', ['article' => $article, 'admin' => 'admin']);
    }

    public function updateForm(Request $request, $uniqid)
    {
        $article = $request->article;
        $sports = index('sports')->sortBy('nom');
        return view('article.update', [
            'article' => $article,
            'sports' => $sports
        ]);
    }

    public function updateStore(Request $request, $uniqid)
    {
        $article = Article::findOrFail($request->article->id);
        $rules = Article::rules($article)['rules'];
        $data = Validator::make($request->all(), $rules)->validate();
        $article->update($data);

        Cache::forget('index-articles');
        Cache::forget('indexcrud-articles');

        return redirect()->route('article.show.admin', [
            'uniqid' => $article->uniqid,
        ]);
    }
}
