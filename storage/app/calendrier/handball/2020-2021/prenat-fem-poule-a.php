<?php 
    echo "SCRIPT DE GENERATION DE LA SAISON 2020/2021 DE PRENAT FEM, POULE A DE HANDBALL<br>";

    $saisonId = 181; //A MODIFIER EN FONCTION
    // $heure = '';
    // $avecMatchesRetour = 1;

    // BOUENI          294
    // BANDRELE        297
    // KANI-KELI       292
    // PASSAMAINTY     306
    // TSINGONI        293
    // MOINATRIN       363

    $rencontres = [
        [// 1ère journée
            [294,293],
            [297,292],
            [306,363],
        ],
        [
            [293,306],
            [292,294],
            [363,297],
        ],
        [
            [292,293],
            [294,363],
            [306,297],
        ],
        [
            [297,294],
            [306,292],
            [363,293],
        ],
        [
            [293,297],
            [292,363],
            [294,306],
        ],
    ];

    $diffAllerRetour = 5; // Différence en nombre de jours entre le match aller et le retour

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