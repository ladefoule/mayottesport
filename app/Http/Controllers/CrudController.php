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
    public function lister(Request $request, string $table)
    {
        Log::info(" -------- CrudController : lister -------- ");
        $crudTable = $request['crudTable']; // Récupérer depuis le middleware VerifTableCrud
        $listeAttributsVisibles = $crudTable->listeAttributsVisibles();
        if($listeAttributsVisibles == false){
            Log::info('Aucun attribut à afficher dans la page liste : ' . $table);
            abort(404);
        }

        $tablePascalCase = Str::ucfirst(Str::camel($table));
        $h1 = $tablePascalCase;
        $title = 'CRUD - Lister : ' . $h1;

        $liste = $crudTable->crud('lister');
        $href['ajouter'] = route('crud.ajouter', ['table' => $table]);
        $href['supprimer-ajax'] = route('crud.supprimer-ajax', ['table' => $table]);
        $href['lister-ajax'] = route('crud.lister-ajax', ['table' => $table]);

        return view('admin.crud.lister', [
            'liste' => $liste,  // La liste des éléments de la classe
            'listeAttributsVisibles' => $listeAttributsVisibles, // Contient tous les attributs à afficher dans la liste et leur position
                                        // ex: listeAttributsVisibles = [ 0 => [attribut => sport_lib, liste_pos => 1, ...],
                                        //                      1 => [attribut => sport_code, liste_pos => 2, ...], ... ]
            'table' => $table, // Le nom de la table en kebab-case
            'h1' => $h1,    // Titre du header de la card
            'title' => $title, // Title de la page
            'href' => $href // Les liens présents dans la view
        ]);
    }

    /**
     * Affichage (du contenu de tbody) de la liste d'une table pour les requètes Ajax.
     *
     * @param string $table
     * @return \Illuminate\View\View|void
     */
    public function listerAjax(Request $request, string $table)
    {
        Log::info(" -------- CrudController : listerAjax -------- ");
        $crudTable = $request['crudTable']; // Récupérer depuis le middleware VerifTableCrud
        $listeAttributsVisibles = $crudTable->listeAttributsVisibles();
        if($listeAttributsVisibles == false)
            abort(404, 'Aucun attribut à afficher dans la page liste.');

        $liste = $crudTable->crud('lister');
        return view('admin.crud.lister-ajax', [
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
    public function voir(Request $request, string $table, int $id)
    {
        Log::info(" -------- CrudController : voir -------- ");
        $crudTable = $request['crudTable']; // Récupérer depuis le middleware VerifTableCrud
        $listeAttributsVisibles = $crudTable->listeAttributsVisibles('voir');
        if($listeAttributsVisibles == false)
            abort(404, 'Aucun attribut à afficher dans la page \'vue\'.');

        $tablePascalCase = Str::ucfirst(Str::camel($table));
        $h1 = $tablePascalCase . '/' . $id;
        $title = 'CRUD - Voir : ' . $h1;

        $donnees = $crudTable->crud('voir', $id);
        $href['lister'] = route('crud.lister', ['table' => $table]);
        $href['editer'] = route('crud.editer', ['table' => $table, 'id' => $id]);
        $href['supprimer'] = route('crud.supprimer', ['table' => $table, 'id' => $id]);

        return view('admin.crud.voir', [
            'donnees' => $donnees,
            'href' => $href,
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
    public function ajouter(Request $request, string $table)
    {
        Log::info(" -------- CrudController : ajouter -------- ");
        $crudTable = $request['crudTable']; // Récupérer depuis le middleware VerifTableCrud
        $listeAttributsVisibles = $crudTable->listeAttributsVisibles('editer');
        if($listeAttributsVisibles == false)
            abort(404, 'Aucun attribut à afficher dans la page d\'ajout.');

        $tablePascalCase = Str::ucfirst(Str::camel($table));
        $h1 = $tablePascalCase . ' : Ajouter';
        $title = 'CRUD - Ajouter : ' . $tablePascalCase;

        $donnees = $crudTable->crud('ajouter');
        $href['lister'] = route('crud.lister', ['table' => $table]);

        return view('admin.crud.ajouter', [
            'donnees' => $donnees,
            'h1' => $h1,
            'title' => $title,
            'href' => $href
        ]);
    }

    /**
     * Modification de l'élement qui a l'id $id de la table $table
     *
     * @param string $table
     * @param int $id
     * @return \Illuminate\View\View|void
     */
    public function editer(Request $request, string $table, int $id)
    {
        Log::info(" -------- CrudController : editer -------- ");
        $crudTable = $request['crudTable']; // Récupérer depuis le middleware VerifTableCrud
        $listeAttributsVisibles = $crudTable->listeAttributsVisibles('editer');
        if($listeAttributsVisibles == false)
            abort(404, 'Aucun attribut à afficher dans la page d\'édition.');

        $tablePascalCase = Str::ucfirst(Str::camel($table));
        $h1 = $tablePascalCase . '/'.$id . ' : Editer';
        $title = 'CRUD - Editer : ' . $tablePascalCase . '/'.$id;

        $donnees = $crudTable->crud('editer', $id);
        $href['lister'] = route('crud.lister', ['table' => $table]);
        $href['voir'] = route('crud.voir', ['table' => $table, 'id' => $id]);
        $href['supprimer'] = route('crud.supprimer', ['table' => $table, 'id' => $id]);

        return view('admin.crud.editer', [
            'donnees' => $donnees,
            'h1' => $h1,
            'title' => $title,
            'href' => $href
        ]);
    }

    /**
     * Ajout d'un élement : traitement du POST
     *
     * @param Illuminate\Http\Request $request
     * @param string $table
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ajouterPost(Request $request, string $table)
    {
        Log::info(" -------- CrudController : ajouterPost -------- ");
        $modele = 'App\\'.modelName(str_replace('-', '_', $table));
        $rules = $modele::rules($request);

        $messages = $rules['messages'] ?? []; // On récupère éventuellement les messages associés
        $request = $rules['request'] ?? $request; // On récupère la requète MAJ s'il y a des checkbox dans le formulaire
        $rules = $rules['rules']; // On récupère les règles de validations

        $request = Validator::make($request->all(), $rules, $messages)->validate();
        $instance = $modele::create($request);

        $this::forgetCaches($table, $instance);
        return redirect()->route('crud.voir', ['table' => $table, 'id' => $instance->id]);
    }

    /**
     * Modification d'un élement : traitement du POST
     *
     * @param Illuminate\Http\Request $request
     * @param string $table
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editerPost(Request $request, string $table, int $id)
    {
        Log::info(" -------- CrudController : editerPost -------- ");
        $modele = 'App\\'.modelName(str_replace('-', '_', $table));
        $instance = $modele::findOrFail($id);
        $rules = $modele::rules($request, $instance);

        $messages = $rules['messages'] ?? []; // On récupère éventuellement les messages associés
        $request = $rules['request'] ?? $request; // On récupère la requète MAJ s'il y a des checkbox dans le formulaire
        $rules = $rules['rules']; // On récupère les règles de validations

        $request = Validator::make($request->all(), $rules, $messages)->validate();
        $instance->update($request);

        $this::forgetCaches($table, $instance);
        return redirect()->route('crud.voir', ['table' => $table, 'id' => $id]);
    }

    /**
     * Suppression de l'élement qui a l'id $id dans la table $table
     *
     * @param string $table
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function supprimer(Request $request, string $table, int $id)
    {
        Log::info(" -------- CrudController : supprimer -------- ");
        $crudTable = $request['crudTable']; // Récupérer depuis le middleware VerifTableCrud
        $modele = 'App\\'.modelName(str_replace('-', '_', $table));
        $instance = $modele::findOrFail($id);
        $instance->delete();
        Log::info("Suppression de l'id $id dans la table $crudTable->nom");
        Cache::forget("crud-$table-lister"); // Effacement du cache associé
        return redirect()->route('crud.lister', ['table' => $table]);
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
        Cache::forget("crud-$table-lister"); // Effacement du cache associé
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
            Cache::forget("crud-$table-lister");
            Cache::forget("attributs-visibles-$table-lister");
            Cache::forget("attributs-visibles-$table-editer");
            Cache::forget("attributs-visibles-$table-ajouter");
            Cache::forget("attributs-visibles-$table-voir");

            // On renouvelle les 3 tables liées à la gestion du Crud
            Cache::forget("crud-crud-tables-lister");
            Cache::forget("crud-crud-attributs-lister");
            Cache::forget("crud-crud-attribut-infos-lister");
        }else{
            $cache = "crud-$table-lister";
            Cache::forget($cache);
        }
    }
}
