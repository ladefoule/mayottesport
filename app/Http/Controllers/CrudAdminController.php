<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Controllers;

use App\CrudTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CrudAdminController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info("Accès au controller CrudAdmin - Ip : " . request()->ip());
    }

    /**
     * Choix des tables sur lesquelles on peut effectuer du CRUD
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tables(Request $request)
    {
        $crudTables = CrudTable::orderBy('nom')->get();
        $request->layout = 'crud-superadmin';
        return view('admin.crud-superadmin.tables', ['crudTables' => $crudTables]);
    }

    /**
     * Suppression de tout le cache
     *
     * @return void
     */
    public function cacheFlush(Request $request)
    {
        if(Auth::user()->role->niveau < 40)
            abort(404);

        Cache::flush();
        Log::info('Suppression de tout le cache !');
    }

    /**
     * Mise à jour du choix sur les tables 'crudables'
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tablesPost(Request $request)
    {
        Log::info(" -------- Controller CrudAdmin : tablesPost -------- ");
        if(isset($request['maj'])){
            Log::info('Mise à jour de la liste des tables dans la table crud_tables');
            Log::info('Demande faite par : ' . Auth::user()->email);
            $tables = DB::select('SHOW TABLES');
            $tables = array_map('current', $tables);

            foreach ($tables as $table) {
                $crudTable = CrudTable::firstWhere('nom', $table);
                if($crudTable == null)
                    CrudTable::create(['nom' => $table]);
            }

            $crudTables = CrudTable::all();
            foreach ($crudTables as $crudTable) {
                // Si la table n'existe plus dans la base de données, on la supprime de crud_tables
                if(! in_array($crudTable->nom, $tables))
                    $crudTable->delete();
            }

        }

        $crudTables = CrudTable::all();
        foreach ($crudTables as $crudTable) {
            $crudable = $request->has($crudTable->id);
            $crudTable->update(['crudable' => $crudable]);
        }

        Cache::forget("crud-navbar-tables");
        return redirect()->route('crud-superadmin.tables');
    }

    // Todo :
    public function attributs()
    {
        $tables = CrudTable::orderBy('nom')->get();
        $crudTables = CrudTable::whereCrudable(1)->orderBy('nom')->get();
        $selects = config('constant');
        return view('admin.crud-superadmin.attributs', [
            'tables' => $tables,
            'crudTables' => $crudTables,
            'selects' => $selects
        ]);
    }

    public function attributsAjax(Request $request)
    {
        // Todo : Version 2 => proposer la liste des attributs qui sont dans la table pour la gestion du Crud
        return view('admin.crud-superadmin.ajax-attributs');
    }

    public function parametres()
    {
        // Todo : Gestion des infos supplémentaires des attributs en lien avec le fichier de configuration json/attributs-crud
    }
}
