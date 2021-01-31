<?php 
    echo "SCRIPT DE GENERATION DE LA SAISON 2020/2021 DE PNM, POULE B DE BAKSETBALL<br>";

    $saisonId = 187; //A MODIFIER EN FONCTION
    $heure = '17:00';
    // $avecMatchesRetour = 1;

    // GOLDEN        225
    // GLADIATOR     222
    // FUZELLIPS     216
    // BC MTSAPERE   219
    // ETOILE BLEUE  215
    // KOROPA F      229

    $rencontres = [
        [// 1ère journée
            [225,222],
            [216,219],
            [215,229],
        ],
        [
            [215,225],
            [219,229],
            [222,216],
        ],
        [
            [225,219],
            [222,215],
            [216,229],
        ],
        [
            [229,225],
            [215,216],
            [219,222],
        ],
        [
            [229,222],
            [225,216],
            [215,219],
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