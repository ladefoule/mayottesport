<?php

namespace App\Http\Controllers;

use App\Cache;
use App\CrudTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CacheController extends Controller
{
    public function reloadCrud(Request $request, CrudTable $crudTable, $instance = null)
    {
        Log::info('ICI');
        return 2;
        $table = $crudTable->nom;

        // On supprime le cache classement si on effectue une modif sur les tables :
        // matches, journées ou saisons qui risquent d'impacter le classement
        if($instance && in_array($table, ['matches', 'journees', 'saisons'])){
            if($table == 'matches'){
                $match = $instance;
                $journee = index('journees')[$match->journee_id];
                $saison = index('saisons')[$journee->saison_id];
            }else if($table == 'journees'){
                $journee = $instance;
                $saison = index('saisons')[$journee->saison_id];
            }else
                $saison = $instance;

            $cacheClassement = "classement-".$saison->id;
            Cache::forget($cacheClassement);
        }

        if($table == 'crud_tables' || $table == 'crud_attributs' || $table == 'crud_attribut_infos'){
            Log::info("Opération effectuée dans la gestion du Crud");
            Log::info("User : " . Auth::user()->email);
            if($table == 'crud_attribut_infos')
                $table = $instance->crudAttribut->crudTable->nom;
            else if($table == 'crud_attributs')
                $table = $instance->crudTable->nom;
            else
                $table = $instance->nom;

            // La table sur laquelle on apporte des modifications
            $table = str_replace('_', '-' , $table);
            Cache::forget("attributs-visibles-$table-create");
            Cache::forget("attributs-visibles-$table-show");
            // Cache::forget('index-' . $table);

            // On supprime les caches des tables liées à la gestion du Crud
            Cache::forget("index-crud-tables");
            Cache::forget("index-crud-attributs");
            Cache::forget("index-crud-attribut-infos");
        }else{
            $cache = "index-$table";
            Cache::forget($cache);

            // On supprime les caches qui utilisent les données de cette table dans leur attribut nom ou crud_name
            $cachesLies = explode(',', $crudTable->caches_lies);
            foreach ($cachesLies as $cache)
                Cache::forget('index-' . $cache);
        }
    }
}
