<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    protected $fillable = ['nom', 'crudable', 'tri_defaut'];
    public $timestamps = false;

    /**
     * navbarCrudTables
     *
     * @return \Illuminate\Support\Collection
     */
    public static function navbarCrudTables()
    {
        $key = "crud-navbar-tables";
        if(! Config::get('constant.activer_cache'))
            Cache::forget($key);

        if (Cache::has($key))
            return Cache::get($key);
        else
            return Cache::rememberForever($key, function () use($key) {
                Log::info('Rechargement du cache : ' . $key);
                $crudTables = CrudTable::orderBy('nom')->where('crudable', 1)
                        ->whereNotIn('nom', ['crud_tables', 'crud_attributs', 'crud_attribut_infos', 'users', 'roles', 'migrations', 'password_resets'])
                        ->get() ?? [];

                $navbarCrudTables = [];
                foreach ($crudTables as $crudTable) {
                    $nomPascalCase = Str::ucfirst(Str::camel($crudTable->nom));
                    $tableSlug = Str::slug($crudTable->nom);
                    $route = route('crud.index', ['table' => $tableSlug]);

                    $navbarCrudTables[$crudTable->nom] = [
                        'nom' => $crudTable->nom,
                        'id' => $crudTable->id,
                        'route' => $route,
                        'nom_kebab_case' => $tableSlug,
                        'nom_pascal_case' => $nomPascalCase
                    ];
                }
                return collect($navbarCrudTables);
            });
    }

    /**
     * Les règles de validations
     *
     * @param CrudTable $crudTable
     * @return array
     */
    public static function rules(CrudTable $crudTable = null)
    {
        $unique = Rule::unique('crud_tables');
        if ($crudTable)
            $unique = $unique->ignore($crudTable);

        request()->crudable = request()->has('crudable');
        $rules = [
            'nom' => ['required', 'string', 'min:3', 'max:50', $unique],
            'tri_defaut' => 'required|max:50',
            'crudable' => 'boolean'
        ];

        return ['rules' => $rules];
    }

    /**
     * La fonction récupère la liste (dans le bon ordre) des attributs et de leurs propriétés qu'on doit afficher
     * soit dans la page liste, dans la page show (affichage d'un élement) ou l'édition/ajout d'un élement.
     * Elle renvoie false si la liste est vide.
     *
     * @param string $action
     * @return \Illuminate\Support\Collection|false
     */
    public function listeAttributsVisibles(string $action = 'index')
    {
        if($action == 'update')
                $action = 'create'; // La liste des attributs visibles est la même lors de l'ajout ou de la modification

        $key = "attributs-visibles-" . str_replace('_', '-' ,$this->nom) . "-" . $action;
        if (! Config::get('constant.activer_cache'))
            Cache::forget($key);

        if (Cache::has($key))
            return Cache::get($key);
        else
            return Cache::rememberForever($key, function () use($action, $key){
                Log::info('Rechargement du cache : ' . $key);

                $correspondances = config('constant.crud-attribut');
                foreach ($correspondances as $id => $value)
                    if($value[0] == $action . '_position'){
                        $infoId = $id;
                        break;
                    }

                if(! isset($infoId))
                    return false;

                $listeAttributsVisibles = DB::table('crud_tables')
                    ->join('crud_attributs', 'crud_table_id', 'crud_tables.id')
                    ->join('crud_attribut_infos', 'crud_attribut_id', 'crud_attributs.id')
                    ->where('propriete_id', $infoId)
                    ->where('crud_table_id', $this->id)
                    ->orderByRaw('CAST(valeur AS UNSIGNED)') // Permet d'interpréter une chaine '1' (car valeur est de type Varchar) en entier
                    ->select(['crud_attributs.*', 'crud_tables.*', 'crud_attribut_infos.crud_attribut_id'])
                    ->get()->all();

                if ($listeAttributsVisibles == null || count($listeAttributsVisibles) == 0)
                    return false;

                foreach ($listeAttributsVisibles as $key => $attributInfos) {
                    $attributInfos = (array) $attributInfos;

                    // Correspondance entre les propriete_id et leur vraie signification
                    $correspondances = config('constant.crud-attribut');

                    // On récupère les infos supplémentaires liés à cet attribut et qui sont présents dans la table crud_attribut_infos
                    // Par exemple : le pattern, le min et max si c'est un nombre, etc...
                    $infosSupplementaires = CrudAttributInfo::whereCrudAttributId($attributInfos['crud_attribut_id'])->get();
                    foreach ($infosSupplementaires as $infoSup) {
                        $infoId = $infoSup->propriete_id;
                        $valeur = $infoSup->valeur;
                        $attributInfos[$correspondances[$infoId][0]] = $valeur;
                    }

                    $listeAttributsVisibles[$key] = $attributInfos;
                }

                return collect($listeAttributsVisibles);
            });
    }

    /**
     * L'ensemble des données à afficher soit dans la page show, lors de l'ajout ou lors d'une modification d'un élément.
     * Dans ces données, on retrouve entre autres : le nom d'un attribut, sa valeur, s'il est optionnel ou non, son pattern, etc ...
     *
     * @param string $action
     * @param integer $id
     * @return \Illuminate\Support\Collection|false
     */
    public function crud(string $action, int $id = 0)
    {
        if ($id != 0) {
            // $modele = 'App\\' . modelName($this['nom']);
            $instance = index($this->nom)[$id];
        }
        $listeAttributsVisibles = $this->listeAttributsVisibles($action);

        $donnees = [];
        foreach ($listeAttributsVisibles as $attributInfos) {
            $attribut = $attributInfos['attribut']; // Le nom de l'attribut
            $valeurAttribut = ($id == 0) ? '' : $instance->$attribut; // Sa valeur

            // Si c'est une date, alors on la convertit au format jj/mm/aaaa si elle est non null
            if ($valeurAttribut && $action == 'show' && isset($attributInfos['input_type']) && $attributInfos['input_type'] == 'date')
                $valeurAttribut = date('d/m/Y', strtotime($valeurAttribut));

            // Si attribut_crud_table_id est renseigné, on récupère la liste complète des éléments de cette table référence
            if ($attributInfos['attribut_crud_table_id']) {
                $tableReference = index('crud_tables')[$attributInfos['attribut_crud_table_id']]->nom;
                $indexTableReference = index($tableReference);
                // $modeleTableAttribut = 'App\\' . modelName($crudTableAttribut->nom); // ORM Eloquent

                if ($action == 'show' && $valeurAttribut)
                    $valeurAttribut = isset($indexTableReference[$valeurAttribut]->crud_name) ? $indexTableReference[$valeurAttribut]->crud_name : $indexTableReference[$valeurAttribut]->nom;
                    // $valeurAttribut = $modeleTableAttribut::find($valeurAttribut)->crud_name; // ORM Eloquent
                else
                    $donnees[$attribut]['select'] = $indexTableReference;
            }

            // Si l'attribut est lié à une liste, on récupère la liste dans les configs
            if (isset($attributInfos['input_type']) && $attributInfos['input_type'] == 'select' && isset($attributInfos['select_liste'])) {
                $selectListe = config('constant.' . $attributInfos['select_liste']);
                if($action == 'show' && $valeurAttribut)
                    $valeurAttribut = $selectListe[$valeurAttribut][1];
            }

            // On affiche le bon format pour les timestamps s'ils sont renseignés
            // Todo : introduire un type input timestamps pour pouvoir gérer tous les timestamps
            if ($valeurAttribut && $attribut == 'created_at' || $attribut == 'updated_at'){
                $date = new Carbon($valeurAttribut);
                $date = $date->format('d/m/Y à H:i:s');
                $valeurAttribut = $date;
            }

            $inputType = $attributInfos['input_type'] ?? 'text';
            $donnees[$attribut]['input_type'] = $inputType;
            $donnees[$attribut]['label'] = $attributInfos['label'];
            $donnees[$attribut]['valeur'] = $valeurAttribut;

            // Si c'est un booléen
            if ($action == 'show' && $inputType == 'checkbox')
                $donnees[$attribut]['valeur'] = $valeurAttribut ? 'Oui' : 'Non';

            // On n'a pas besoin de ces infos dans la page show, on les utilise que dans les formulaires
            if ($action != 'show') {
                $optionnel = $attributInfos['optionnel'];
                $donnees[$attribut]['optionnel'] = $optionnel;
                $donnees[$attribut]['select_liste'] = $selectListe ?? [];
                $donnees[$attribut]['data_msg'] = $attributInfos['data_msg'] ? 'data-msg="' . htmlspecialchars($attributInfos['data_msg']) . '"' : '';
                $donnees[$attribut]['pattern'] = isset($attributInfos['pattern']) ? 'pattern="' . htmlspecialchars($attributInfos['pattern']) . '"' : '';
                $donnees[$attribut]['min'] = isset($attributInfos['min']) && ($attributInfos['min'] || $attributInfos['min'] === 0) ? 'min="' . $attributInfos['min'] . '"' : '';
                $donnees[$attribut]['max'] = isset($attributInfos['max']) && ($attributInfos['max'] || $attributInfos['max'] === 0) ? 'max="' . $attributInfos['max'] . '"' : '';
                $donnees[$attribut]['class'] = ($inputType == 'checkbox' ? 'form-check-input' : 'form-control')
                    . ($optionnel ? ' input-optionnel' : '');
            }
        }
        return collect($donnees);
    }

    /**
     * Liste de tous les éléments de la table.
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        $table = $this->nom;
        $tableSlug = Str::slug($table);
        $modele = 'App\\' . modelName($table);

        $key = "index-$tableSlug";
        if (!Config::get('constant.activer_cache'))
            Cache::forget($key);

        if (Cache::has($key))
            $index = Cache::get($key);
        else
            $index = Cache::rememberForever($key, function () use($modele, $key){
                Log::info('Rechargement du cache : ' . $key);

                $liste = [];
                $triDefaut = $this->tri_defaut;
                $listeComplete = $triDefaut ? $modele::orderBy($triDefaut)->get() : $modele::all();
                foreach ($listeComplete as $instance) {
                    $id = $instance->id;
                    $collect = collect();
                    // On ajoute tous les attributs de l'objet dans une collection
                    foreach ($instance->attributes as $key => $value)
                        $collect->$key = $value;

                    // Tous les éléments auront un attribut nom même vide
                    $collect->nom = $instance->nom ?? '';

                    // On ajoute l'attribut crud_name qu'aux classes qui possèdent la méthode suivante
                    if(method_exists($modele, 'getCrudNameAttribute'))
                        $collect->crud_name = $instance->crud_name;

                    $liste[$id] = $collect;
                }

                return collect($liste);
            });
        return $index;
    }

    /**
     * Liste de tous les éléments de la table.
     *
     * @return \Illuminate\Support\Collection
     */
    public function indexCrud()
    {
        $table = $this->nom;
        $tableSlug = Str::slug($table);
        // $modele = 'App\\' . modelName($table);

        $key = "indexcrud-$tableSlug";
        if (!Config::get('constant.activer_cache'))
            Cache::forget($key);

        if (Cache::has($key))
            return Cache::get($key);
        else
            return Cache::rememberForever($key, function () use($key){
                Log::info('Rechargement du cache : ' . $key);

                $table = $this->nom;
                $tableSlug = Str::slug($table);

                $triDefaut = $this->tri_defaut;
                $listeComplete = $triDefaut ? $this->index()->sortBy($triDefaut) : $this->index();
                // $listeComplete = $triDefaut ? $modele::orderBy($triDefaut)->get() : $modele::all();
                $liste = [];

                $listeAttributsVisibles = $this->listeAttributsVisibles();
                if ($listeAttributsVisibles == false)
                    return [];

                $i = 0;
                foreach($listeAttributsVisibles as $attributInfos){
                    $crudAttributId = $attributInfos['crud_attribut_id'];
                    $crudAttribut = index('crud_attributs')[$crudAttributId];
                    $attribut[$i] = $crudAttribut->attribut;

                    if ($attributInfos['attribut_crud_table_id']){
                        $tableReference = index('crud_tables')[$attributInfos['attribut_crud_table_id']]->nom;
                        // $modeleReference = 'App\\' . modelName($tableReference);
                        $listeTableAttribut[$i] = /* $modeleReference::all(); */index($tableReference);
                    }

                    $checkbox[$i] = false;
                    if (isset($attributInfos['input_type']) && $attributInfos['input_type'] == 'checkbox')
                        $checkbox[$i] = true;
                    $i++;
                }
                $nbAttributs = $i;

                foreach ($listeComplete as $instance) {
                    $id = $instance->id;
                    $collect = collect();

                    // $collect->nom = $instance->nom;
                    // $collect->crud_name = $instance->crud_name;
                    $collect->href_show = route('crud.show', ['table' => $tableSlug, 'id' => $id]);
                    $collect->href_update = route('crud.update', ['table' => $tableSlug, 'id' => $id]);

                    // On parcourt la liste des attributs à afficher et on récupère à chaque fois la valeur correspondante
                    // On les range dans le tableau $collect->afficher[] avec des index numériques
                    $afficher = [];
                    for ($i = 0; $i < $nbAttributs; $i++) {
                        $attr = $attribut[$i];
                        $contenu = $instance->$attr;

                        // Si l'attribut attribut_crud_table_id est renseigné, il faut donc récupérer
                        // le 'crud_name' de cette foreign key grace à son modele (info présente dans la table gestion_tables)
                        if(isset($listeTableAttribut[$i])){
                            $instanceReference = $listeTableAttribut[$i]->firstWhere('id', $contenu);
                            if(! $instanceReference)
                                abort(404);
                            $contenu = isset($instanceReference->crud_name) ? $instanceReference->crud_name : $instanceReference->nom;
                        }

                        if($checkbox[$i])
                            $contenu = $contenu ? 'Oui' : 'Non';

                        $afficher[] = $contenu;
                    }
                    $collect->afficher = $afficher;
                    $liste[$id] = $collect;
                }
                return collect($liste);
        });
    }
}
