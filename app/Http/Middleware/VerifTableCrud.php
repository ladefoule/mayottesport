<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Middleware;

use Closure;
use App\CrudTable;
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
        $navbarCrudTables = CrudTable::navbarCrudTables();
        foreach ($navbarCrudTables as $table_)
            if(array_search($table, $table_)){
                $crudTable = CrudTable::findOrFail($table_['id']);
                // $crudTable = index('crud_tables')[$table_['id']];
                $request->layout = 'crud';
            }

        if(index('roles')[Auth::user()->role_id]->nom == 'superadmin'){
            $tables = config('constant.superadmin-tables');
            $position = array_search(str_replace('-', '_', $table), $tables);
            if($position !== false){
                $crudTable = CrudTable::whereNom($tables[$position])->firstOrFail();
                $request->layout = 'crud-superadmin';
            }
        }

        if(! $crudTable){
            Log::info('Table non gérée ou introuvable : ' . $table);
            abort(404);
        }
        $request->crudTable = $crudTable;
        return $next($request);
    }
}
