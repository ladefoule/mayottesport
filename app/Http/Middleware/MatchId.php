<?php

namespace App\Http\Middleware;

use Closure;
use App\Match;

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
        $match = Match::whereUniqid($request->id)->first();
        if(! $match)
            abort(404);

        $request->match = $match;

        return $next($request);
    }
}
