<?php
use App\Role;
use App\Match;
use App\Journee;
use App\SaisonEquipe;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

/**
 * La liste des correspondances entre l'id (information dans la table crud_attribut_infos) et sa signification.
 *
 * @return \Illuminate\Support\Collection
 */
function infosAttributCrud()
{
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', config('app.url') . "/json/infos-crud-attributs.json", ['timeout' => 2]);
    return collect(json_decode($response->getBody()->getContents()));
}

/**
 * La liste des correspondances entre l'id (information dans la table match_infos) et sa signification.
 *
 * @return \Illuminate\Support\Collection
 */
function infosMatch()
{
    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', config('app.url') . "/json/infos-matches.json", ['timeout' => 2]);
    return collect(json_decode($response->getBody()->getContents()));
}

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
    return str_replace([' ', '/', '_', '\\', '\'', '\"'], '-', Str::lower(stripAccents($str)));
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
 * On vérifie si le role de l'utilisateur est présent ou non dans les $roles
 *
 * @param array $roles
 * @return boolean
 */
function checkPermission(array $roles)
{
    // S'il n'y a pas encore de connexion
    if (auth()->user() == null)
        return false;

    $userRole = auth()->user()->role->nom;

    foreach ($roles as $role)
        if ($role == $userRole)
            return true;

    return false;
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
        SaisonEquipe::create([
            'equipe_id' => $equipeId,
            'saison_id' => $saisonId
        ]);
    }

    // Une heure. Par défaut vaut 15:00
    $heure = $donnees['heure'] ?? '15:00';

    // Un tableau de 2 dimensions qui contient tous les matches
    /*
    $rencontres = [
        [], // L'index 0 contient un tableau vide pour éviter d'insérer la Journée 0
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

    // Si les matches retours ne sont pas symétriques, alors on doit fournir la correspondance entre la journée aller et retour
    // Ex : $corr = [1 => 26, 2 => 14, 3 => 15, ...]
    $correspondanceAllerRetour = $donnees['correspondanceAllerRetour'] ?? [];

    foreach ($rencontres as $journeeNumero => $matchesMemeJournee) {
        foreach ($matchesMemeJournee as $rencontre) {
            /* ALLER */
            // $journee = DB::table('journees')->select('journees.*')->where(['journee_numero' => $journeeNumero, 'saison_id' => $saisonId])->get()->first();
            $journee = Journee::firstWhere(['numero' => $journeeNumero, 'saison_id' => $saisonId]);
            $date = $journee->date;
            $idJournee = $journee->id;

            $match = [
                'date' => $date,
                'heure' => $heure,
                'journee_id' => $idJournee,
                'equipe_id_dom' => $rencontre[0],
                'equipe_id_ext' => $rencontre[1],
                'terrain_id' => $terrains[$rencontre[0]],
                'uniqid' => uniqid()
                // 'uuid' => rand(config('constant.UUID_MIN'), config('constant.UUID_MAX'))
            ];

            Match::create($match);

            // Si le tableau de correspondance est précisé alors on l'applique, sinon on applique une diff aller retour unique pour tous les matches
            $diffAllerRetour = $correspondanceAllerRetour[$journeeNumero] ?? $diffAllerRetour;

            /* RETOUR */
            $journee = Journee::firstWhere(['numero' => $journeeNumero + $diffAllerRetour, 'saison_id' => $saisonId]);
            $date = $journee->date;
            $idJournee = $journee->id;

            $match = [
                'date' => $date,
                'heure' => $heure,
                'journee_id' => $idJournee,
                'equipe_id_dom' => $rencontre[1],
                'equipe_id_ext' => $rencontre[0],
                'terrain_id' => $terrains[$rencontre[1]],
                'uniqid' => uniqid()
                // 'uuid' => rand(config('constant.UUID_MIN'), config('constant.UUID_MAX'))
            ];

            Match::create($match);
        }
    }
}
