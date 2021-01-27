<?php 
    echo "SCRIPT DE GENERATION DE LA SAISON 2020/2021 DE PRENAT MASC, POULE A DE HANDBALL<br>";

    $saisonId = 180; A MODIFIER EN FONCTION
    // $heure = '';
    // $avecMatchesRetour = 1;

    // TSINGONI        314
    // TSIMKOURA       263
    // PASSAMINTY      272
    // BOUENi          271
    // TCO             302
    // KOUNGOU         284
    // ACOUA           266
    // KANI-KELI       264

    $rencontres = [
        [// 1ère journée
            [314,263],
            [272,271],
            [302,284],
            [266,264],
        ],
        [
            [263,272],
            [284,314],
            [271,266],
            [264,302],
        ],
        [
            [263,284],
            [266,272],
            [314,264],
            [302,271],
        ],
        [
            [266,263],
            [264,284],
            [272,302],
            [271,314],
        ],
        [
            [263,264],
            [314,272],
            [302,266],
            [284,271],
        ],
        [
            [271,264],
            [266,314],
            [272,284],
            [302,263],
        ],
        [
            [263,271],
            [314,302],
            [264,272],
            [284,266],
        ],
    ];

    $diffAllerRetour = 7; // Différence en nombre de jours entre le match aller et le retour

    $donnees = [
        // 'heure' => $heure,
        'saisonId' => $saisonId,
        'rencontres' => $rencontres,
        'diffAllerRetour' => $diffAllerRetour,
        // 'avecMatchesRetour' => $avecMatchesRetour
    ];

    genererCalendrier($donnees);

    echo "<br>FIN DE L'EXECUTION";
?>