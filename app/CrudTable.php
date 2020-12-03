<?php

namespace App;

use Carbon\Carbon;
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
    protected $fillable = ['nom', 'crudable', 'tri_defaut'];
    public $timestamps = false;

    /**
     * Définition de l'affichage dans le CRUD (back-office)
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        return $this->nom;
    }

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
            return Cache::rememberForever($key, function () {
                $crudTables = CrudTable::orderBy('nom')->where('crudable', 1)
                        ->whereNotIn('nom', ['crud_tables', 'crud_attributs', 'crud_attribut_infos', 'users', 'roles', 'migrations', 'password_resets'])
                        ->get() ?? [];

                $navbarCrudTables = [];
                foreach ($crudTables as $crudTable) {
                    $nomPascalCase = Str::ucfirst(Str::camel($crudTable->nom));
                    $tableKebabCase = str_replace('_', '-', $crudTable->nom);
                    $route = route('crud.index', ['table' => $tableKebabCase]);

                    $navbarCrudTables[$crudTable->nom] = [
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

    // public function crudName(array $tables, string $attribut = 'nom')
    // {
    //     $str = '';
    //     foreach ($tables as $key => $table) {
    //         $str += index($table)
    //     }
    // }

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
     * La fonction récupère la liste (dans le bon ordre) des attributs qu'on doit afficher
     * soit dans la page show (affichage d'un élement) ou l'édition/ajout d'un élement.
     * Elle renvoie false sinon la liste est vide.
     *
     * @param string $action
     * @return \Illuminate\Support\Collection|false
     */
    public function listeAttributsVisibles(string $action = 'index')
    {
        $key = "attributs-visibles-" . str_replace('_', '-' ,$this->nom) . "-" . $action;
        if (! Config::get('constant.activer_cache'))
            Cache::forget($key);

        if (Cache::has($key))
            return Cache::get($key);
        else
            return Cache::rememberForever($key, function () use($action){
                if($action == 'update')
                    $action = 'create'; // La liste des attributs visibles est la même lors de l'ajout ou de la modification

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

                foreach ($listeAttributsVisibles as $key => $infosAttribut) {
                    $infosAttribut = (array) $infosAttribut;

                    // Correspondance entre les propriete_id et leur vraie signification
                    $correspondances = config('constant.crud-attribut');

                    // On récupère les infos supplémentaires liés à cet attribut et qui sont présents dans la table crud_attribut_infos
                    // Par exemple : le pattern, le min et max si c'est un nombre, etc...
                    $infosSupplementaires = CrudAttributInfo::whereCrudAttributId($infosAttribut['crud_attribut_id'])->get();
                    foreach ($infosSupplementaires as $infoSup) {
                        $infoId = $infoSup->propriete_id;
                        $valeur = $infoSup->valeur;
                        $infosAttribut[$correspondances[$infoId][0]] = $valeur;
                    }

                    $listeAttributsVisibles[$key] = $infosAttribut;
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
        foreach ($listeAttributsVisibles as $infosAttribut) {
            $attribut = $infosAttribut['attribut']; // Le nom de l'attribut
            $valeurAttribut = ($id == 0) ? '' : $instance->$attribut; // Sa valeur

            // Si c'est une date, alors on la convertit au bon format si elle est non null
            if ($valeurAttribut && $action == 'show' && isset($infosAttribut['input_type']) && $infosAttribut['input_type'] == 'date')
                $valeurAttribut = date('d/m/Y', strtotime($valeurAttribut));

            // Si l'attribut attribut_crud_table_id est renseigné, il faut récupérer la liste complète
            // des éléments de cette table référence sous forme de select (pour pouvoir faire un choix dessus)
            if ($infosAttribut['attribut_crud_table_id']) {
                $crudTableAttribut = index('crud_tables')[$infosAttribut['attribut_crud_table_id']];
                $modeleTableAttribut = 'App\\' . modelName($crudTableAttribut->nom);

                // Dans la page vue, on affiche le 'nom' de l'attribut référence, sinon on affiche la liste complète
                if ($action == 'show' && $valeurAttribut)
                    $valeurAttribut = $modeleTableAttribut::find($valeurAttribut)->crud_name;
                else
                    $donnees[$attribut]['select'] = index($crudTableAttribut->nom);
            }

            if (isset($infosAttribut['input_type']) && $infosAttribut['input_type'] == 'select' && isset($infosAttribut['select_liste'])) {
                $selectListe = config('constant.' . $infosAttribut['select_liste']);
                if($action == 'show' && $valeurAttribut)
                    $valeurAttribut = $selectListe[$valeurAttribut][1];
            }

            // On affiche le bon format pour les timestamps s'ils sont renseignés
            if ($valeurAttribut && $attribut == 'created_at' || $attribut == 'updated_at'){
                $date = new Carbon($valeurAttribut);
                $date = $date->format('d/m/Y à H:i:s');
                $valeurAttribut = $date;
            }

            $inputType = $infosAttribut['input_type'] ?? 'text';
            $donnees[$attribut]['input_type'] = $inputType;
            $donnees[$attribut]['label'] = $infosAttribut['label'];
            $donnees[$attribut]['valeur'] = $valeurAttribut;

            if ($action == 'show' && $inputType == 'checkbox')
                $donnees[$attribut]['valeur'] = $valeurAttribut ? 'Oui' : 'Non';

            if ($action != 'show') { // On n'a pas besoin de ces infos dans la page show
                $optionnel = $infosAttribut['optionnel'];
                $donnees[$attribut]['optionnel'] = $optionnel;
                $donnees[$attribut]['select_liste'] = $selectListe ?? [];
                $donnees[$attribut]['data_msg'] = $infosAttribut['data_msg'] ? 'data-msg="' . htmlspecialchars($infosAttribut['data_msg']) . '"' : '';
                $donnees[$attribut]['pattern'] = isset($infosAttribut['pattern']) ? 'pattern="' . htmlspecialchars($infosAttribut['pattern']) . '"' : '';
                $donnees[$attribut]['min'] = isset($infosAttribut['min']) && ($infosAttribut['min'] || $infosAttribut['min'] === 0) ? 'min="' . $infosAttribut['min'] . '"' : '';
                $donnees[$attribut]['max'] = isset($infosAttribut['max']) && ($infosAttribut['max'] || $infosAttribut['max'] === 0) ? 'max="' . $infosAttribut['max'] . '"' : '';
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
        $tableKebabCase = str_replace('_', '-', $table);
        $modele = 'App\\' . modelName($table);

        $key = "index-$tableKebabCase";
        if (!Config::get('constant.activer_cache'))
            Cache::forget($key);

        if (Cache::has($key))
            $index = Cache::get($key);
        else
            $index = Cache::rememberForever($key, function () use($modele, $tableKebabCase){
                $triDefaut = $this->tri_defaut;
                $liste = [];
                $listeComplete = $triDefaut ? $modele::orderBy($triDefaut)->get() : $modele::all();
                foreach ($listeComplete as $instance) {
                    $id = $instance->id;
                    $collect = collect();
                    foreach ($instance->attributes as $key => $value)
                        $collect->$key = $value;

                    $collect->nom = $instance->nom;
                    $collect->crud_name = $instance->crud_name;

                    // $collect->infos =  method_exists($instance, 'infos') ? $instance->infos() : [];
                    $collect->href_show = route('crud.show', ['table' => $tableKebabCase, 'id' => $id]);
                    $collect->href_update = route('crud.update', ['table' => $tableKebabCase, 'id' => $id]);

                    $liste[$id] = /* $instance; */$collect;
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
        $tableKebabCase = str_replace('_', '-', $table);
        // $modele = 'App\\' . modelName($table);

        $key = "indexcrud-$tableKebabCase";
        if (!Config::get('constant.activer_cache'))
            Cache::forget($key);

        if (Cache::has($key))
            return Cache::get($key);
        else
            return Cache::rememberForever($key, function (){
            $table = $this->nom;
            $tableKebabCase = str_replace('_', '-', $table);

            $triDefaut = $this->tri_defaut;
            $liste = [];
            // $listeComplete = $triDefaut ? $modele::orderBy($triDefaut)->get() : $modele::all();
            $listeComplete = $this->index();

            $listeAttributsVisibles = $this->listeAttributsVisibles();
            if ($listeAttributsVisibles == false)
                return [];

            $i = 0;
            foreach($listeAttributsVisibles as $infosAttribut){
                $crudAttributId = $infosAttribut['crud_attribut_id'];
                $crudAttribut = index('crud_attributs')[$crudAttributId];
                $attribut[$i] = $crudAttribut->attribut;

                if ($infosAttribut['attribut_crud_table_id']){
                    $tableReference = index('crud_tables')[$infosAttribut['attribut_crud_table_id']]->nom;
                    // $modeleReference = 'App\\' . modelName($tableReference);
                    $listeTableAttribut[$i] = /* $modeleReference::all(); */index($tableReference);
                }

                $checkbox[$i] = false;
                if (isset($infosAttribut['input_type']) && $infosAttribut['input_type'] == 'checkbox')
                    $checkbox[$i] = true;
                $i++;
            }
            $nbAttributs = $i;

            foreach ($listeComplete as $instance) {
                $id = $instance->id;
                $collect = collect();

                $collect->nom = $instance->nom;
                // $collect->crud_name = $instance->crud_name;
                $collect->href_show = route('crud.show', ['table' => $tableKebabCase, 'id' => $id]);
                $collect->href_update = route('crud.update', ['table' => $tableKebabCase, 'id' => $id]);

                // On parcourt la liste des attributs à afficher et on récupère à chaque fois la valeur correspondante
                // On les range dans le tableau $collect->afficher[] avec des index numériques
                $afficher = [];
                for ($i = 0; $i < $nbAttributs; $i++) {
                    $attr = $attribut[$i];
                    $contenu = $instance->$attr;

                    // Si l'attribut attribut_crud_table_id est renseigné, il faut donc récupérer
                    // le 'nom' de cette foreign key grace à son modele (info présente dans la table gestion_tables)
                    if(isset($listeTableAttribut[$i])){
                        $contenu = $listeTableAttribut[$i]->where('id', $contenu)->first()->crud_name;
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
