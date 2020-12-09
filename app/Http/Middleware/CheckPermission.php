<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param mixed $permissions
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        Log::info(" -------- Middleware CheckPermission -------- ");
        $roles = explode('|', $permissions);

        // S'il n'y a pas encore de connexion
        if (auth()->user() == null)
            abort(404);

        $userRole = index('roles')[auth()->user()->role_id]->nom;

        if(in_array($userRole, $roles))
            return $next($request);

        abort(404);
    }
}
