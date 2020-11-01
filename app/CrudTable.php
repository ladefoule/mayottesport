<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\Redis;

class CrudTable extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom', 'crudable'];
    public $timestamps = false;

    /**
     * On vérifie si la table saisie est présente dans la table de gestion du CRUD.
     * Si c'est le cas, on renvoie l'objet CrudTable correspondant, sinon on renvoie false
     *
     * @param String $table
     * @return CrudTable|false|void
     */
    public static function verifTable(String $table)
    {
        $navbarCrudTables = CrudTable::navbarCrudTables();
        foreach ($navbarCrudTables as $table_) {
            if(array_search($table, $table_))
                return CrudTable::findOrFail($table_['id']);
        }
        return false;
    }

    /**
     * navbarCrudTables
     *
     * @return \Illuminate\Support\Collection
     */
    public static function navbarCrudTables()
    {
        $user = Auth::user();
        $role = $user->role->nom; // admin/membre/superadminpremium etc...
        $key = "crud-navbar-tables-users-" . $role;
        if(!Config::get('constant.activer_cache', false))
            Cache::forget($key);

        if (Cache::has($key))
            return Cache::get($key);
        else
            return Cache::rememberForever($key, function () use($role) {
                if($role == 'superadmin')
                    $crudTables = CrudTable::orderBy('nom')->where('crudable', 1)->get() ?? [];
                else // => admin
                    $crudTables = CrudTable::orderBy('nom')->where('crudable', 1)
                        ->whereNotIn('nom', ['crud_tables', 'crud_attributs', 'crud_attribut_infos', 'users', 'roles'])
                        ->get() ?? [];

                $navbarCrudTables = [];
                foreach ($crudTables as $crudTable) {
                    $nomPascalCase = Str::ucfirst(Str::camel($crudTable->nom));
                    $tableKebabCase = str_replace('_', '-', $crudTable->nom);
                    $route = route('crud.lister', ['table' => $tableKebabCase]);

                    $navbarCrudTables[] = [
                        'nom' => $crudTable->nom,
                        'id' => $crudTable->id,
                        'route' => $route,
                        'nom_kebab_case' => $tableKebabCase,
                        'nom_pascal_case' => $nomPascalCase
                    ];
                }
                return collect($navbarCrudTables);
            });


    }

    // public function liste()
    // {
    //     $key = "crud-tables";
    //     if(!Config::get('constant.activer_cache', false))
    //         Cache::forget($key);

    //     if (Cache::has($key))
    //         return Cache::get($key);
    //     else
    //         return Cache::rememberForever($key, function (){
    //             return $this->liste();
    //         });

    //     $tables = DB::select('SHOW TABLES');
    //     $tables = array_map('current', $tables);
    //     foreach ($tables as $table) {
    //         $crudTable = CrudTable::firstWhere('nom', $table);
    //         if($crudTable == null)
    //             CrudTable::create(['nom' => $table]);
    //     }
    //     $crudTables = CrudTable::orderBy('nom')->get();
    // }

    /**
     * Les règles de validations
     *
     * @param Request $request
     * @param CrudTable $crudTable
     * @return array
     */
    public static function rules(Request $request, CrudTable $crudTable = null)
    {
        $unique = Rule::unique('crud_tables');
        if ($crudTable)
            $unique = $unique->ignore($crudTable);

        $request['crudable'] = $request->has('crudable');
        $rules = [
            'nom' => ['required', 'string', 'min:3', 'max:50', $unique],
            'crudable' => 'boolean'
        ];

        return ['rules' => $rules, 'request' => $request];
    }

    /**
     * La fonction récupère la liste (dans le bon ordre) des attributs qu'on doit afficher
     * soit dans la page liste, la page vue (affichage d'un élement) ou l'édition/ajout d'un élement.
     * Elle renvoie false sinon.
     *
     * @param string $action
     * @return \Illuminate\Support\Collection|false
     */
    public function listeAttributsVisibles($action = 'lister')
    {
        $infos = infosAttributCrud()->toArray();
        $actionsPossibles = [
            'lister' => array_search('liste_pos', $infos) ?: 1,
            'voir' => array_search('vue_pos', $infos) ?: 2,
            'editer' => array_search('edit_pos', $infos) ?: 3
        ];

        if (key_exists($action, $actionsPossibles) == false)
            return false;

        $key = "attributs-visibles-" . str_replace('_', '-' ,$this->nom) . "-" . $action;
        if (!Config::get('constant.activer_cache', false))
            Cache::forget($key);

        if (Cache::has($key))
            return Cache::get($key);
        else
            return Cache::rememberForever($key, function () use($actionsPossibles, $action){
                $listeAttributsVisibles = DB::table('crud_tables')
                    ->join('crud_attributs', 'crud_table_id', 'crud_tables.id')
                    ->join('crud_attribut_infos', 'crud_attribut_id', 'crud_attributs.id')
                    ->where('information', $actionsPossibles[$action])
                    ->where('crud_table_id', $this->id)
                    ->orderByRaw('CAST(valeur AS UNSIGNED)') // Permet d'interpréter la chaine '1' (car valeur est de type Varchar) en entier
                    ->get()->all();

                if ($listeAttributsVisibles == null || count($listeAttributsVisibles) == 0)
                    return false;

                foreach ($listeAttributsVisibles as $key => $infosAttribut) {
                    $infosAttribut = (array) $infosAttribut;
                    Arr::pull($infosAttribut, 'information');
                    Arr::pull($infosAttribut, 'valeur');
                    $liens = infosAttributCrud();

                    // On récupère les infos supplémentaires liés à cet attribut et qui sont présents dans la table crud_attribut_infos
                    // Par exemple : le pattern, le min et max si c'est un nombre, etc...
                    $infosSupplementaires = CrudAttributInfo::whereCrudAttributId($infosAttribut['crud_attribut_id'])->get();
                    // dd($infosSupplementaires);
                    foreach ($infosSupplementaires as $infoSup) {
                        $info = $infoSup->information;
                        $valeur = $infoSup->valeur;
                        $infosAttribut[$liens[$info]] = $valeur;
                    }

                    $listeAttributsVisibles[$key] = $infosAttribut;
                }
                return collect($listeAttributsVisibles);
            });
    }

    /**
     * En fonction de l'action, la fonction renvoie les données associée si elles sont en cache
     * ou les regénère si elles ne sont plus en cache. On stocke que la liste en cache.
     *
     * @param string $action
     * @param integer $id
     * @return array
     */
    public function crud(string $action, int $id = 0)
    {
        $actionsPossibles = ['lister', 'ajouter', 'editer', 'voir'];
        if (in_array($action, $actionsPossibles) == false)
            return false;

        $table = Str::kebab(Str::camel($this->nom));
        if ($action == 'lister') {
            $key = "crud-$table-lister";
            if (!Config::get('constant.activer_cache', false))
                Cache::forget($key);

            if (Cache::has($key))
                return Cache::get($key);
            else
                return Cache::rememberForever($key, function () {
                    return $this->liste();
                });
        }

        return $this->donneesCrud($action, $id);
    }

    /**
     * Liste de tous les éléments de la table $table.
     * Dans cette liste, on aura que les attributs ayant une position 'liste_pos' spécifiée dans la table crud_attribut_infos.
     *
     * @return \Illuminate\Support\Collection
     */
    private function liste()
    {
        // $k = 0;
        $table = $this->nom;
        $tableKebabCase = str_replace('_', '-', $table);
        $modele = 'App\\' . modelName($table);
        $triDefaut = $this->tri_defaut;
        $listeAttributsVisibles = $this->listeAttributsVisibles();
        if ($listeAttributsVisibles == false)
            return [];

        $i = 0;
        foreach($listeAttributsVisibles as $infosAttribut){
            $crudAttributId = $infosAttribut['crud_attribut_id'];
            $crudAttribut = CrudAttribut::findOrFail($crudAttributId);
            $attribut[$i] = $crudAttribut->attribut;

            if ($infosAttribut['attribut_crud_table_id']){
                $listeTableAttribut[$i] = CrudTable::findOrFail($infosAttribut['attribut_crud_table_id'])->crud('lister');
                // dd($nomTable);
                // $listeTableAttribut[$i] = 0;//'App\\' . modelName(CrudTable::findOrFail($crudAttribut->attribut_crud_table_id)->nom);
            }

            $checkbox[$i] = false;
            if (isset($infosAttribut['input_type']) && $infosAttribut['input_type'] == 'checkbox')
                $checkbox[$i] = true;
            $i++;
        }
        $nbAttributs = $i;
        // if(isset($listeTableAttribut)){
        //     var_dump($listeTableAttribut[0]);
        //     exit();
        // }
        $listeComplete = $triDefaut ? $modele::orderBy($triDefaut)->get() : $modele::all();
        foreach ($listeComplete as $instance) {
            $id = $instance->id;
            $liste[$id]['nom'] = $instance->nom;
            $liste[$id]['href_voir'] = route('crud.voir', ['table' => $tableKebabCase, 'id' => $id]);
            $liste[$id]['href_editer'] = route('crud.editer', ['table' => $tableKebabCase, 'id' => $id]);

            // On parcourt la liste des attributs à afficher et on récupère à chaque fois la valeur correspondante
            // On les range dans le tableau $liste[$id]['afficher'][] avec des index numériques
            for ($i = 0; $i < $nbAttributs; $i++) {
                // $crudAttributId = $infosAttribut['crud_attribut_id'];
                // $crudAttribut = CrudAttribut::findOrFail($crudAttributId);
                $contenu = $instance[$attribut[$i]];

                // Si l'attribut attribut_crud_table_id est renseigné, il faut donc récupérer
                // le 'nom' de cette foreign key grace à son modele (info présente dans la table gestion_tables)
                // if ($crudAttribut->attribut_crud_table_id && $contenu) {
                    // $modelTableFK = 'App\\' . modelName(CrudTable::findOrFail($crudAttribut->attribut_crud_table_id)->nom);
                if(isset($listeTableAttribut[$i]))
                    $contenu = $listeTableAttribut[$i][$contenu]['nom'];
                    // $contenu = $modelTableAttribut[$i]::find($contenu)->nom;
                // }

                // if (isset($infosAttribut['input_type']) && $infosAttribut['input_type'] == 'checkbox')
                if($checkbox[$i])
                    $contenu = $contenu ? 'Oui' : 'Non';

                $liste[$id]['afficher'][$i] = $contenu;
            }
            // $k++;
        }
        return collect($liste);
    }

    /**
     * L'ensemble des données à afficher soit dans la vue, lors de l'ajout ou lors d'une modification d'un élément.
     * Dans ces données, on retrouve entre autres : ne nom d'un attribut, sa valeur, s'il est optionnel ou non, son pattern, etc ...
     *
     * @param string $action
     * @param integer $id
     * @return \Illuminate\Support\Collection
     */
    private function donneesCrud(string $action, int $id = 0)
    {
        if ($id != 0) {
            $modele = 'App\\' . modelName($this['nom']);
            $instance = $modele::findOrFail($id);
        }
        $listeAttributsVisibles = $this->listeAttributsVisibles($action == 'ajouter' ? 'editer' : $action);
        if ($listeAttributsVisibles == false)
            return [];

        foreach ($listeAttributsVisibles as $infosAttribut) {
            $attribut = $infosAttribut['attribut']; // Le nom de l'attribut
            $valeurAttribut = ($id == 0) ? '' : $instance->$attribut; // Sa valeur

            // Si c'est une date, alors on la convertit au bon format si elle est non null
            if ($valeurAttribut && $action == 'voir' && isset($infosAttribut['input_type']) && $infosAttribut['input_type'] == 'date')
                $valeurAttribut = date('d/m/Y', strtotime($valeurAttribut));

            // Si l'attribut attribut_crud_table_id est renseigné, il faut récupérer la liste complète
            // des éléments de cette table référence sous forme de select (pour pouvoir faire un choix dessus)
            if ($infosAttribut['attribut_crud_table_id']) {
                $crudTableAttribut = CrudTable::find($infosAttribut['attribut_crud_table_id']);
                $modeleTableAttribut = 'App\\' . modelName($crudTableAttribut->nom);

                // Dans la page vue, on affiche le 'nom' de l'attribut référence
                if ($action == 'voir' && $valeurAttribut)
                    $valeurAttribut = $modeleTableAttribut::find($valeurAttribut)->nom;
                else{
                    $orderBy = $crudTableAttribut->tri_defaut;
                    $select = $orderBy ? $modeleTableAttribut::orderBy($orderBy)->get() : $modeleTableAttribut::get();
                    $informations[$attribut]['select'] = $select;
                }
            }

            // On affiche le bon format pour les timestamps s'ils sont renseignés
            if ($valeurAttribut && $attribut == 'created_at' || $attribut == 'updated_at')
                $valeurAttribut = $valeurAttribut->format('d/m/Y à H:i:s');

            $inputType = $infosAttribut['input_type'] ?? 'text';
            $informations[$attribut]['input_type'] = $inputType;
            $informations[$attribut]['label'] = $infosAttribut['label'];
            $informations[$attribut]['valeur'] = $valeurAttribut;
            if ($action == 'voir' && $inputType == 'checkbox')
                $informations[$attribut]['valeur'] = $valeurAttribut ? 'Oui' : 'Non';

            if ($action != 'voir') { // On n'a pas besoin de ces infos dans la vue
                $optionnel = $infosAttribut['optionnel'];
                $informations[$attribut]['optionnel'] = $optionnel;
                $informations[$attribut]['data_msg'] = $infosAttribut['data_msg'] ? 'data-msg="' . htmlspecialchars($infosAttribut['data_msg']) . '"' : '';
                $informations[$attribut]['pattern'] = isset($infosAttribut['pattern']) ? 'pattern="' . htmlspecialchars($infosAttribut['pattern']) . '"' : '';
                $informations[$attribut]['min'] = isset($infosAttribut['min']) && ($infosAttribut['min'] || $infosAttribut['min'] === 0) ? 'min="' . $infosAttribut['min'] . '"' : '';
                $informations[$attribut]['max'] = isset($infosAttribut['max']) && ($infosAttribut['max'] || $infosAttribut['max'] === 0) ? 'max="' . $infosAttribut['max'] . '"' : '';
                $informations[$attribut]['class'] = ($inputType == 'checkbox' ? 'form-check-input' : 'form-control')
                    . ($optionnel ? ' input-optionnel' : '');
            }
        }
        return collect($informations);
    }
}
