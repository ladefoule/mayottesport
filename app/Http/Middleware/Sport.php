<?php

namespace App\Http\Middleware;

use Closure;
use App\Sport as SportModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Sport
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
        Log::info(" ---- Middleware Sport ---- ");
        if (Validator::make(['sport' => $request->sport], ['sport' => 'alpha_dash|min:3'])->fails())
            abort(404);

        // On remplace la chaine de caractère par l'objet
        $sport = SportModel::where('nom', 'like', $request->sport)->firstOrFail();
        $request->sport = $sport;

        return $next($request);
    }
}
