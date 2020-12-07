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
        Log::info(" -------- Controller Cache : reloadCrud -------- ");
        $rules = [
            'instance_id' => 'required|integer|min:1',
            'crud_table_id' => 'required|integer|min:1|exists:crud_tables,id'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            abort(404);

        $id = $request['instance_id'];
        $crudTable = CrudTable::findOrFail($request['crud_table_id']);

        $table = $crudTable->nom;
        $modele = '\App\\' . modelName($crudTable->nom);

        $instance = $modele::findOrFail($id);
        $tableKebabCase = str_replace('_', '-' , $table);

        // On recharge le cache classement si on effectue une modif sur les tables :
        // matches, journées ou saisons qui risquent d'impacter le classement
        if(in_array($table, ['matches', 'journees', 'baremes', 'saisons'])){
            if($table == 'matches'){
                $match = $instance;
                $journee = $match->journee;
                $saison = $journee->saison;
            }else if($table == 'journees'){
                $journee = $instance;
                $saison = $journee->saison;
            }else if($table == 'baremes'){
                $bareme = $instance;
                $saisons = $bareme->saisons;

                // Rechargement de tous les caches saisons liés au barème
                foreach ($saisons as $saisonTemp) {
                    $cacheSaison = "saison-".$saisonTemp->id;
                    Cache::forget($cacheSaison);
                    saison($saisonTemp->id);
                }
            }else
                $saison = $instance;

            // Rechargement du cache qui contient les infos sur le match : urls, fanions, scores, etc...
            if(isset($match)){
                $cacheMatch = "match-".$match->uniqid;
                Cache::forget($cacheMatch);
                match($match->uniqid);
            }

            // Rechargement du cache qui contient les infos sur la journée : render, matches, etc...
            if(isset($journee)){
                $cacheJournee = "journee-".$journee->id;
                Cache::forget($cacheJournee);
                journee($journee->id);
            }

            // Rechargement du cache qui contient les infos sur la saison : classement, render classement simplifié, etc...
            if(isset($saison)){
                $cacheSaison = "saison-".$saison->id;
                Cache::forget($cacheSaison);
                saison($saison->id);
            }
        }

        // On recharge les caches 'attributs visibles' de la table si on effetue une modif dans les tables gestion CRUD
        if($table == 'crud_tables' || $table == 'crud_attributs' || $table == 'crud_attribut_infos'){
            Log::info("Opération effectuée dans la gestion du Crud");
            // Log::info("User : " . Auth::user()->email);
            if($table == 'crud_attribut_infos')
                $crudTableCible = $instance->crudAttribut->crudTable;
            else if($table == 'crud_attributs')
                $crudTableCible = $instance->crudTable;
            else
                $crudTableCible = $instance;


            $table = $crudTableCible->nom;
            Cache::forget("attributs-visibles-$table-index");
            $crudTableCible->listeAttributsVisibles();

            Cache::forget("attributs-visibles-$table-create");
            $crudTableCible->listeAttributsVisibles('create');

            Cache::forget("attributs-visibles-$table-show");
            $crudTableCible->listeAttributsVisibles('show');
        }

        Cache::forget("index-$tableKebabCase");
        $crudTable->index();
        Cache::forget("indexcrud-$tableKebabCase");
        $crudTable->indexCrud();

        // On recharge les caches 'index' qui utilisent les données de cette table dans leur attribut nom ou crud_name
        refreshCachesLies($tableKebabCase);
    }
}
