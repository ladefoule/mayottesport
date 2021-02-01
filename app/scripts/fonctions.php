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
use App\Article;
use App\Journee;
use App\CrudTable;
use App\EquipeSaison;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
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
    if ($numero <= 0) return false;
    return ($numero == 1 ? '1ère' : $numero . 'ème') . ' journée';
}

/**
 * Enlèvement des accents.
 *
 * @param string $str
 * @return string
 */
function stripAccents(string $str)
{
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
 * On vérifie si l'utilisateur a le droit de modifier le résultats du match ou non
 *
 * @param Match
 * @param User
 * @return bool
 */
function accesModifResultat($match, $user)
{
    if (! $user)
        return false;

    $niveauUser = $user->role->niveau;
    $lastUser = $match->user;
    $niveauLastUser = $lastUser ? $lastUser->role->niveau : 0;

    // Les conditions d'accès refusé
    // - Journée bloquée
    // - Match bloqué
    // - Date du match > aujourd'hui
    // - Niveau de l'utilisateur ayant modifié le match > Niveau du membre connecté (ne concerne pas les admins)
    if ($match->journee->acces_bloque || $match->bloque || ($niveauLastUser > $niveauUser && $niveauUser < 30) || $match->date > date('Y-m-d'))
        return false;

    return true;
}

/**
 * On vérifie si l'utilisateur a le droit de modifier l'horaire du match ou non
 *
 * @param Match
 * @param User
 * @return bool
 */
function accesModifHoraire($match, $user)
{
    if (! $user)
        return false;

    $niveauUser = $user->role->niveau;

    // Les conditions d'accès refusé
    // - Journée bloquée
    // - Match bloqué
    // - Niveau de l'utilisateur connecté < 20 cad niveau membre (10)
    if ($match->journee->acces_bloque || $match->bloque || $niveauUser < 20)
        return false;

    return true;
}

/**
 * Teste si l'équipe possède un fanion présent dans le repertoire app/public/img/fanion.
 * Dans le cas ou il existe on renvoie le lien complet vers celui-ci.
 * Sinon on renvoie le lien vers le fanion par défaut.
 *
 * @return string
 */
function fanion($equipeUniqid)
{
    $fanion = $equipeUniqid;
    $exists = Storage::disk('public')->exists('img/fanion/' . $fanion . '.png');
    if ($exists == false)
        $fanion = "defaut-2";

    return asset("/storage/img/fanion/" . $fanion . '.png');
}

function imagesList()
{
    $images = Storage::allFiles('public/img');
    foreach ($images as $image) {
        $image = str_replace('public/', 'storage/', $image);
        $images_list[] = [
            'title' => explode('storage/img/', $image)[1],
            'value' => asset($image),
        ];
    }

    return $images_list ?? [];
}

/**
 * Suppression des caches
 *
 * @param string $table - en snake_case
 * @param object $instance
 * @return void
 */
function forgetCaches(string $table, object $instance)
{
    Log::info(" -------- Controller Crud : forgetCaches -------- ");

    $tableSlug = Str::slug($table);
    // On supprime le cache index de la table
    Cache::forget('index-' . $tableSlug);

    // On supprime les caches directement liés au match, à la journée ou à la saison
    if (in_array($table, ['matches', 'journees', 'saisons'])) {
        if ($table == 'matches') {
            $match = $instance;
            $journee = $match->journee;
            $saison = $journee->saison;
        } else if ($table == 'journees') {
            $journee = $instance;
            $saison = $journee->saison;
        } else
            $saison = $instance;

        if (isset($match))
            Cache::forget("match-" . $match->uniqid);

        if (isset($journee))
            Cache::forget("journee-" . $journee->id);

        if (isset($saison))
            Cache::forget("saison-" . $saison->id);

        // Suppression de tous les caches saisons liés au barème
    } else if ($table == 'baremes') {
        $bareme = $instance;
        $saisons = $bareme->saisons;
        foreach ($saisons as $saisonTemp)
            Cache::forget("saison-" . $saisonTemp->id);

   // On supprime les caches des articles
    } else if ($table == 'articles') {
        $article = $instance;
         Cache::forget("article-" . $article->uniqid);
    }
}

/**
 * Rechargement de tous les caches index des tables qui utilisent les données de la $table
 * On ne doit recharger le cache que SI ET SEULEMENT SI les données des tables sont utilisées dans la génération des attributs nom ou crud_name
 * Ex: Saison->crudname = Competititon->crud_name . annee() ==> SEUL la table competitions peut engendrer le rechargement de la table saisons
 *
 * @param string $table - en snake_case
 * @return void
 */
function refreshCachesLies(string $table)
{
    Log::info("Rechargement des caches des tables utilisant les données de : $table");
    $tablesLiees = config('listes.caches-lies')[$table] ?? [];
    foreach ($tablesLiees as $table) {
        if (isset(config('listes.caches-lies')[$table]))
            refreshCachesLies($table);

        Cache::forget('index-' . Str::slug($table));
        index($table);
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
    if ($a['points'] == $b['points']) {
        if ($a['diff'] == $b['diff']) {
            if ($a['marques'] == $b['marques'])
                return 0;
            return ($a['marques'] < $b['marques']) ? 1 : -1;
        }
        return ($a['diff'] < $b['diff']) ? 1 : -1;
    }
    return ($a['points'] < $b['points']) ? 1 : -1;
}

/**
 * Fonction de comparaison pour générer les classements de Volleyball
 *
 * @param array $a
 * @param array $b
 * @return int
 */
function compareVolley($a, $b)
{
    if ($a['points'] == $b['points']) {
        if ($a['coefficient'] == $b['coefficient']) {
            if ($a['marques'] == $b['marques'])
                return 0;
            return ($a['marques'] < $b['marques']) ? 1 : -1;
        }
        return ($a['coefficient'] < $b['coefficient']) ? 1 : -1;
    }
    return ($a['points'] < $b['points']) ? 1 : -1;
}

/**
 * Liste de tous les éléments de la table.
 *
 * @param string $table - Table en camel_case
 * @return \Illuminate\Database\Eloquent\Collection
 */
function index(string $table)
{
    $key = "index-" . Str::slug($table);
    if (Cache::has($key))
        return Cache::get($key);
    else
        return Cache::rememberForever($key, function () use ($table, $key) {
            Log::info('Rechargement du cache : ' . $key);
            $index = [];
            $modele = 'App\\' . modelName($table);
            $instances = $modele::all();

            foreach ($instances as $instance){
                $collect = collect();
                foreach ($instance->getAttributes() as $key => $value)
                    $collect->$key = $value;;

                $collect->nom = $instance->nom ?? '';
                $index[$instance->id] = $collect;
            }

            return collect($index);
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
    if (Cache::has($key))
        return Cache::get($key);
    else
        return Cache::rememberForever($key, function () use ($journeeId) {
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
    if (Cache::has($key))
        return Cache::get($key);
    else
        return Cache::rememberForever($key, function () use ($saisonId) {
            return Saison::findOrFail($saisonId)->infos();
        });
}

/**
 * Style procédural de la méthode infos() de la classe Article
 * Retourne une collection contenant toutes les infos sur l'article
 *
 * @param string $uniqid
 * @return \Illuminate\Database\Eloquent\Collection
 */
function article(string $uniqid)
{
    $key = "article-" . $uniqid;
    if (Cache::has($key))
        return Cache::get($key);
    else
        return Cache::rememberForever($key, function () use ($uniqid) {
            return Article::whereUniqid($uniqid)->firstOrFail()->infos();
        });
}

/**
 * Style procédural de la méthode infos() de la classe Match
 * Retourne une collection contenant toutes les infos sur le match : competition, saison, equipes, urls, ...
 *
 * @param string $uniqid
 * @return \Illuminate\Database\Eloquent\Collection
 */
function match($uniqid)
{
    $key = "match-" . $uniqid;
    if (Cache::has($key))
        return Cache::get($key);
    else
        return Cache::rememberForever($key, function () use ($uniqid) {
            return Match::whereUniqid($uniqid)->firstOrFail()->infos();
        });
}

/**
 * Insère tous les matches d'une saison dans la bdd
 * Les journées doivent être crées dans la base avant de commencer, de même pour la saison
 *
 * @param array $donnees
 * @return void|boolean
 */
function genererCalendrier($donnees)
{
    // L'ID de la saison (la saison sera au préalable créée dans la bdd)
    $saisonId = $donnees['saisonId'];

    // Une heure.
    $heure = $donnees['heure'] ?? NULL;

    // S'il y a ou non des matches retour à générer
    $avecMatchesRetour = $donnees['avecMatchesRetour'] ?? 1;

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
    if(! $rencontres)
        return false;

    // Différence entre l'aller et le retour : Si 12 équipes alors $diffAllerRetour = 11 cad J1 et J12 correspondront aux mêmes matches
    $diffAllerRetour = $donnees['diffAllerRetour'] ?? 11;

    // Si les matches allers/retours ne sont pas symétriques, alors on doit fournir la correspondance entre la journée aller et retour
    // Ex : $corr = [1 => 26, 2 => 14, 3 => 15, ...]
    $correspondanceAllerRetour = $donnees['correspondanceAllerRetour'] ?? [];

    foreach ($rencontres as $i => $matchesMemeJournee) {
        $journeeNumero = $i + 1;
        /* ALLER */
        $journeeAller = Journee::where(['numero' => $journeeNumero, 'saison_id' => $saisonId])->firstOrFail();

        foreach ($matchesMemeJournee as $rencontre) {
            $equipeDomId = $rencontre[0];
            $equipeExtId = $rencontre[1];
            $matchAller = [
                'date' => $journeeAller->date,
                'heure' => $heure,
                'journee_id' => $journeeAller->id,
                'equipe_id_dom' => $equipeDomId,
                'equipe_id_ext' => $equipeExtId,
                'uniqid' => uniqid()
                // 'uuid' => rand(config('listes.UUID_MIN'), config('listes.UUID_MAX'))
            ];

            $matchAller = Match::create($matchAller);
            Log::info("Ajout d'un match : " . $matchAller);

            if($avecMatchesRetour){
                // Si le tableau de correspondance est précisé alors on l'applique, sinon on applique une diff aller retour unique pour tous les matches
                $diffAllerRetour = $correspondanceAllerRetour[$journeeNumero] ?? $diffAllerRetour;

                /* RETOUR */
                $journeeRetour = Journee::where(['numero' => $journeeNumero + $diffAllerRetour, 'saison_id' => $saisonId])->firstOrFail();

                $equipeDomId = $rencontre[1];
                $equipeExtId = $rencontre[0];
                $matchRetour = [
                    'date' => $journeeRetour->date,
                    'heure' => $heure,
                    'journee_id' => $journeeRetour->id,
                    'equipe_id_dom' => $equipeDomId,
                    'equipe_id_ext' => $equipeExtId,
                    'uniqid' => uniqid()
                    // 'uuid' => rand(config('listes.UUID_MIN'), config('listes.UUID_MAX'))
                ];

                $matchRetour = Match::create($matchRetour);
                Log::info("Ajout d'un match : " . $matchRetour);
            }
        }
    }
}
