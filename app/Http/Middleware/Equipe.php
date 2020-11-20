<?php

namespace App\Http\Middleware;

use Closure;
use App\EquipeSaison;
use App\Equipe as EquipeModel;
use Illuminate\Support\Facades\DB;
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
        Log::info(" -------- Middleware Equipe -------- ");
        $rules = [
            'equipe' => 'alpha_dash|min:3',
            'id' => 'exists:equipes,uniqid'
        ];

        $validator = Validator::make([
            'equipe' => $request->equipe,
            'id' => $request->id
        ], $rules);

        if ($validator->fails())
            abort(404);

        $equipe = EquipeModel::whereUniqid($request->id)->first();
        if (! $equipe || strToUrl($equipe->nom) != $request->equipe)
            abort(404);

        $request->equipe = $equipe;
        // $request->competitions = $competitions;

        return $next($request);
    }
}
