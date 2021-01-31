<?php 
    echo "SCRIPT DE GENERATION DE LA SAISON 2020/2021 DE PNF DE BAKSETBALL<br>";

    $saisonId = 188; //A MODIFIER EN FONCTION
    $heure = '17:00';
    // $avecMatchesRetour = 1;

    // BC CHICONI        370
    // MAGIC PASSAM      371
    // BC MTSAPERE       250
    // WAKAIDI           372
    // CHICAGO MAMO      252
    // GOLDEN FORCE      253
    // FUZ'ELLIPS        254

    $rencontres = [
        [
            [370,371],
            [250,372],
            [252,253],
        ],
        [
            [372,252],
            [371,254],
            [253,370],
        ],
        [
            [254,253],
            [252,371],
            [370,250],
        ],
        [
            [372,370],
            [253,250],
            [252,254],
        ],
        [
            [371,250],
            [253,372],
            [254,370],
        ],
        [
            [250,254],
            [372,371],
            [370,252],
        ],
        [
            [371,253],
            [254,372],
            [252,250],
        ],
    ];

    $diffAllerRetour = 7; // Différence en nombre de jours entre le match aller et le retour

    $donnees = [
        'heure' => $heure ?? '',
        'saisonId' => $saisonId,
        'rencontres' => $rencontres,
        'diffAllerRetour' => $diffAllerRetour,
        // 'avecMatchesRetour' => $avecMatchesRetour
    ];

    genererCalendrier($donnees);

    echo "<br>FIN DE L'EXECUTION";
?>