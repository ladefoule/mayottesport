<?php

namespace App\Http\Controllers;

use App\Cache;
use App\Article;
use App\Journee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\ProcessCacheReload;
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

    /**
     * Formulaire de création d'un article
     *
     * @return \Illuminate\View\View
     */
    public function createForm()
    {
        Log::info(" -------- Controller Article : createForm -------- ");
        $sports = index('sports')->sortBy('nom');
        return view('article.create', [
            'images' => imagesList(),
            'sports' => $sports
        ]);
    }

    /**
     * Traitement de la requète de création d'un article
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function createPost(Request $request)
    {
        Log::info(" -------- Controller Article : createPost -------- ");
        $rules = Article::rules()['rules'];
        $request['uniqid'] = uniqid();
        $request['valide'] = $request->has('valide');
        $request['user_id'] = Auth::id();
        $request['slug'] = Str::slug($request['titre']);
        $data = Validator::make($request->all(), $rules)->validate();
        $article = Article::create($data);

        forgetCaches('articles', $article);
        ProcessCacheReload::dispatch('articles', $article->id);
        return redirect()->route('article.show.admin', ['uniqid' => $article->uniqid]);
    }

    /**
     * Affichage d'un article sur le site
     *
     * @param Request $request
     * @param string $uniqid
     * @param string $titre
     * @return \Illuminate\View\View|void
     */
    public function show(Request $request, $uniqid, $titre)
    {
        Log::info(" -------- Controller Article : show -------- ");
        $article = $request->article;
        if (! $article->valide)
            abort(404);

        $calendriers = Journee::calendriersPageHome();

        $filActualites = Article::where('valide', 1)
            ->where('fil_actu', 1)
            ->where('home_visible', '>', 0)
            ->orderBy('home_priorite', 'desc')
            ->orderBy('created_at')
            ->get();

        return view('article.show', [
            'article' => article($article->uniqid), 
            'resultats' => $calendriers['resultats'],
            'prochains' => $calendriers['prochains'],
            'filActualites' => $filActualites
        ]);
    }

    public function showSport(Request $request, $sport, $uniqid, $titre)
    {
        $article = $request->article;
        if (!$article->valide)
            abort(404);

        $sport = $request->sport;
        $calendriers = Journee::calendriersPageSport($sport);

        // dd($calendriers);

        $filActualites = Article::filActu($sport);

        return view('article.show-sport', [
            'article' => article($article->uniqid), 
            'resultats' => [$sport->nom => $calendriers['resultats']],
            'prochains' => [$sport->nom => $calendriers['prochains']],
            'filActualites' => $filActualites,
        ]);
    }

    /**
     * Affichage d'un article sur le site back-office
     *
     * @param Request $request
     * @param string $uniqid
     * @return \Illuminate\View\View
     */
    public function showAdmin(Request $request, $uniqid)
    {
        Log::info(" -------- Controller Article : showAdmin -------- ");
        $article = $request->article;
        return view('article.show-admin', ['article' => article($article->uniqid), 'admin' => 'admin']);
    }

    /**
     * Formulaire de modification d'un article
     *
     * @param Request $request
     * @param string $uniqid
     * @return \Illuminate\View\View
     */
    public function updateForm(Request $request, $uniqid)
    {
        Log::info(" -------- Controller Article : updateForm -------- ");
        $article = $request->article;
        $sports = index('sports')->sortBy('nom');

        return view('article.update', [
            'article' => $article,
            'images' => imagesList(),
            'sports' => $sports
        ]);
    }

    /**
     * Traitement de la requète de modification d'un article
     *
     * @param Request $request
     * @param string $uniqid
     * @return void|\Illuminate\Http\RedirectResponse
     */
    public function updatePost(Request $request, $uniqid)
    {
        Log::info(" -------- Controller Article : updatePost -------- ");
        $article = Article::findOrFail($request->article->id);
        $request['valide'] = $article->valide;
        $request['user_id'] = $article->user_id;
        $request['uniqid'] = $article->uniqid;
        $request['slug'] = Str::slug($request['titre']);
        $rules = Article::rules($article)['rules'];
        $data = Validator::make($request->all(), $rules)->validate();
        $article->update($data);

        forgetCaches('articles', $article);
        ProcessCacheReload::dispatch('articles', $article->id);
        return redirect()->route('article.show.admin', [
            'uniqid' => $article->uniqid,
        ]);
    }

    /**
     * Formulaire de choix de l'article
     *
     * @return \Illuminate\View\View
     */
    public function selectForm()
    {
        Log::info(" -------- Controller Article : selectForm -------- ");
        $articles = index('articles');
        return view('article.select', [
            'articles' => $articles
        ]);
    }

    /**
     * Traitement de la requète sur la sélection de l'article à modifier
     *
     * @param Request $request
     * @return void|\Illuminate\Routing\Redirector
     */
    public function selectPost(Request $request)
    {
        Log::info(" -------- Controller Article : selectPost -------- ");
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
