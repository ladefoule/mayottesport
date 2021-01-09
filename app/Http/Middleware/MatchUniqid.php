<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Middleware;

use Closure;
use App\Match;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MatchUniqid
{
    /**
     * On vérifie la valeur uniqid du match, saisie dans l'url.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info(" -------- Middleware MatchUniqid -------- ");
        $match = Match::whereUniqid($request->uniqid)->firstOrFail();

        $request->match = $match;
        return $next($request);
    }
}
