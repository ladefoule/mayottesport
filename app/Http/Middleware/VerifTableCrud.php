<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VerifTableCrud
{
    /**
     * On vérifie si la table renseignée est 'crudable' ou non. On limite aussi l'accès à la gestion du CRUD au super-administrateurs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info(" -------- Middleware VerifTableCrud -------- ");
        if (Validator::make(['table' => $request->table], ['table' => 'alpha_dash'])->fails())
            abort(404);

        $table = $request['table'];
        $crudTable = false;

        $request->layout = 'crud';
        $crudTable = index('crud_tables')->where('nom', str_replace('-', '_', $table))
                                        ->where('crudable', 1)
                                        ->whereNotIn('nom', config('listes.tables-gestion-crud'))
                                        ->whereNotIn('nom', config('listes.tables-superadmin'))
                                        ->first();

        // Si on n'a pas trouvé la table et que c'est un superadmin qui est connecté, on recherche toutes les tables crudables
        if(! $crudTable && index('roles')[Auth::user()->role_id]->name == 'superadmin'){
            $crudTable = index('crud_tables')->where('nom', str_replace('-', '_', $table))
                                            ->where('crudable', 1)
                                            ->first();

            if($crudTable)
                $request->layout = 'crud-superadmin';
        }

        if(! $crudTable){
            Log::info('Table non gérée ou introuvable : ' . $table);
            abort(404);
        }

        $request->crudTable = $crudTable;
        return $next($request);
    }
}
