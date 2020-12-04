<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

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
        Log::info(" -------- Middleware AttributVisible -------- ");
        $crudTable = $request->crudTable; // Récupérer depuis le middleware VerifTableCrud
        $route = $request->route()->getName();
        $action = explode('.', $route)[1]; // les différentes routes : crud.create / crud.show / crud.update

        $listeAttributsVisibles = $crudTable->listeAttributsVisibles($action);
        if(! $listeAttributsVisibles && $action != 'index') // On autorise à afficher la liste même s'il n'y a pas d'attribut visible. On affiche le crud_name pour chaque élément.
            abort(404);

        $request->listeAttributsVisibles = $listeAttributsVisibles;
        return $next($request);
    }
}
