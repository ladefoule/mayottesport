<?php
    echo "SCRIPT DE GENERATION DE LA SAISON 2021 DE REGIONAL 2 DE FOOTBALL<br>";

    $saisonId = 2; //A MODIFIER EN FONCTION
    $heure = '16:15';
    // $avecMatchesRetour = 1;

    //  2   // ABEILLES
    //  25  // BANDRELE
    //  35  // DEMBENI
    //  17  // ENFANTS
    //  10  // FOUDRE
    //  120 // KANGANI
    //  22  // KANI-KELI
    //  87  // KAWENI
    //  9   // LABATTOIR
    //  15  // MAJICAVO
    //  11  // MALAMANI
    //  81  // MIRERENI

    $rencontres = [
        [
            [2,25],
            [81,22],
            [10,15],
            [35,11],
            [9,17],
            [87,120],
        ],
        [
            [25,81],
            [15,2],
            [22,35],
            [17,10],
            [11,87],
            [120,9],
        ],
        [
            [25,15],
            [35,81],
            [2,17],
            [87,22],
            [10,120],
            [9,11],
        ],
        [
            [35,25],
            [17,15],
            [81,87],
            [120,2],
            [22,9],
            [11,10],
        ],
        [
            [25,17],
            [87,35],
            [15,120],
            [9,81],
            [2,11],
            [10,22],
        ],
        [
            [87,25],
            [120,17],
            [35,9],
            [11,15],
            [81,10],
            [22,2],
        ],
        [
            [25,120],
            [9,87],
            [17,11],
            [10,35],
            [15,22],
            [2,81],
        ],
        [
            [9,25],
            [11,120],
            [87,10],
            [22,17],
            [35,2],
            [81,15],
        ],
        [
            [25,11],
            [10,9],
            [120,22],
            [2,87],
            [17,81],
            [15,35],
        ],
        [
            [10,25],
            [22,11],
            [9,2],
            [81,120],
            [87,15],
            [35,17],
        ],
        [
            [25,22],
            [2,10],
            [11,81],
            [15,9],
            [120,35],
            [17,87],
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
