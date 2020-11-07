<?php
// use Illuminate\Support\Facades\Config;
return [
    'activer_cache' => false, // Mettre à false pour désactiver la mise en cache,

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
        1 => "forfait_eq_dom",
        2 => "forfait_eq_ext",
        3 => "penalite_eq_dom",
        4 => "penalite_eq_ext"
    ],
    "type-competition" => [
        1 => "championnat",
        2 => "coupe"
    ],
    "bareme" => [
        1 => "victoire_3_0"
    ]
];
