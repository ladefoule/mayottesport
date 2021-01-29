<?php 
    echo "SCRIPT DE GENERATION DE LA SAISON 2020/2021 DE PRENAT MASC, POULE B DE HANDBALL<br>";

    $saisonId = 180; //A MODIFIER EN FONCTION
    // $heure = '';
    // $avecMatchesRetour = 1;

    // ALAKARABU       270
    // TSINGONI        261
    // SOHOA           275
    // COMBANI         262
    // BANDRELE        269
    // LABATTOIR       268
    // CHICONI         267
    // TCHANGA         260

    $rencontres = [
        [// 1ère journée
            [270,261],
            [275,262],
            [269,268],
            [267,260],
        ],
        [
            [275,261],
            [268,270],
            [262,267],
            [260,269],
        ],
        [
            [261,268],
            [267,275],
            [270,260],
            [269,262],
        ],
        [
            [267,261],
            [260,268],
            [275,269],
            [262,270],
        ],
        [
            [261,260],
            [270,275],
            [269,267],
            [268,262],
        ],
        [
            [262,260],
            [267,270],
            [275,268],
            [269,261],
        ],
        [
            [261,262],
            [270,269],
            [260,275],
            [268,267],
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