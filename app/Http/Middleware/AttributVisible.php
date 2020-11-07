<?php

namespace App\Http\Middleware;

use Closure;

class AttributVisible
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
        $crudTable = $request['crudTable']; // Récupérer depuis le middleware VerifTableCrud
        $route = $request->route()->getName();

        switch ($route) {
            case 'crud.index-ajax':
                $action = 'index';
                break;

            default:
                $action = explode('.', $route)[1];
                break;
        }

        $listeAttributsVisibles = $crudTable->listeAttributsVisibles($action);
        if($listeAttributsVisibles == false)
            abort(404);

        $request['listeAttributsVisibles'] = $listeAttributsVisibles;
        return $next($request);
    }
}
