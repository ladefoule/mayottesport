<?php
// use Illuminate\Support\Facades\Config;
return [
    'activer_cache' => env('MISE_EN_CACHE', true),

    'boutons' => [
        'supprimer' => '<i class="icon-trash-empty"></i>',
        'ajouter' => '<i class="icon-plus"></i>',
        'ajouter_cercle' => '<i class="icon-plus-circled"></i>',
        'voir' => '<i class="icon-eye"></i>',
        'editer' => '<i class="icon-edit"></i>',
        'lister' => '<i class="icon-list-bullet"></i>',
        'database' => '<i class="icon-database"></i>',
        'left' => '<i class="icon-left-open"></i>',
        'right' => '<i class="icon-right-open"></i>',
        'retour' => '<i class="icon-left"></i>',
        'football' => '<i class="icon-soccer-ball"></i>',
    ],

    "crud-attribut" => [
        1 => ["index_position", "Position sur la page liste"],
        2 => ["show_position", "Position sur la page vue"],
        3 => ["create_position", "Position sur la page ajout/édition"],
        4 => ["input_type", "Type de l'input"],
        5 => ["pattern", "RegEx"],
        6 => ["min", "Minimum"],
        7 => ["max", "Maximum"],
        8 => ["select_liste", "Liste à afficher"]
    ],

    "match" => [
        1 => ["forfait_eq_dom", "Forfait équipe dom."],
        2 => ["forfait_eq_ext", "Forfait équipe ext."],
        3 => ["penalite_eq_dom", "Pénalité équipe dom."],
        4 => ["penalite_eq_ext", "Pénalité équipe ext."]
    ],

    "type-competition" => [
        1 => ["championnat", "Championnat"],
        2 => ["coupe", "Coupe"]
    ],

    "journees" => [
        1 => ['finale', 'Finale'],
        2 => ['demi-finales', 'Demi-finales'],
        3 => ['quarts-de-finales', 'Quarts de finale'],
        4 => ['8emes-de-finales', '1/8ème de finale'],
        5 => ['16emes-de-finales', '1/16ème de finale'],
        6 => ['32eme-de-finales', '1/32ème de finale'],
        11 => ['1er-tour', '1er tour'],
        12 => ['2eme-tour', '2ème tour'],
        13 => ['3eme-tour', '3ème tour'],
        14 => ['4eme-tour', '4ème tour'],
        15 => ['5eme-tour', '5ème tour'],
        16 => ['6eme-tour', '6ème tour'],
        17 => ['7eme-tour', '7ème tour'],
        18 => ['8eme-tour', '8ème tour'],
        19 => ['9eme-tour', '9ème tour'],
    ],

    "bareme" => [
        1 => ["victoire_3_0", "Victoire 3-0"]
    ],

    "superadmin-tables" => [
        'crud_tables', 'crud_attributs', 'crud_attribut_infos', 'roles', 'users'
    ],

    "tables-non-crudables" => [
        'migrations', 'password_resets', 'failed_jobs', 'jobs'
    ],

    'sports-position' => [
        'football' => ['sport_id' => 1],
        'handball' => ['sport_id' => 2],
        'basketball' => ['sport_id' => 3],
        'volleyball' => ['sport_id' => 4],
    ],

    'competitions-position' => [
        'regional-1' => ['sport_id' => 1, 'competition_id' => 1, 'home' => 1, 'index' => 1],
        'regional-2' => ['sport_id' => 1, 'competition_id' => 2, 'home' => 2, 'index' => 2],
        'coupe-de-mayotte' => ['sport_id' => 1, 'competition_id' => 3, 'home' => 3, 'index' => 3],
        'coupe-de-france' => ['sport_id' => 1, 'competition_id' => 4, 'home' => 4, 'index' => 4],
    ],

    /* $key => $values */
    /* Si on met à jour le cache $key alors on doit recharger tous les caches $values */
    'caches-lies' => [
        'equipes' => ['matches', 'equipe-saison'],
        'saisons' => ['journees', 'equipe-saison'],
        'competitions' => ['saisons'],
        'villes' => ['terrains'],
        'sports' => ['equipes', 'competitions', 'baremes'],
        'crud-attributs' => ['crud-attribut-infos'],
        'crud-tables' => ['crud-attributs'],
    ],
];
