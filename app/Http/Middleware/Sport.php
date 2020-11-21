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
        Log::info(" -------- Middleware Sport -------- ");
        $rules = [
            'sport' => 'alpha_dash|min:3'
        ];

        $validator = Validator::make([
            'sport' => $request->sport
        ], $rules);

        if ($validator->fails()) {
            abort(404);
        }

        $sport = SportModel::firstWhere('nom', 'like', $request->sport);
        if($sport == null)
            abort(404);

        // La liste des sports ainsi que les compétitions liées (pour les navbars et le footer)
        $request->sports = sportsEtCompetitions();
        $request->competitions = $sport->competitions;
        // $request->competitions = $request->sports[$sport->id]->competitions;

        // On remplace la chaine de caractère par l'objet
        $request->sport = $sport;

        return $next($request);
    }
}
