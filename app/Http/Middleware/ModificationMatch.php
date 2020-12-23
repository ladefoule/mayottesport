<?php

namespace App\Http\Middleware;

use Closure;
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
        $match = $request->match;
        $user = index('users')[Auth::id()];
        $niveauUser = index('roles')[$user->role_id]->niveau;

        $lastUser = $match->last_user;
        $niveauLastUser = $lastUser ? index('roles')[$lastUser->role_id]->niveau : 0;

        $routeName = $request->route()->getName();

        // Routes de modification de match
        if(in_array($routeName, ['competition.match.resultat', 'competition.match.resultat.store'])){
            if($match->bloque || $niveauLastUser > $niveauUser)
                abort(404);
        }
            // dd($routeName);
        return $next($request);
    }
}
