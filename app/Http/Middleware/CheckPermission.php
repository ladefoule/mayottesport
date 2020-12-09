<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class CheckPermission
{
    /**
     * On contrôle si le membre connecté a bien les droits d'accès à cette ressource.
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
