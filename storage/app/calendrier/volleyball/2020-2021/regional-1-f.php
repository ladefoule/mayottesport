<?php 
    echo "SCRIPT DE GENERATION DE LA SAISON 2020/2021 DE R1 F DE VOLLEYBALL<br>";

    $saisonId = 184; //A MODIFIER EN FONCTION
    $heure = '16:00';
    // $avecMatchesRetour = 1;

    // VCM       352
    // VBM       357
    // AOSCJ     362
    // ALLS      355
    // VCKB      332

    $rencontres = [
        [
            [357,355],
            [332,362],
        ],
        [
            [362,357],
            [355,352],
        ],
        [
            [352,362],
            [357,332],
        ],
        [
            [332,352],
            [362,355],
        ],
        [
            [355,332],
            [352,357],
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