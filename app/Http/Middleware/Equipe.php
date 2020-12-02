<?php

namespace App\Http\Middleware;

use Closure;
use App\Equipe as EquipeModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Equipe
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
        Log::info(microtime(true));
        Log::info(" ---- Middleware Equipe ---- ");
        $rules = [
            'equipe' => 'alpha_dash|min:3',
            'uniqid' => 'alpha_dash'//exists:equipes,uniqid'
        ];

        $validator = Validator::make([
            'equipe' => $request->equipe,
            'uniqid' => $request->uniqid
        ], $rules);

        if ($validator->fails())
            abort(404);

        // $equipe = EquipeModel::whereUniqid($request->uniqid)->firstOrFail();
        $equipe = index('equipes')->firstWhere('uniqid', $request->uniqid);
        if (! $equipe || strToUrl($equipe->nom) != $request->equipe)
            abort(404);

        $request->equipe = $equipe;
        // $request->competitions = $competitions;
        Log::info(microtime(true));
        return $next($request);
    }
}
