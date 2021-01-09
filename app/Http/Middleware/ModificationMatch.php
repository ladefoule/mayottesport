<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ModificationMatch
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
        Log::info(" -------- Middleware ModificationMatch -------- ");
        $match = $request->match; // Récupéré depuis le moddleware MatchUniqid
        $user = Auth::check() ? index('users')[Auth::id()] : '';

        $accesModifResultat = accesModifResultat($match, $user);
        $accesModifHoraire = accesModifHoraire($match, $user);

        $routeName = $request->route()->getName();

        // Routes de modification du résultat d'un match
        if(in_array($routeName, ['competition.match.resultat', 'competition.match.resultat.post']) && ! $accesModifResultat){
            abort(404);

        // Routes de modification de l'horaire d'un match
        }else if(in_array($routeName, ['competition.match.horaire', 'competition.match.horaire.post']) && ! $accesModifHoraire){
            abort(404);

        // Routes d'accès à la page match'
        }else if(in_array($routeName, ['competition.match'])){
            $request->accesModifHoraire = $accesModifHoraire;

            // On affiche le bouton de modification si le match n'est pas bloqué même s'il n'y a pas de membre connecté
            $request->accesModifResultat = ($user && !$match->bloque) ? $accesModifResultat : true;
        }

        return $next($request);
    }
}
