<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $permission = explode('|', $permission);
        if(checkPermission($permission))
            return $next($request);

            return redirect()->route('accueil')->with('messageAccess', "Vous n'avez pas accès à cette page. Merci de contacter l'administrateur.");
        }
}
