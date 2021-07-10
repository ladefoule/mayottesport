<?php
    echo "SCRIPT DE GENERATION DE LA SAISON 2021 DE REGIONAL 1 DE FOOTBALL<br>";

    $saisonId = 1; //A MODIFIER EN FONCTION
    $heure = '16:15';
    // $avecMatchesRetour = 1;

    //  57  // ANTEOU
    //  1   // AS SADA
    //  61  // BANDRABOUA
    //  45  // DIABLES NOIRS
    //  13  // JUMEAUX
    //  12  // KAWENI
    //  16  // KOUNGOU
    //  26  // MOINATRINDRI
    //  3   // MTSAPERE
    //  4   // ROSADOR
    //  18  // TCHANGA
    //  5   // UCS SADA

    $rencontres = [
        [
            [13,26],
            [3,16],
            [5,1],
            [4,57],
            [18,12],
            [61,45],
        ],
        [
            [26,3],
            [1,13],
            [16,4],
            [12,5],
            [57,61],
            [45,18],
        ],
        [
            [26,1],
            [4,3],
            [13,12],
            [61,16],
            [5,45],
            [18,57],
        ],
        [
            [4,26],
            [12,1],
            [3,61],
            [45,13],
            [16,18],
            [57,5],
        ],
        [
            [26,12],
            [61,4],
            [1,45],
            [18,3],
            [13,57],
            [5,16],
        ],
        [
            [61,26],
            [45,12],
            [4,18],
            [57,1],
            [3,5],
            [16,13],
        ],
        [
            [26,45],
            [18,61],
            [12,57],
            [5,4],
            [1,16],
            [13,3],
        ],
        [
            [18,26],
            [57,45],
            [61,5],
            [16,12],
            [4,13],
            [3,1],
        ],
        [
            [26,57],
            [5,18],
            [45,16],
            [13,61],
            [12,3],
            [1,4],
        ],
        [
            [5,26],
            [16,57],
            [18,13],
            [3,45],
            [61,1],
            [4,12],
        ],
        [
            [26,16],
            [13,5],
            [57,3],
            [1,18],
            [45,4],
            [12,61],
        ],
    ];

    $diffAllerRetour = 11; // Différence en nombre de jours entre le match aller et le retour

    $donnees = [
        'heure' => $heure,
        'saisonId' => $saisonId,
        'rencontres' => $rencontres,
        'diffAllerRetour' => $diffAllerRetour,
        // 'avecMatchesRetour' => $avecMatchesRetour
    ];

    genererCalendrier($donnees);

    echo "<br>FIN DE L'EXECUTION";
?>
