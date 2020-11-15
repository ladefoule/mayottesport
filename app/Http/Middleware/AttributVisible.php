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
        $action = explode('.', $route)[1]; // les différentes routes : crud.create / crud.show / crud.update

        $listeAttributsVisibles = $crudTable->listeAttributsVisibles($action);
        if($listeAttributsVisibles == false)
            abort(404);

        $request['listeAttributsVisibles'] = $listeAttributsVisibles;
        return $next($request);
    }
}
