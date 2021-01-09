<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Middleware;

use Closure;
use App\Article as ArticleModel;
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
        $article = ArticleModel::whereUniqid($request->uniqid)->firstOrFail();
        $request->article = $article;
        return $next($request);
    }
}
