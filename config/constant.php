<?php
// use Illuminate\Support\Facades\Config;
return [
    'activer_cache' => env('MISE_EN_CACHE', false),

    'boutons' => [
        'supprimer' => '<i class="fas fa-trash-alt"></i>',
        'ajouter' => '<i class="fas fa-plus"></i>',
        'ajouter_cercle' => '<i class="fas fa-plus-circle"></i>',
        'voir' => '<i class="fas fa-eye"></i>',
        'editer' => '<i class="fas fa-edit"></i>',
        'lister' => '<i class="fas fa-list-ul"></i>',
        'database' => '<i class="fas fa-database"></i>'
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

    "bareme" => [
        1 => ["victoire_3_0", "Victoire 3-0"]
    ]
];
