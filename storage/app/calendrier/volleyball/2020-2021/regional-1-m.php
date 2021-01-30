<?php 
    echo "SCRIPT DE GENERATION DE LA SAISON 2020/2021 DE R1 M DE VOLLEYBALL<br>";

    $saisonId = 183; //A MODIFIER EN FONCTION
    $heure = '17:00';
    // $avecMatchesRetour = 1;

    // VCM       323
    // AOSCJ     337
    // ZAMFI     322
    // VBM       330
    // VCV       324
    // LAREEC    344
    // VCT       336
    // MAV       325

    $rencontres = [
        [
            [323,337],
            [322,330],
            [324,344],
            [336,325],
        ],
        [
            [336,337],
            [325,324],
            [344,322],
            [323,330],
        ],
        [
            [337,330],
            [344,323],
            [322,325],
            [324,336],
        ],
        [
            [324,337],
            [336,322],
            [325,323],
            [344,330],
        ],
        [
            [337,344],
            [330,325],
            [323,336],
            [322,324],
        ],
        [
            [322,337],
            [324,323],
            [336,330],
            [325,344],
        ],
        [
            [337,325],
            [344,336],
            [330,324],
            [323,322],
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