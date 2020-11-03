<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

header('Content-Type: application/json; charset=utf-8');

$table = request()['table'] ?? '';
$table = str_replace('-', '_', $table);

// Les tables sur lesquelles on peut faire de l'ajax
// Si 2 tables sont liées à une mm table parent, alors l'une des 2 doit se située après la table parent
// sinon ça engendre un conflit au niveau de la jointure (ambiguité de la foreign key)
$joins = [
    'competitions' => ['sports'],
    'equipes' => ['saisons', 'sports'],
    'baremes' => ['sports'],
    'saisons' => ['competitions', 'sports', 'baremes'],
    'journees' => ['saisons', 'competitions', 'sports', 'baremes'],
    'matches' => ['journees', 'saisons', 'competitions', 'sports', 'baremes', 'equipes'],
];

// Si la table n'est pas présente dans le tableau $joins
if(key_exists($table, $joins) == false){
    header('HTTP/1.0 403');
    echo json_encode('');
    exit();
}

$rules = [];
foreach ($joins[$table] as $tableJoin) {
    $attribut = Str::singular($tableJoin) . '_id';
    $rules[$attribut] = "nullable|integer|exists:$tableJoin,id";
}

$validator = Validator::make(request()->all(), $rules);
if ($validator->fails()) {
    header('HTTP/1.0 400');
    echo json_encode('');
    exit();
}

$request = $validator->validate(); // On récupère le tableau filtré de la requète
$liste = DB::table($table); // Initialisation de la requète

// On lie toutes les jointures
foreach ($joins[$table] as $tableJoin){
    $attributJoin = Str::singular($tableJoin) . '_id';
    if($table == 'matches' && $tableJoin == 'equipes'){
        // Jointure spécial pour les équipes car elles peuvent appartenir à deux champs equipe_id_dom/equipe_id_ext
        if(isset($request['equipe_id']))
            $liste = $liste->join('equipes', function ($join) {
                    $join->on('equipe_id_ext', '=', 'equipes.id')->orOn('equipe_id_dom', '=', 'equipes.id');
                });
    }else if($table == 'equipes' && $tableJoin == 'saisons')
        // Mon algorithme ne permet pas de lier 2 tables séparées par une table pivot (d'où l'utilisation du leftJoin)
        $liste = $liste->leftJoin('saison_equipe', 'equipe_id', '=', 'equipes.id')
                ->leftJoin('saisons', 'saison_id', '=', 'saisons.id');
    else
        $liste = $liste->join($tableJoin, $attributJoin, '=', "$tableJoin.id");
}

$liste = $liste->select("$table.id");
$triDefaut = App\CrudTable::firstWhere('nom', $table)->tri_defaut;
if($triDefaut)
    $liste->orderBy($table . '.' . $triDefaut);

foreach ($request as $attribut => $valeur){
    // Les attributs sont de ce type sport_id, pour éviter une ambiguité dans les requètes, on les remplace dans le where par sports.id
    $attribut = Str::plural(Str::before($attribut, '_id')).'.'.'id';
    if($valeur)
        $liste = $liste->where($attribut, $valeur);
}

$liste = $liste->get();
if(count($liste) == 0){
    header('HTTP/1.0 404');
    echo json_encode('');
    exit();
}

$modele = 'App\\' . modelName($table);
foreach ($liste as $key => $instance) {
    $instance = $modele::find($instance->id);
    $liste[$key] = $instance;
    $liste[$key]['nom'] = $instance->nom; // Toutes les tables doivent avoir un attribut nom (natif ou non)

    if($table == "matches")
        $liste[$key]['journee'] = 'J' . str_pad($instance->journee->numero, 2, "0", STR_PAD_LEFT);
}

header('HTTP/1.0 200');
echo json_encode($liste);
exit(); //Pour éviter d'interpréter la dernière ligne (que VS Code rajoute automatiquement)
