<?php

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
     * Choix des tables sur lesquelles on peut effectuer du CRUD
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tables()
    {
        $crudTables = CrudTable::orderBy('nom')->get();
        return view('admin.crud-admin.tables', ['crudTables' => $crudTables]);
    }

    /**
     * Mise à jour du choix sur les tables 'crudables'
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tablesPost(Request $request)
    {
        Log::info(" -------- CrudAdminController : tablesPost -------- ");
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
        }

        $crudTables = CrudTable::all();
        foreach ($crudTables as $crudTable) {
            $request[$crudTable->id] = $request->has($crudTable->id);
            if($request[$crudTable->id])
                $crudTable->update(['crudable' => 1]);
            else
                $crudTable->update(['crudable' => 0]);
        }

        Log::info('Suppressions des caches : "crud-navbar-tables-users-superadmin" et "crud-navbar-tables-users-admin"');
        Cache::forget("crud-navbar-tables-users-superadmin");
        Cache::forget("crud-navbar-tables-users-admin");
        return redirect()->route('crud-gestion.tables');
    }

    // Todo :
    public function attributs()
    {
        // $tables = CrudTable::orderBy('nom')->get();
        // $crudTables = CrudTable::whereCrudable(1)->orderBy('nom')->get();
        // return view('admin.crud-admin.attributs', [
        //     'tables' => $tables,
        //     'crudTables' => $crudTables
        // ]);
    }

    public function attributsAjax(Request $request)
    {
        // Todo : Version 2 => proposer la liste des attributs qui sont dans la table pour la gestion du Crud
        return view('admin.crud-admin.ajax-attributs');
    }

    public function parametres()
    {
        // Todo : Gestion des infos supplémentaires des attributs en lien avec le fichier de configuration json/attributs-crud
    }
}
