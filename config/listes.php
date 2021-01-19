<?php
// use Illuminate\Support\Facades\Config;
return [
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
        'football' => '<i class="icon-soccer-ball"></i>',
        'user' => '<i class="icon-user"></i>',
        'user-add' => '<i class="icon-user-add"></i>',
    ],

    /* $key => $values */
    /* Si on met à jour le cache $key alors on doit recharger tous les caches $values */
    'caches-lies' => [
        'baremes' => ['bareme_infos'],
        'competitions' => ['saisons'],
        'crud-attributs' => ['crud_attribut_infos'],
        'crud-tables' => ['crud_attributs'],
        // 'matches' => ['modifs', 'match_infos'],
        'saisons' => ['journees', /* 'equipe_saison' */],
        'sports' => ['equipes', 'competitions', 'baremes'],
        'users' => ['modifs'],
        'villes' => ['terrains'],
    ],

    "priorites-articles" => [
        1 => ['normale', 'Normale'],
        2 => ['elevee', 'Elevée'],
        3 => ['absolue', 'Absolue'],
    ],

    "proprietes-baremes" => [
        1 => ["victoire_3_0", "Victoire 3-0"],
        2 => ["victoire_3_1", "Victoire 3-1"],
        3 => ["victoire_3_2", "Victoire 3-2"],
        4 => ["defaite_0_3", "Défaite 0-3"],
        5 => ["defaite_1_3", "Défaite 1-3"],
        6 => ["defaite_2_3", "Défaite 2-3"],
    ],

    "proprietes-crud-attributs" => [
        1 => ["index_position", "Position sur la page liste"],
        2 => ["show_position", "Position sur la page vue"],
        3 => ["create_position", "Position sur la page ajout/édition"],
        4 => ["input_type", "Type de l'input"],
        5 => ["pattern", "RegEx"],
        6 => ["min", "Minimum"],
        7 => ["max", "Maximum"],
        8 => ["select_liste", "Liste à afficher"]
    ],

    "proprietes-matches" => [
        1 => ["forfait_eq_dom", "Forfait (dom.)"],
        2 => ["forfait_eq_ext", "Forfait (ext.)"],
        3 => ["penalite_eq_dom", "Pénalité (dom.)"],
        4 => ["penalite_eq_ext", "Pénalité (ext.)"],
        5 => ["avec_tirs_au_but", "Avec tirs au but?"],
        6 => ["tab_eq_dom", "Tirs au but (dom.)"],
        7 => ["tab_eq_ext", "Tirs au but (ext.)"],
    ],

    // "routes-sans-background" => [
    //    'article.select',
    //     'login',
    //     'register',
    //     'profil',
    //     'profil.update',
    //     'journees.multi.select',
    //     'journees.multi.edit',
    //     'journees.multi.show',
    // ],

    "tables-avec-colonne-uniqid" => ['matches', 'equipes', 'articles'],

    "tables-gestion-crud" => [
        'crud_tables', 'crud_attributs', 'crud_attribut_infos'
    ],

    "tables-non-crudables" => [
        'migrations', 'password_resets', 'matches', 'failed_jobs', 'jobs', 'equipe_saison', 'article_sport', 'article_equipe', 'article_competition'
    ],

    "tables-superadmin" => [
        'roles', 'users', 'matches'
    ],

    "types-competitions" => [
        1 => ["championnat", "Championnat"],
        2 => ["coupe", "Coupe"]
    ],

    "types-journees" => [
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

    "types-modifs" => [
        1 => ["score", "Score"],
        2 => ["horaire", "Horaire"]
    ],
];
