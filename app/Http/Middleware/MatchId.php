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
        Log::info(" -------- Middleware MatchId -------- ");
        if (Validator::make(['uniqid' => $request->uniqid], ['uniqid' => 'alpha_dash|min:3'])->fails())
            abort(404);

        // La requète Eloquent est plus rapide
        $match = Match::whereUniqid($request->uniqid)->firstOrFail();
        // $match = index('matches')->where('uniqid', $request->uniqid)->first();

        $request->match = $match;
        return $next($request);
    }
}
