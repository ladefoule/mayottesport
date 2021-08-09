<?php
    echo "SCRIPT DE GENERATION DE LA SAISON 2021 DE REGIONAL 3 POULE SUD DE FOOTBALL<br>";

    $saisonId = 228; //A MODIFIER EN FONCTION
    $heure = '15:00';

    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
    //

    $rencontres = [
        [
            [,],
            [,],
            [,],
            [,],
            [,],
            [,]
        ],
        [
            [,],
            [,],
            [,],
            [,],
            [,],
            [,]
        ],
        [
            [,],
            [,],
            [,],
            [,],
            [,],
            [,]
        ],
        [
            [,],
            [,],
            [,],
            [,],
            [,],
            [,]
        ],
        [
            [,],
            [,],
            [,],
            [,],
            [,],
            [,]
        ],
        [
            [,],
            [,],
            [,],
            [,],
            [,],
            [,]
        ],
        [
            [,],
            [,],
            [,],
            [,],
            [,],
            [,]
        ],
        [
            [,],
            [,],
            [,],
            [,],
            [,],
            [,]
        ],
        [
            [,],
            [,],
            [,],
            [,],
            [,],
            [,]
        ],
        [
            [,],
            [,],
            [,],
            [,],
            [,],
            [,]
        ],
        [
            [,],
            [,],
            [,],
            [,],
            [,],
            [,]
        ]
    ];

    $diffAllerRetour = 11; // Différence en nombre de jours entre le match aller et le retour

    $donnees = [
        'heure' => $heure,
        'saisonId' => $saisonId,
        'rencontres' => $rencontres,
        'diffAllerRetour' => $diffAllerRetour,
    ];

    genererCalendrier($donnees);

    echo "<br>FIN DE L'EXECUTION";
?>
