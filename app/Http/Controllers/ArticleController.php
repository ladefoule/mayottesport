<?php

namespace App\Http\Controllers;

use App\Cache;
use App\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
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

        return redirect()->route('article.show', ['uniqid' => $article->uniqid, 'titre' => Str::slug($article->titre)]);
    }

    public function show(Request $request, $uniqid, $titre)
    {
        $article = Article::whereUniqid($uniqid)->firstOrFail();

        $texte = $article->texte;
        // foreach ($texte as $key => $value) {
        //     $texteR =
        // }
        // $texte
        // dd(json_encode($texte));

        return view('article.show', ['article' => $article, 'texte' => $texte]);
    }

    public function ajax(Request $request, $uniqid)
    {
        $article = Article::whereUniqid($uniqid)->firstOrFail();

        return $article->texte;
    }
}
