<?php
use App\Cache;
use App\Match;
use App\Sport;
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
 * Transforme la chaine $str en kebabCase et enlève les accents
 *
 * @param string $str
 * @return string
 */
function strToUrl(string $str)
{
    return str_replace([' ', '/', '_', '\\', '\'', '\"', '(', ')'], '-', Str::lower(stripAccents($str)));
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
 * Renvoie le nom du model correspondant à la table (nom de la table en snake_case).
 *
 * @param string $table
 * @return string
 */
function modelName(string $table)
{
    return Str::ucfirst(Str::camel(Str::singular($table)));
}

/**
 * On vérifie si le role de l'utilisateur connecté est présent ou non dans les $roles
 *
 * @param array $roles
 * @return boolean
 */
function checkPermission(array $roles)
{
    // S'il n'y a pas encore de connexion
    if (auth()->user() == null)
        return false;

    $userRole = index('roles')[auth()->user()->role_id]->nom;

    foreach ($roles as $role)
        if ($role == $userRole)
            return true;

    return false;
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
 * Liste de tous les éléments de la table.
 *
 * @param string $table - Table en camel_case
 * @return \Illuminate\Database\Eloquent\Collection
 */
function index(string $table)
{
    $key = "index-" . str_replace('_', '-', $table);
    if (!Config::get('constant.activer_cache'))
        Cache::forget($key);

    if (Cache::has($key))
        return Cache::get($key);
    else
        return Cache::rememberForever($key, function () use($table){
            // dd('ICI');
            return CrudTable::whereNom($table)->firstOrFail()->index();
        });
}

/**
 * Génère tous les matches d'une saison dans la bdd
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
