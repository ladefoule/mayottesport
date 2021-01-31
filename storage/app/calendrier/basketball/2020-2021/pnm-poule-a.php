<?php 
    echo "SCRIPT DE GENERATION DE LA SAISON 2020/2021 DE PNM, POULE A DE BAKSETBALL<br>";

    $saisonId = 186; //A MODIFIER EN FONCTION
    $heure = '17:00';
    // $avecMatchesRetour = 1;

    // COLORADO        230
    // RAPIDES         232
    // SCOLO DUNKS     226
    // TCO             217
    // ETOILE DZ       369
    // VAUTOUR         221

    $rencontres = [
        [// 1ère journée
            [230,232],
            [226,217],
            [369,221],
        ],
        [
            [232,226],
            [217,369],
            [221,230],
        ],
        [
            [221,217],
            [230,226],
            [369,232],
        ],
        [
            [232,221],
            [226,369],
            [217,230],
        ],
        [
            [226,221],
            [217,232],
            [230,369],
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