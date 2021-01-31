<?php 
    echo "SCRIPT DE GENERATION DE LA SAISON 2020/2021 DE R3M DE BAKSETBALL<br>";

    $saisonId = 190; //A MODIFIER EN FONCTION
    $heure = '17:00';
    // $avecMatchesRetour = 1;

    // CHICONI      247
    // ACOUA        235
    // TSARARANO    243
    // MTSAMBORO    377
    // BAK7         238

    $rencontres = [
        [
            [247,235],
            [243,377],
        ],
        [
            [377,247],
            [238,243],
        ],
        [
            [247,243],
            [235,238],
        ],
        [
            [238,247],
            [377,235],
        ],
        [
            [235,243],
            [238,377],
        ],
    ];

    $diffAllerRetour = 5; // Différence en nombre de jours entre le match aller et le retour

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