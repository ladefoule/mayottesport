<?php

namespace App\Http\Controllers;

use App\Cache;
use App\CrudTable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CrudController extends Controller
{
    /**
     * Affichage de tous les élements d'une table.
     * Ensuite, elle vérifie s'il y a des attributs de cette table à afficher lors de l'appel à la liste (infos présentes dans la table crud_attributs).
     * Enfin, si on trouve au moins un attribut, on affiche les données.
     *
     * @param string $table
     * @return \Illuminate\View\View|void
     */
    public function index(Request $request, string $table)
    {
        Log::info(" -------- CrudController : index -------- ");
        $crudTable = $request['crudTable']; // Récupérer depuis le middleware VerifTableCrud
        $listeAttributsVisibles = $request['listeAttributsVisibles']; // Récupérér depuis le middleware AttributVisible
        $tablePascalCase = Str::ucfirst(Str::camel($table));
        $h1 = $tablePascalCase;
        $title = 'CRUD - Lister : ' . $h1;

        $liste = $crudTable->index();
        $hrefs['create'] = route('crud.create', ['table' => $table]);
        $hrefs['delete-ajax'] = route('crud.delete-ajax', ['table' => $table]);
        $hrefs['index-ajax'] = route('crud.index-ajax', ['table' => $table]);

        return view('admin.crud.index', [
            'liste' => $liste,  // La liste des éléments de la classe
            'listeAttributsVisibles' => $listeAttributsVisibles, // Contient tous les attributs à afficher dans la liste et leur position
                                        // ex: listeAttributsVisibles = [ 0 => [attribut => sport_lib, liste_pos => 1, ...],
                                        //                      1 => [attribut => sport_code, liste_pos => 2, ...], ... ]
            'table' => $table, // Le nom de la table en kebab-case
            'h1' => $h1,    // Titre du header de la card
            'title' => $title, // Title de la page
            'hrefs' => $hrefs // Les liens présents dans la view
        ]);
    }

    /**
     * Affichage (du contenu de tbody) de la liste d'une table pour les requètes Ajax.
     *
     * @param string $table
     * @return \Illuminate\View\View|void
     */
    public function indexAjax(Request $request, string $table)
    {
        Log::info(" -------- CrudController : indexAjax -------- ");
        $crudTable = $request['crudTable']; // Récupérer depuis le middleware VerifTableCrud
        $listeAttributsVisibles = $request['listeAttributsVisibles']; // Récupérér depuis le middleware AttributVisible
        $liste = $crudTable->index();
        return view('admin.crud.index-ajax', [
            'liste' => $liste,
            'listeAttributsVisibles' => $listeAttributsVisibles
        ]);
    }

    /**
     * Affichage d'un élement (vue)
     *
     * @param string $table
     * @param int $id
     * @return \Illuminate\View\View|void
     */
    public function show(Request $request, string $table, int $id)
    {
        Log::info(" -------- CrudController : show -------- ");
        $crudTable = $request['crudTable']; // Récupérer depuis le middleware VerifTableCrud

        $tablePascalCase = Str::ucfirst(Str::camel($table));
        $h1 = $tablePascalCase . '/' . $id;
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
    public function create(Request $request, string $table)
    {
        Log::info(" -------- CrudController : create -------- ");
        $crudTable = $request['crudTable']; // Récupérer depuis le middleware VerifTableCrud
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
     * Modification de l'élement qui a l'id $id de la table $table
     *
     * @param string $table
     * @param int $id
     * @return \Illuminate\View\View|void
     */
    public function update(Request $request, string $table, int $id)
    {
        Log::info(" -------- CrudController : update -------- ");
        $crudTable = $request['crudTable']; // Récupérer depuis le middleware VerifTableCrud
        $tablePascalCase = Str::ucfirst(Str::camel($table));
        $h1 = $tablePascalCase . '/'.$id . ' : Editer';
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
     * Ajout d'un élement : traitement du POST
     *
     * @param Illuminate\Http\Request $request
     * @param string $table
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createStore(Request $request, string $table)
    {
        Log::info(" -------- CrudController : createStore -------- ");
        $modele = 'App\\'.modelName(str_replace('-', '_', $table));
        $rules = $modele::rules($request);

        $messages = $rules['messages'] ?? []; // On récupère éventuellement les messages associés
        $request = $rules['request'] ?? $request; // On récupère la requète MAJ s'il y a des checkbox dans le formulaire
        $rules = $rules['rules']; // On récupère les règles de validations

        $request = Validator::make($request->all(), $rules, $messages)->validate();
        $instance = $modele::create($request);

        $this::forgetCaches($table, $instance);
        return redirect()->route('crud.show', ['table' => $table, 'id' => $instance->id]);
    }

    /**
     * Modification d'un élement : traitement du POST
     *
     * @param Illuminate\Http\Request $request
     * @param string $table
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStore(Request $request, string $table, int $id)
    {
        Log::info(" -------- CrudController : updateStore -------- ");
        $modele = 'App\\'.modelName(str_replace('-', '_', $table));
        $instance = $modele::findOrFail($id);
        $rules = $modele::rules($request, $instance);

        $messages = $rules['messages'] ?? []; // On récupère éventuellement les messages associés
        $request = $rules['request'] ?? $request; // On récupère la requète MAJ s'il y a des checkbox dans le formulaire
        $rules = $rules['rules']; // On récupère les règles de validations

        $request = Validator::make($request->all(), $rules, $messages)->validate();
        $instance->update($request);

        $this::forgetCaches($table, $instance);
        return redirect()->route('crud.show', ['table' => $table, 'id' => $id]);
    }

    /**
     * Suppression de l'élement qui a l'id $id dans la table $table
     *
     * @param string $table
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function delete(Request $request, string $table, int $id)
    {
        Log::info(" -------- CrudController : delete -------- ");
        $crudTable = $request['crudTable']; // Récupérer depuis le middleware VerifTableCrud
        $modele = 'App\\'.modelName(str_replace('-', '_', $table));
        $instance = $modele::findOrFail($id);
        $instance->delete();
        Log::info("Suppression de l'id $id dans la table $crudTable->nom");
        Cache::forget("crud-$table-index"); // Effacement du cache associé
        return redirect()->route('crud.index', ['table' => $table]);
    }

    /**
     * Suppressions multiples d'élements de la table $table
     *
     * @param Illuminate\Http\Request $request
     * @param string $table
     * @return void
     */
    public function supprimerAjax(Request $request, string $table)
    {
        Log::info(" -------- CrudController : supprimerAjax -------- ");
        $crudTable = $request['crudTable']; // Récupérer depuis le middleware VerifTableCrud
        $modele = 'App\\'.modelName(str_replace('-', '_', $table));
        $nomTable = $crudTable->nom;
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => "integer|exists:$nomTable,id"
        ]);

        if ($validator->fails())
            abort(404);

        $request = $validator->validate();
        foreach ($request['ids'] as $id) {
            $instance = $modele::findOrFail($id);
            $instance->delete();
            Log::info("Suppression de l'id $id dans la table $nomTable");
        }
        Cache::forget("crud-$table-index"); // Effacement du cache associé
    }

    /**
     * Liste des caches à supprimer qui necessiteront donc un renouvellement
     *
     * @param string $table
     * @param object $instance
     * @return void
     */
    private static function forgetCaches(string $table, object $instance)
    {
        Log::info(" -------- CrudController : forgetCaches -------- ");
        if($instance && $table == 'matches'){
            $cacheClassement = "classement-".$instance->journee->saison->id;
            $cacheJournee = "journee-".$instance->journee->id;
            Cache::forget($cacheClassement);
            Cache::forget($cacheJournee);
        }

        if($table == 'crud-tables' || $table == 'crud-attributs' || $table == 'crud-attribut-infos'){
            Log::info("Opération effectuée dans la gestion du Crud");
            Log::info("User : " . Auth::user()->email);
            if($table == 'crud-attribut-infos')
                $table = $instance->crudAttribut->crudTable->nom;
            else if($table == 'crud-attributs')
                $table = $instance->crudTable->nom;
            else
                $table = $instance->nom;

            // La table sur laquelle on apporte des modifications
            $table = str_replace('_', '-' , $table);
            Cache::forget("crud-$table-index");
            Cache::forget("attributs-visibles-$table-index");
            Cache::forget("attributs-visibles-$table-create");
            Cache::forget("attributs-visibles-$table-show");

            // On renouvelle les 3 tables liées à la gestion du Crud
            Cache::forget("crud-crud-tables-index");
            Cache::forget("crud-crud-attributs-index");
            Cache::forget("crud-crud-attribut-infos-index");
        }else{
            $cache = "crud-$table-index";
            Cache::forget($cache);
        }
    }
}
