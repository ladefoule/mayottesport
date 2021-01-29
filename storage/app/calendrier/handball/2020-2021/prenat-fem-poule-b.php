<?php 
    echo "SCRIPT DE GENERATION DE LA SAISON 2020/2021 DE PRENAT FEM, POULE B DE HANDBALL<br>";

    $saisonId = 182; //A MODIFIER EN FONCTION
    // $heure = '';
    // $avecMatchesRetour = 1;

    // COMBANI          290
    // TSIMKOURA        291
    // MBOUANATSA       364
    // TCO              309
    // SADA            295
    // TCHANGA         296

    $rencontres = [
        [// 1ère journée
            [295,296],
            [290,309],
            [291,364],
        ],
        [
            [296,291],
            [309,295],
            [364,290],
        ],
        [
            [364,309],
            [290,296],
            [295,291],
        ],
        [
            [291,290],
            [295,364],
            [296,309],
        ],
        [
            [290,295],
            [364,296],
            [309,291],
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