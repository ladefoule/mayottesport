<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Article
{
    /**
     * Vérification du nom de l'équipe et de son uniqid.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info(" -------- Middleware Article -------- ");
        $rules = [
            // 'equipe' => 'alpha_dash|min:3',
            'uniqid' => 'alpha_dash|size:13'
        ];

        $validator = Validator::make([
            // 'equipe' => $request->equipe,
            'uniqid' => $request->uniqid
        ], $rules);

        if ($validator->fails())
            abort(404);


        // $article = Article::whereUniqid($request->uniqid)->firstOrFail();
        $article = index('articles')->firstWhere('uniqid', $request->uniqid);
        if (! $article /* || Str::slug($article->nom) != $request->article */)
            abort(404);

        $request->article = $article;
        return $next($request);
    }
}
