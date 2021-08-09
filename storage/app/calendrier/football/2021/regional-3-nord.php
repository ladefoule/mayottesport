<?php
    echo "SCRIPT DE GENERATION DE LA SAISON 2021 DE REGIONAL 3 POULE NORD DE FOOTBALL<br>";

    $saisonId = 227; //A MODIFIER EN FONCTION
    $heure = '15:00';

    //  48  // AJ MTSAHARA
    //  97  // ASC WAHADI
    //  59  // ASJ HANDREMA
    //  196 // ASO ESPOIR CHICONI
    //  46  // ENFANT DU PORT
    //  19  // ETINCELLES HAMJAGO
    //  193 // MAHABOU SC
    //  381 // NDREMA CLUB
    //  84  // FCM 2
    //  34  // PAMANDZI SC
    //  20  // RACINE DU NORD
    //  70  // US KAVANI

    $rencontres = [
        [
            [20,34],
            [97,19],
            [84,196],
            [46,70],
            [381,48],
            [193,59],
        ],
        [
            [196,20],
            [34,97],
            [48,84],
            [70,193],
            [59,381],
            [19,46],
        ],
        [
            [381,70],
            [34,196],
            [46,97],
            [20,48],
            [193,19],
            [84,59],
        ],
        [
            [97,193],
            [59,20],
            [19,381],
            [70,84],
            [46,34],
            [48,196],
        ],
        [
            [34,48],
            [193,46],
            [196,59],
            [381,97],
            [20,70],
            [84,19],
        ],
        [
            [59,48],
            [46,381],
            [70,196],
            [97,84],
            [19,20],
            [193,34],
        ],
        [
            [34,59],
            [381,193],
            [48,70],
            [84,46],
            [196,19],
            [20,97],
        ],
        [
            [381,34],
            [70,59],
            [193,84],
            [19,48],
            [46,20],
            [97,196],
        ],
        [
            [48,97],
            [196,46],
            [34,70],
            [84,381],
            [59,19],
            [20,193],
        ],
        [
            [84,34],
            [19,70],
            [381,20],
            [97,59],
            [193,196],
            [46,48],
        ],
        [
            [196,381],
            [59,46],
            [48,193],
            [34,19],
            [20,84],
            [70,97],
        ],
    ];

    $diffAllerRetour = 11; // Différence en nombre de jours entre le match aller et le retour

    $donnees = [
        'heure' => $heure,
        'saisonId' => $saisonId,
        'rencontres' => $rencontres,
        'diffAllerRetour' => $diffAllerRetour,
    ];

    genererCalendrier($donnees);

    echo "<br>FIN DE L'EXECUTION";
?>
