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
   /**
    * Rechargement des caches supprimés
    *
    * @param Request $request
    * @return mixed
    */
    public function reload(Request $request)
    {
        Log::info(" -------- Controller Cache : reload -------- ");
        $rules = [
            'id' => 'nullable|integer|min:0',
            'table' => 'required|min:3'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            abort(404);

         $table = $request['table'];
         $modele = '\App\\' . modelName($table);

        $id = $request['id'];

        // Si l'id vaut 0, alors on recharge juste le cache index de la table
        // Dans le cas par exemple de la suppression de l'instance
        if(! $id){
           index($table);
           return true;
        }

        $instance = $modele::findOrFail($id);

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

         // On supprime les caches des articles
         } else if ($table == 'articles') {
            $article = $instance;
            article($article->uniqid);
        }

        index($table);
    }
}
