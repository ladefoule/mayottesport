<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

use App\Cache;
use App\Match;
use App\Bareme;
use App\Saison;
use App\Journee;
use App\CrudTable;
use App\EquipeSaison;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

/**
 * Revoie '1ère journée' si $numero = 1, Xème journée si $numero > 1, false dans les autres cas de figure
 *
 * @param  int $numero
 * @return void|false
 */
function niemeJournee(int $numero)
{
    if($numero <= 0) return false;
    return ($numero == 1 ? '1ère' : $numero.'ème') . ' journée';
}

/**
 * Enlèvement des accents.
 *
 * @param string $str
 * @return string
 */
function stripAccents(string $str) {
    return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

/**
 * Renvoie le nom du model correspondant à la table.
 *
 * @param string $table - nom de la table en snake_case
 * @return string
 */
function modelName(string $table)
{
    return Str::ucfirst(Str::camel(Str::singular($table)));
}

/**
 * Style procédural de la méthode annee() de la classe Saison
 *
 * @param int $debut
 * @param int $fin
 * @param string $separateur
 * @return string
 */
function annee(int $debut, int $fin, string $separateur = '-')
{
    return ($debut == $fin) ? $debut : $debut . $separateur . $fin;
}

/**
 * Teste si l'équipe possède un fanion présent dans le repertoire app/public/img/fanion.
 * Dans le cas ou il existe on renvoie le lien complet vers celui-ci.
 * Sinon on renvoie le lien vers le fanion par défaut.
 *
 * @return string
 */
function fanion($equipeId)
{
    $fanion = 'foot-' . $equipeId;
    $exists = Storage::disk('public')->exists('img/fanion/' . $fanion . '.png');
    if($exists == false)
        $fanion = "defaut-2";

    return config('app.url') . "/storage/img/fanion/" . $fanion . '.png';
}

/**
     * Suppression des caches
     *
     * @param string $table - en camel_case
     * @param int $id
     * @return void
     */
    function forgetCaches(string $table, int $id)
    {
    Log::info(" -------- Controller Crud : forgetCaches -------- ");

    // On supprime le cache index de la table
    Cache::forget('index-' . Str::slug($table));
    Cache::forget('indexcrud-' . Str::slug($table));

    // On supprime les caches directement liés au match, à la journée ou à la saison
    if(in_array($table, ['matches', 'journees', 'saisons'])){
        if($table == 'matches'){
            $match = Match::findOrFail($id);
            $journee = $match->journee;
            $saison = $journee->saison;
        }else if($table == 'journees'){
            $journee = Journee::findOrFail($id);
            $saison = $journee->saison;
        }else
            $saison = Saison::findOrFail($id);

        if(isset($match))
            Cache::forget("match-" . $match->uniqid);

        if(isset($journee))
            Cache::forget("journee-" . $journee->id);

        if(isset($saison))
            Cache::forget("saison-" . $saison->id);

    // Suppression de tous les caches saisons liés au barème
    }else if($table == 'baremes'){
        $bareme = Bareme::findOrFail($id);
        $saisons = $bareme->saisons;
        foreach ($saisons as $saisonTemp)
            Cache::forget("saison-".$saisonTemp->id);

    // On supprime les caches des attributs visibles si on a effectuer une action sur les tables CRUD
    }else if(in_array($table, ['crud_tables', 'crud_attributs', 'crud_attribut_infos'])){
        Cache::forget("attributs-visibles-$table-index");
        Cache::forget("attributs-visibles-$table-create");
        Cache::forget("attributs-visibles-$table-show");
    }
}

/**
 * Rechargement de tous les caches index des tables qui utilisent les données de la $table
 * On ne doit recharger le cache que SI ET SEULEMENT SI les données des tables sont utilisées dans la génération des attributs nom ou crud_name
 * Ex: Saison->crudname = Competititon->crud_name . annee() ==> SEUL la table competitions peut engendrer le rechargement de la table saisons
 *
 * @param string $table - en kebab-case
 * @return void
 */
function refreshCachesLies(string $table){
    Log::info("Rechargement des caches des tables utilisant les données de : $table");
    $tablesLiees = config('constant.caches-lies')[$table] ?? [];
    foreach ($tablesLiees as $tableSlug){
        if(isset(config('constant.caches-lies')[$tableSlug]))
            refreshCachesLies($tableSlug);

        Cache::forget('index-' . $tableSlug);
        index(str_replace('-', '_', $tableSlug));
        // CrudTable::where('nom', str_replace('-', '_', $tableSlug))->firstOrFail()->index();
    }
}

/**
 * Fonction de comparaison pour générer les classements
 *
 * @param array $a
 * @param array $b
 * @return int
 */
function compare($a, $b)
{
    if ($a['points'] == $b['points']){
        if($a['diff'] == $b['diff']){
            if($a['marques'] == $b['marques'])
                return 0;
            return ($a['marques'] < $b['marques']) ? 1 : -1;
        }
        return ($a['diff'] < $b['diff']) ? 1 : -1;
    }
    return ($a['points'] < $b['points']) ? 1 : -1;
}

/**
 * Style procédural de la méthode infos() de la classe CrudTable : Liste de tous les éléments de la table.
 *
 * @param string $table - Table en camel_case
 * @return \Illuminate\Database\Eloquent\Collection
 */
function index(string $table)
{
    $key = "index-" . Str::slug($table);
    if (!Config::get('constant.activer_cache'))
        Cache::forget($key);

    if (Cache::has($key))
        return Cache::get($key);
    else
        return Cache::rememberForever($key, function () use($table){
            return CrudTable::whereNom($table)->firstOrFail()->index();
        });
}

/**
 * Style procédural de la méthode infos() de la classe CrudTable
 * Liste de tous les éléments de la table (avec les infos sur les attributs à afficher dans le CRUD : position, type, max, ...).
 *
 * @param string $table - Table en camel_case
 * @return \Illuminate\Database\Eloquent\Collection
 */
function indexCrud(string $table)
{
    $key = "indexcrud-" . Str::slug($table);
    if (!Config::get('constant.activer_cache'))
        Cache::forget($key);

    if (Cache::has($key))
        return Cache::get($key);
    else
        return Cache::rememberForever($key, function () use($table){
            return CrudTable::whereNom($table)->firstOrFail()->indexCrud();
        });
}

/**
 * Style procédural de la méthode infos() de la classe Journee
 * Renvoie une collection qui contient les matches de la journée ainsi que le render (le html de l'affichage du calendrier)
 *
 * @param int $journeeId
 * @return \Illuminate\Database\Eloquent\Collection
 */
function journee(int $journeeId)
{
    $key = "journee-" . $journeeId;
    if (!Config::get('constant.activer_cache'))
        Cache::forget($key);

    if (Cache::has($key))
        return Cache::get($key);
    else
        return Cache::rememberForever($key, function () use($journeeId){
            return Journee::findOrFail($journeeId)->infos();
        });
}

/**
 * Style procédural de la méthode infos() de la classe Saison
 * Retourne une collection qui contient le classement si c'est un championnat et d'autres infos sur la saison
 *
 * @param int $journeeId
 * @return \Illuminate\Database\Eloquent\Collection
 */
function saison(int $saisonId)
{
    $key = "saison-" . $saisonId;
    if (!Config::get('constant.activer_cache'))
        Cache::forget($key);

    if (Cache::has($key))
        return Cache::get($key);
    else
        return Cache::rememberForever($key, function () use($saisonId){
            return Saison::findOrFail($saisonId)->infos();
        });
}

/**
 * Style procédural de la méthode infos() de la classe Match
 * Retourne une collection contenant toutes les infos sur le match : competition, saison, equipes, urls, ...
 *
 * @param string $matchUniqid
 * @return \Illuminate\Database\Eloquent\Collection
 */
function match(string $matchUniqid)
{
    $key = "match-" . $matchUniqid;
    if (!Config::get('constant.activer_cache'))
        Cache::forget($key);

    if (Cache::has($key))
        return Cache::get($key);
    else
        return Cache::rememberForever($key, function () use($matchUniqid){
            return Match::whereUniqid($matchUniqid)->firstOrFail()->infos();
        });
}

/**
 * Insère tous les matches d'une saison dans la bdd
 *
 * @param array $donnees
 * @return void
 */
function genererCalendrier($donnees)
{
    // L'ID de la saison (la saison sera au préalable créée dans la bdd)
    $saisonId = $donnees['saisonId'];

    // Un tableau qui contiendra l'id du terrain pour chaque équipe : $terrains = [$idEq1 => $idTerEq1, $idEq2 => $idTerEq2, ...]
    $terrains = $donnees['terrains'];

    // On insère dans la table associatif le lien equipe/saison pour toutes les équipes
    foreach ($terrains as $equipeId => $terrain) {
        EquipeSaison::create([
            'equipe_id' => $equipeId,
            'saison_id' => $saisonId
        ]);
    }

    // Une heure. Par défaut vaut 15:00
    $heure = $donnees['heure'] ?? '15:00';

    // Un tableau de 2 dimensions qui contient tous les matches
    /*
    $rencontres = [
        [   JOURNEE 1
            [16, 17],
            [12, 13], L'équipe avec id 12 reçoit l'équipe avec l'id 13
            [14, 15],
            [18, 19],
            [20, 21],
            [22, 23]
        ],
        [   JOURNEE 2
            [21, 16],
            [13, 14],
            [17, 12],
            [15, 18],
            [19, 22],
            [23, 20]
        ],
        .
        .
        .
    ];
    */
    $rencontres = $donnees['rencontres'];

    // Différence entre l'aller et le retour : Si 12 équipes alors $diffAllerRetour = 11 cad J1 et J12 correspondront aux mêmes matches
    $diffAllerRetour = $donnees['diffAllerRetour'] ?? 11;

    // Si les matches allers/retours ne sont pas symétriques, alors on doit fournir la correspondance entre la journée aller et retour
    // Ex : $corr = [1 => 26, 2 => 14, 3 => 15, ...]
    $correspondanceAllerRetour = $donnees['correspondanceAllerRetour'] ?? [];

    foreach ($rencontres as $i => $matchesMemeJournee) {
        $journeeNumero = $i+1;
        /* ALLER */
        $journeeAller = Journee::firstWhere(['numero' => $journeeNumero, 'saison_id' => $saisonId]);
        if(! $journeeAller) // Si la journée n'est pas encore insérée dans la base, alors on le fait ici
            $journeeAller = Journee::create(['numero' => $journeeNumero, 'date' => date('Y-m-d'), 'saison_id' => $saisonId, 'created_at' => now(), 'updated_at' => now()]);

        // Si le tableau de correspondance est précisé alors on l'applique, sinon on applique une diff aller retour unique pour tous les matches
        $diffAllerRetour = $correspondanceAllerRetour[$journeeNumero] ?? $diffAllerRetour;

        /* RETOUR */
        $journeeRetour = Journee::firstWhere(['numero' => $journeeNumero + $diffAllerRetour, 'saison_id' => $saisonId]);
        if(! $journeeRetour) // Si la journée n'est pas encore insérée dans la base, alors on le fait ici
            $journeeRetour = Journee::create(['numero' => $journeeNumero + $diffAllerRetour, 'date' => date('Y-m-d'), 'saison_id' => $saisonId, 'created_at' => now(), 'updated_at' => now()]);

        foreach ($matchesMemeJournee as $rencontre) {
            $equipeDomId = $rencontre[0];
            $equipeExtId = $rencontre[1];
            $matchAller = [
                'date' => $journeeAller->date,
                'heure' => $heure,
                'journee_id' => $journeeAller->id,
                'equipe_id_dom' => $equipeDomId,
                'equipe_id_ext' => $equipeExtId,
                'terrain_id' => $terrains[$equipeDomId],
                'uniqid' => uniqid()
                // 'uuid' => rand(config('constant.UUID_MIN'), config('constant.UUID_MAX'))
            ];

            $matchAller = Match::create($matchAller);
            Log::info("Ajout d'un match : " . $matchAller);

            $equipeDomId = $rencontre[1];
            $equipeExtId = $rencontre[0];
            $matchRetour = [
                'date' => $journeeRetour->date,
                'heure' => $heure,
                'journee_id' => $journeeRetour->id,
                'equipe_id_dom' => $equipeDomId,
                'equipe_id_ext' => $equipeExtId,
                'terrain_id' => $terrains[$equipeDomId],
                'uniqid' => uniqid()
                // 'uuid' => rand(config('constant.UUID_MIN'), config('constant.UUID_MAX'))
            ];

            $matchRetour = Match::create($matchRetour);
            Log::info("Ajout d'un match : " . $matchRetour);
        }


    }
}
