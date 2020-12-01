<?php

namespace App\Http\Middleware;

use Closure;
use App\Match;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        Log::info(" ---- Middleware MatchId ---- ");
        if (Validator::make(['id' => $request->id], ['id' => 'alpha_dash|exists:matches,uniqid'])->fails())
            abort(404);

        $match = Match::whereUniqid($request->id)->firstOrFail();
        $request->match = $match;

        return $next($request);
    }
}
