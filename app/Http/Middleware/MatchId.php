<?php

namespace App\Http\Middleware;

use Closure;
use App\Match;
use Illuminate\Support\Facades\Log;

class MatchId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info(" -------- Middleware MatchId -------- ");
        $match = Match::whereUniqid($request->id)->first();
        if(! $match)
            abort(404);

        $request->match = $match;

        return $next($request);
    }
}
