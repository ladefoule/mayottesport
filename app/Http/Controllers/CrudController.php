<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Controllers;

use App\Cache;
use App\CrudTable;
use App\Jobs\ProcessCrudTable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CrudController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info(" -------- Controller Crud : __construct -------- ");
        $this->middleware('verif-table-crud')->except('refreshCaches', 'indexAjax', 'deleteAjax');
        $this->middleware('attribut-visible')->only(['index', 'show', 'createForm', 'updateForm']);
    }

    /**
     * Affichage de tous les élements d'une table.
     *
     * @param string $table
     * @return \Illuminate\View\View|void
     */
    public function index(Request $request, $table)
    {
        Log::info(" -------- Controller Crud : index -------- ");
        $crudTable = $request->crudTable; // Récupérer depuis le middleware VerifTableCrud
        $tablePascalCase = Str::ucfirst(Str::camel($table));
        $h1 = $tablePascalCase;
        $title = 'CRUD - Lister : ' . $h1;

        $liste = $crudTable->indexCrud();
        $hrefs['create'] = route('crud.create', ['table' => $table]);
        $hrefs['delete-ajax'] = route('crud.delete-ajax', ['table' => $table]);
        $hrefs['index-ajax'] = route('crud.index-ajax', ['table' => $table]);

        return view('admin.crud.index', [
            'liste' => $liste,  // La liste des éléments de la classe
            'table' => $table, // Le nom de la table en kebab-case
            'h1' => $h1,
            'title' => $title,
            'hrefs' => $hrefs,
            'listeAttributsVisibles' => $request->listeAttributsVisibles
        ]);
    }

    /**
     * Affichage (du contenu de tbody) de la liste d'une table pour les requètes Ajax.
     *
     * @param string $table
     * @return \Illuminate\View\View|void
     */
    public function indexAjax($table)
    {
        Log::info(" -------- Controller Crud : indexAjax -------- ");
        $table = str_replace('-', '_', $table);
        $crudTable = CrudTable::whereNom($table)->firstOrFail();
        $liste = $crudTable->indexCrud();
        return view('admin.crud.index-ajax', [
            'liste' => $liste
        ]);
    }

    /**
     * Affichage d'un élement (vue)
     *
     * @param string $table
     * @param int $id
     * @return \Illuminate\View\View|void
     */
    public function show(Request $request, $table, $id)
    {
        Log::info(" -------- Controller Crud : show -------- ");
        $crudTable = $request->crudTable; // Récupérer depuis le middleware VerifTableCrud

        $tablePascalCase = Str::ucfirst(Str::camel($table));
        $h1 = $tablePascalCase;
        $title = 'CRUD - Voir : ' . $h1;

        $donnees = $crudTable->crud('show', $id);
        $hrefs['index'] = route('crud.index', ['table' => $table]);
        $hrefs['update'] = route('crud.update', ['table' => $table, 'id' => $id]);
        $hrefs['delete'] = route('crud.delete', ['table' => $table, 'id' => $id]);

        return view('admin.crud.show', [
            'donnees' => $donnees,
            'hrefs' => $hrefs,
            'h1' => $h1,
            'title' => $title
        ]);
    }

    /**
     * Ajout d'un élement dans la table $table
     *
     * @param string $table
     * @return \Illuminate\View\View|void
     */
    public function createForm(Request $request, $table)
    {
        Log::info(" -------- Controller Crud : create -------- ");
        $crudTable = $request->crudTable; // Récupérer depuis le middleware VerifTableCrud
        $tablePascalCase = Str::ucfirst(Str::camel($table));
        $h1 = $tablePascalCase . ' : Ajouter';
        $title = 'CRUD - Ajouter : ' . $tablePascalCase;

        $donnees = $crudTable->crud('create');
        $hrefs['index'] = route('crud.index', ['table' => $table]);

        return view('admin.crud.create', [
            'donnees' => $donnees,
            'h1' => $h1,
            'title' => $title,
            'hrefs' => $hrefs
        ]);
    }

    /**
     * Ajout d'un élement : traitement du POST
     *
     * @param Illuminate\Http\Request $request
     * @param string $table
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createStore(Request $request, $table)
    {
        Log::info(" -------- Controller Crud : createStore -------- ");
        $crudTable = $request->crudTable; // Récupérer depuis le middleware VerifTableCrud
        $modele = 'App\\'.modelName(str_replace('-', '_', $table));
        $rules = $modele::rules();

        $messages = $rules['messages'] ?? []; // On récupère éventuellement les messages associés
        $rules = $rules['rules']; // On récupère les règles de validations

        $request = Validator::make($request->all(), $rules, $messages)->validate();
        if(in_array($table, ['matches', 'equipes']))
            $request['uniqid'] = uniqid(); // On génére un uniqid pour les matches et les équipes

        // $instance = $modele::create($request);
        $instance = new $modele($request);
        $instance->save();

        // Todo : Erreur lors de la création d'un élément de la table equipe_saison, impossible de récupérer l'id car table pivot ?
        // dd($instance);

        $this::refreshCaches($crudTable, $instance);
        return redirect()->route('crud.show', ['table' => $table, 'id' => $instance->id]);
    }

    /**
     * Modification de l'élement qui a l'id $id de la table $table
     *
     * @param string $table
     * @param int $id
     * @return \Illuminate\View\View|void
     */
    public function updateForm(Request $request, $table, $id)
    {
        Log::info(" -------- Controller Crud : update -------- ");
        $crudTable = $request->crudTable; // Récupérer depuis le middleware VerifTableCrud
        $tablePascalCase = Str::ucfirst(Str::camel($table));
        $h1 = $tablePascalCase;
        $title = 'CRUD - Editer : ' . $tablePascalCase . '/'.$id;

        $donnees = $crudTable->crud('update', $id);
        $hrefs['index'] = route('crud.index', ['table' => $table]);
        $hrefs['show'] = route('crud.show', ['table' => $table, 'id' => $id]);
        $hrefs['delete'] = route('crud.delete', ['table' => $table, 'id' => $id]);

        return view('admin.crud.update', [
            'donnees' => $donnees,
            'h1' => $h1,
            'title' => $title,
            'hrefs' => $hrefs
        ]);
    }

    /**
     * Modification d'un élement : traitement du POST
     *
     * @param Illuminate\Http\Request $request
     * @param string $table
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStore(Request $request, $table, $id)
    {
        Log::info(" -------- Controller Crud : updateStore -------- ");
        $crudTable = $request->crudTable; // Récupérer depuis le middleware VerifTableCrud
        $modele = 'App\\'.modelName(str_replace('-', '_', $table));
        $instance = $modele::findOrFail($id);
        $rules = $modele::rules($instance);

        $messages = $rules['messages'] ?? []; // On récupère éventuellement les messages associés
        $rules = $rules['rules']; // On récupère les règles de validations

        $request = Validator::make($request->all(), $rules, $messages)->validate();
        $instance->update($request);

        $this::refreshCaches($crudTable, $instance);
        return redirect()->route('crud.show', ['table' => $table, 'id' => $id]);
    }

    /**
     * Suppression de l'élement qui a l'id $id dans la table $table
     *
     * @param string $table
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function delete(Request $request, $table, $id)
    {
        Log::info(" -------- Controller Crud : delete -------- ");
        $crudTable = $request->crudTable; // Récupérer depuis le middleware VerifTableCrud
        $modele = 'App\\'.modelName(str_replace('-', '_', $table));
        $instance = $modele::findOrFail($id);
        $this::refreshCaches($crudTable, $instance);
        $instance->delete();
        Log::info("Suppression de l'id $id dans la table $crudTable->nom");

        return redirect()->route('crud.index', ['table' => $table]);
    }

    /**
     * Suppressions multiples d'élements de la table $table
     *
     * @param Illuminate\Http\Request $request
     * @param string $table
     * @return void
     */
    public function deleteAjax(Request $request, $table)
    {
        Log::info(" -------- Controller Crud : deleteAjax -------- ");
        $table = str_replace('-', '_', $table);
        $crudTable = CrudTable::whereNom($table)->firstOrFail();
        $modele = 'App\\'.modelName($table);
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => "integer|exists:$table,id"
        ]);

        if ($validator->fails())
            return response(null, 404);

        $request = $validator->validate();
        foreach ($request['ids'] as $id)
            $this::refreshCaches($crudTable, $modele::findOrFail($id));
        $modele::destroy($request['ids']);
    }

    /**
     * Rechargement des caches
     *
     * @param CrudTable $crudTable
     * @param object $instance
     * @return void
     */
    private static function refreshCaches(CrudTable $crudTable, object $instance)
    {
        Log::info(" -------- Controller Crud : refreshCaches -------- ");

        // On supprime les caches index directement liés à la table
        Cache::forget('index-' . Str::slug($crudTable->nom));
        Cache::forget('indexcrud-' . Str::slug($crudTable->nom));

        // On recharge tous les caches dépendants en Asynchrone (Laravel Queues)
        ProcessCrudTable::dispatch($crudTable, $instance);
    }
}
