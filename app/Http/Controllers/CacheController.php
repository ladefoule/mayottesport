<?php

namespace App\Http\Controllers;

use App\Cache;
use App\Saison;
use App\CrudTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CacheController extends Controller
{
    public function reloadCrud(Request $request)
    {
        Log::info(" -------- CacheController : reloadCrud -------- ");
        $rules = [
            'instance_id' => 'nullable|integer|min:1',
            'crud_table_id' => 'required|integer|min:1|exists:crud_tables,id'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            abort(404);

        $id = $request['instance_id'];
        $crudTable = CrudTable::findOrFail($request['crud_table_id']);

        $table = $crudTable->nom;
        $modele = '\App\\' . modelName($crudTable->nom);

        $instance = $modele::find($id);
        $tableKebabCase = str_replace('_', '-' , $table);

        // On recharge le cache classement si on effectue une modif sur les tables :
        // matches, journées ou saisons qui risquent d'impacter le classement
        if($instance && in_array($table, ['matches', 'journees', 'saisons'])){
            if($table == 'matches'){
                $match = $instance;
                $journee = $match->journee;
                $saison = $journee->saison;
            }else if($table == 'journees'){
                $journee = $instance;
                $saison = $journee->saison;
            }else
                $saison = $instance;

            if(isset($match)){
                $cacheMatch = "match-".$match->uniqid;
                Cache::forget($cacheMatch);
                Log::info("Rechargement du cache match-" . $match->uniqid);
                match($match->uniqid);
            }

            if(isset($journee)){
                $cacheJournee = "journee-".$journee->id;
                Cache::forget($cacheJournee);
                Log::info("Rechargement du cache journee-" . $journee->id);
                journee($journee->id);
            }

            $cacheSaison = "saison-".$saison->id;
            Cache::forget($cacheSaison);
            Log::info("Rechargement du cache saison-" . $saison->id);
            saison($saison->id);
        }

        // On recharge les caches 'attributs visibles' de la table si on effetue une modif dans les tables gestion CRUD
        if($instance && ($table == 'crud_tables' || $table == 'crud_attributs' || $table == 'crud_attribut_infos')){
            Log::info("Opération effectuée dans la gestion du Crud");
            Log::info("User : " . Auth::user()->email);
            if($table == 'crud_attribut_infos')
                $table = $instance->crudAttribut->crudTable->nom;
            else if($table == 'crud_attributs')
                $table = $instance->crudTable->nom;
            else
                $table = $instance->nom;


            Cache::forget("attributs-visibles-$tableKebabCase-index");
            Log::info("Rechargement du cache attributs-visibles-$tableKebabCase-index");
            $crudTable->listeAttributsVisibles();

            Cache::forget("attributs-visibles-$tableKebabCase-create");
            Log::info("Rechargement du cache attributs-visibles-$tableKebabCase-create");
            $crudTable->listeAttributsVisibles('create');

            Cache::forget("attributs-visibles-$tableKebabCase-show");
            Log::info("Rechargement du cache attributs-visibles-$tableKebabCase-show");
            $crudTable->listeAttributsVisibles('show');
        }

        Cache::forget("index-$tableKebabCase");
        Log::info("Rechargement du cache index-$tableKebabCase");
        $crudTable->index();

        // On recharge les caches qui utilisent les données de cette table dans leur attribut nom ou crud_name
        $cachesLies = config('constant.caches-lies')[$tableKebabCase] ?? [];
        foreach ($cachesLies as $cache){
            if($cache){
                Cache::forget('index-' . $cache);
                Log::info("Rechargement du cache index-$cache");
                $crudTable = CrudTable::where('nom', str_replace('-', '_', $cache))->firstOrFail()->index();
            }
        }
    }
}
