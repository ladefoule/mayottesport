<?php
    echo "SCRIPT DE GENERATION DE LA SAISON 2021 DE REGIONAL 3 POULE SUD DE FOOTBALL<br>";

    $saisonId = 228; //A MODIFIER EN FONCTION
    $heure = '15:00';

    //  27  // CHOUNGUI FC
    //  23  // FC CHICONI
    //  24  // FC KANI-BE
    //  62  // FC SOHOA
    //  21  // L'ESPERANCE D'ILONI
    //  7   // MIRACLE DU SUD
    //  14  // RC BARAKANI
    //  60  // TCO MAMOUDZOU
    //  8   // US OUANGANI
    //  54  // USC LABATTOIR
    //  51  // VCO VAHIBE
    //  55  // VSS HAGNOUNDROU

    $rencontres = [
        [
            [21,7],
            [54,8],
            [14,62],
            [23,55],
            [24,60],
            [27,51]
        ],
        [
            [7,54],
            [62,21],
            [8,23],
            [60,14],
            [55,27],
            [51,24]
        ],
        [
            [14,51],
            [24,55],
            [7,62],
            [23,54],
            [21,60],
            [27,8]
        ],
        [
            [54,27],
            [51,21],
            [8,24],
            [55,14],
            [23,7],
            [60,62]
        ],
        [
            [7,60],
            [27,23],
            [62,51],
            [24,54],
            [21,55],
            [14,8]
        ],
        [
            [51,60],
            [23,24],
            [55,62],
            [54,14],
            [8,21],
            [27,7]
        ],
        [
            [7,51],
            [24,27],
            [60,55],
            [14,23],
            [62,8],
            [21,54]
        ],
        [
            [24,7],
            [55,51],
            [27,14],
            [8,60],
            [23,21],
            [54,62]
        ],
        [
            [60,54],
            [62,23],
            [7,55],
            [14,24],
            [51,8],
            [21,27]
        ],
        [
            [14,7],
            [8,55],
            [24,21],
            [54,51],
            [27,62],
            [23,60]
        ],
        [
            [62,24],
            [51,23],
            [60,27],
            [7,8],
            [21,14],
            [55,54]
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
