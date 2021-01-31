<?php 
    echo "SCRIPT DE GENERATION DE LA SAISON 2020/2021 DE R2M DE BAKSETBALL<br>";

    $saisonId = 189; //A MODIFIER EN FONCTION
    $heure = '17:00';
    // $avecMatchesRetour = 1;

    // JEUNESSE CANON  218
    // MAGIC PASSAM  373
    // CHIRONGUI     374
    // ABS SADA      233
    // WAKAIDI       375
    // MANGAJOU      223
    // TCO           239
    // MTSAPERE      376

    $rencontres = [
        [
            [218,373],
            [233,374],
            [223,375],
            [376,239],
        ],
        [
            [374,376],
            [218,239],
            [375,233],
            [373,223],
        ],
        [
            [374,218],
            [223,233],
            [375,239],
            [373,376],
        ],
        [
            [239,374],
            [376,375],
            [233,373],
            [223,218],
        ],
        [
            [374,223],
            [239,233],
            [218,376],
            [375,373],
        ],
        [
            [223,239],
            [233,376],
            [375,218],
            [373,374],
        ],
        [
            [374,375],
            [218,233],
            [239,373],
            [376,223],
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