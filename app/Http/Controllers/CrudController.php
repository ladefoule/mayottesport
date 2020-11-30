<?php

namespace App\Http\Controllers;

use App\Cache;
use App\CrudTable;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class CrudController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info(" -------- CrudController : __construct -------- ");
        $this->middleware('verif-table-crud')->except('forgetCaches');
        $this->middleware('attribut-visible')->only(['index', 'show', 'createForm', 'updateForm']);
    }

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
            'h1' => $h1,    // Titre du header de la card
            'title' => $title, // Title de la page
            'hrefs' => $hrefs, // Les liens présents dans la view
            'listeAttributsVisibles' => $request->listeAttributsVisibles
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
        $crudTable = $request->crudTable; // Récupérer depuis le middleware VerifTableCrud
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
    public function show(Request $request, string $table, int $id)
    {
        Log::info(" -------- CrudController : show -------- ");
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
    public function createForm(Request $request, string $table)
    {
        Log::info(" -------- CrudController : create -------- ");
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
    public function createStore(Request $request, string $table)
    {
        Log::info(" -------- CrudController : createStore -------- ");
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

        // Todo : Erreur lors de la crétion d'un élément equipe_saison, impossible de récupérer l'id car table pivot
        // dd($instance);

        $this::forgetCaches($crudTable, $instance);
        return redirect()->route('crud.show', ['table' => $table, 'id' => $instance->id]);
    }

    /**
     * Modification de l'élement qui a l'id $id de la table $table
     *
     * @param string $table
     * @param int $id
     * @return \Illuminate\View\View|void
     */
    public function updateForm(Request $request, string $table, int $id)
    {
        Log::info(" -------- CrudController : update -------- ");
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
    public function updateStore(Request $request, string $table, int $id)
    {
        Log::info(" -------- CrudController : updateStore -------- ");
        $crudTable = $request->crudTable; // Récupérer depuis le middleware VerifTableCrud
        $modele = 'App\\'.modelName(str_replace('-', '_', $table));
        $instance = $modele::findOrFail($id);
        $rules = $modele::rules($instance);

        $messages = $rules['messages'] ?? []; // On récupère éventuellement les messages associés
        $rules = $rules['rules']; // On récupère les règles de validations

        $request = Validator::make($request->all(), $rules, $messages)->validate();
        $instance->update($request);

        $this::forgetCaches($crudTable, $instance);
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
        $crudTable = $request->crudTable; // Récupérer depuis le middleware VerifTableCrud
        $modele = 'App\\'.modelName(str_replace('-', '_', $table));
        $instance = $modele::findOrFail($id);
        $this::forgetCaches($crudTable, $instance);
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
    public function deleteAjax(Request $request, string $table)
    {
        Log::info(" -------- CrudController : deleteAjax -------- ");
        $crudTable = $request->crudTable; // Récupérer depuis le middleware VerifTableCrud
        $modele = 'App\\'.modelName(str_replace('-', '_', $table));
        $nomTable = $crudTable->nom;
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => "integer|exists:$nomTable,id"
        ]);

        if ($validator->fails())
            return response(null, 404);

        $request = $validator->validate();
        $modele::destroy($request['ids']);
        $this::forgetCaches($crudTable);
        // Cache::forget('index-' . $table);
        // foreach ($request['ids'] as $id) {
        //     $instance = $modele::findOrFail($id);
        //     $this::forgetCaches($table, $instance);
        //     $instance->delete();
        //     Log::info("Suppression de l'id $id dans la table $nomTable");
        // }
    }

    /**
     * Liste des caches à supprimer qui necessiteront donc un renouvellement
     *
     * @param string $table
     * @param object $instance
     * @return void
     */
    private static function forgetCaches(CrudTable $crudTable, object $instance = null)
    {
        Log::info(" -------- CrudController : forgetCaches -------- ");
        // $client = new \GuzzleHttp\Client(['base_uri' => 'http://v2.mayottesport.com']);
        $client = new Client([
            // Base URI is used with relative requests
            // 'base_uri' => 'http://v2.mayottesport.com',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
        // // Send an asynchronous request.
        // $request = new \GuzzleHttp\Psr7\Request('GET', '/ajax/caches/reload', ['timeout' => 2]);
        // $client->sendAsync($request)->then(function ($response) {
        //     Log::info('I completed! ' . $response->getBody());
        // });

        // $request = new GuzzleRequest('GET', 'http://v2.mayottesport.com');
        // $promise = $client->requestAsync('GET', 'http://v2.mayottesport.com');

        // use Psr\Http\Message\ResponseInterface;
        // use GuzzleHttp\Exception\RequestException;

        // $response = $client->get('http://v2.mayottesport.com');
        // Log::info((string) $response->getBody());

        // $client->get('http://v2.mayottesport.com/ajax/caches/reload');

        $promise = $client->requestAsync('GET', 'http://v2.mayottesport.com/ajax/caches/reload');
        $promise->then(
            function (ResponseInterface $res) {
                Log::info('ICI');
                Log::info($res->getStatusCode() . "\n");
            },
            function (RequestException $e) {
                Log::info('LA');
                Log::info($e->getMessage() . "\n");
                Log::info($e->getRequest()->getMethod());
            }
        );

        // Notre requète n'est pas encore partie. Il faut lancer manuellement l'appel.
        $client->wait();


        Log::info('OK');
        Log::info('OK');
        Log::info('OK');
        Log::info('OK');
    }
}
