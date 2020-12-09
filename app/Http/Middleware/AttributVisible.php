<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class AttributVisible
{
    /**
     * On vérifie s'il y a au moins 1 attribut à afficher (en fonction de la route) dans la table demandée.
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
        if(! in_array($route, ['crud.index', 'crud.show', 'crud.update', 'crud.create'])){
            Log::info("La route '$route' n'est pas gérée par le middleware AttributVisible");
            abort(404);
        }

        $action = explode('.', $route)[1]; // les différentes routes : crud.create / crud.show / crud.update / crud.index

        $listeAttributsVisibles = $crudTable->listeAttributsVisibles($action);
        if(! $listeAttributsVisibles){
            Log::info('Aucun attribut à afficher pour la table : ' . $crudTable->nom);
            abort(404);
        }

        // On insère la liste dans la requète
        $request->listeAttributsVisibles = $listeAttributsVisibles;
        return $next($request);
    }
}
