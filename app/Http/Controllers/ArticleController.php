<?php

namespace App\Http\Controllers;

use App\Cache;
use App\Article;
use Illuminate\Support\Str;
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
        $this->middleware('article')->only(['show', 'showAdmin', 'updateForm', 'updateStore']);
    }

    public function createForm()
    {
        return view('article.create');
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

    public function showAdmin(Request $request, $uniqid)
    {
        $article = $request->article;
        return view('article.show', ['article' => $article]);
    }

    public function updateForm(Request $request, $uniqid)
    {
        $article = $request->article;
        return view('article.update', ['article' => $article]);
    }

    public function updateStore(Request $request, $uniqid)
    {
        $article = Article::findOrFail($request->article->id);
        $rules = Article::rules($article)['rules'];
        $data = Validator::make($request->all(), $rules)->validate();
        $article->update($data);

        Cache::forget('index-articles');
        Cache::forget('indexcrud-articles');

        return redirect()->route('article.show.admin', ['uniqid' => $article->uniqid]);
    }
}
