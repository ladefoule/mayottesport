<?php

namespace App\Http\Middleware;

use Closure;
use App\CrudTable;
use Illuminate\Support\Facades\Log;

class VerifTableCrud
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
        Log::info(" -------- Middleware VerifTableCrud -------- ");
        $table = $request['table'];
        $crudTable = false;
        $navbarCrudTables = CrudTable::navbarCrudTables();
        foreach ($navbarCrudTables as $table_)
            if(array_search($table, $table_))
                $crudTable = CrudTable::findOrFail($table_['id']);

        if(! $crudTable){
            Log::info('Table non gérée ou introuvable : ' . $table);
            abort(404);
        }
        $request['crudTable'] = $crudTable;
        return $next($request);
    }
}
