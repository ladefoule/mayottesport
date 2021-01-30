<?php 
    echo "SCRIPT DE GENERATION DE LA SAISON 2020/2021 DE R2 M DE VOLLEYBALL<br>";

    $saisonId = 185; //A MODIFIER EN FONCTION
    $heure = '16:00';
    // $avecMatchesRetour = 1;

    // VCV2     366
    // MVC      339
    // MAV2     367
    // VOS      334
    // 6VCC     368
    // SPORTS   365   

    $rencontres = [
        [
            [366,339],
            [367,334],
            [368,365],
        ], 
        [
            [368,339],
            [365,367],
            [334,366],
        ], 
        [
            [339,334],
            [366,365],
            [367,368],
        ], 
        [
            [367,339],
            [368,366],
            [365,334],
        ], 
        [
            [339,365],
            [334,368],
            [366,367],
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