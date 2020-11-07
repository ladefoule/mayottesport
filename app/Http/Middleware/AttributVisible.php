<?php

namespace App\Http\Middleware;

use Closure;
use App\CrudTable;
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
        $crudTable = $request['crudTable'];
        // $route = $request->getUri();
        // $request->getU
        // $route = route('crud.lister', ['table' => $request['table']]);
        // $route = $request->routeIs('crud.index');
        $route = $request->route()->getName();
        // dd($route);
        switch ($route) {
            case 'crud.index-ajax':
                $action = 'index';
                break;

            default:
                $action = explode('.', $route)[1];
                break;
        }
        // dd($action);
        // Log::info($route);
        $listeAttributsVisibles = $crudTable->listeAttributsVisibles($action);
        if($listeAttributsVisibles == false){
            // Log::info("Aucun attribut à afficher dans la page $action : " . $table);
            abort(404);
        }
        $request['listeAttributsVisibles'] = $listeAttributsVisibles;
        return $next($request);
    }
}
