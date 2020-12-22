<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Controllers;

use App\CrudTable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CacheController extends Controller
{
    public function reload(Request $request)
    {
        Log::info(" -------- Controller Cache : reload -------- ");
        $rules = [
            'id' => 'required|integer|min:1',
            'table' => 'required|min:3|exists:crud_tables,nom'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            abort(404);

        $id = $request['id'];
        $crudTable = CrudTable::whereNom($request['table'])->firstOrFail();

        $table = $crudTable->nom;
        $modele = '\App\\' . modelName($crudTable->nom);

        $instance = $modele::findOrFail($id);
        $tableSlug = Str::slug($table);

        // On recharge les caches directement liés au match, à la journée ou à la saison
        if(in_array($table, ['matches', 'journees', 'saisons'])){
            if($table == 'matches'){
                $match = $instance;
                $journee = $match->journee;
                $saison = $journee->saison;
            }else if($table == 'journees'){
                $journee = $instance;
                $saison = $journee->saison;
            }else
                $saison = $instance;

            if(isset($match))
                match($match->uniqid);

            if(isset($journee))
                journee($journee->id);

            if(isset($saison))
                saison($saison->id);

        // Suppression de tous les caches saisons liés au barème
        }else if(in_array($table, ['baremes'])){
            $bareme = $instance;
            $saisons = $bareme->saisons;

            foreach ($saisons as $saison)
                saison($saison->id);

        // On recharge les caches 'attributs visibles' de la table si on effetue une modif dans les tables gestion CRUD
        }else if(in_array($table, ['crud_tables', 'crud_attributs', 'crud_attribut_infos'])){
            Log::info("Opération effectuée dans la gestion du Crud");
            if($table == 'crud_attribut_infos')
                $crudTableCible = $instance->crudAttribut->crudTable;
            else if($table == 'crud_attributs')
                $crudTableCible = $instance->crudTable;
            else
                $crudTableCible = $instance;

            $crudTableCible->listeAttributsVisibles();
            $crudTableCible->listeAttributsVisibles('create');
            $crudTableCible->listeAttributsVisibles('show');
            $crudTableCible->indexCrud();
        }

        $crudTable->index();

        // On recharge les caches 'index' qui utilisent les données de cette table dans leur attribut nom ou crud_name
        if(! in_array($crudTable->nom, config('constant.tables-non-crudables'))){
            $crudTable->indexCrud();
            refreshCachesLies($tableSlug);
        }
    }
}
