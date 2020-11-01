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
        $table = $request['table'];
        $crudTable = CrudTable::verifTable($table);
        if($crudTable == false){
            Log::info('Table non gérée ou introuvable : ' . $table);
            abort(404);
        }
        return $next($request);
    }
}
