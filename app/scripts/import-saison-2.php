<?php
// On insère 5 saisons de Régional 2 (football)
for ($i=0; $i < 2; $i++) {
    $saison = App\Saison::create([
        'annee_debut' => date('Y') - $i,
        'annee_fin' => date('Y') - $i + 1,
        'finie' => $i==0 ? 0 : 1,
        'nb_journees' => 22,
        'bareme_id' => 1,
        'competition_id' => 2,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    $saisonId = $saison->id;
    $heure = '18:30';

    $idsEquipes = [81,60,35,97,21,18,40,120,139,150,112,47];

    $rencontres = [
        [// 1ère journée
            [81, 60],
            [35, 97],
            [21, 18],
            [40, 139],
            [120, 150],
            [112, 47]
        ],
        [ // 2ème journée
            [60, 35],
            [18, 81],
            [97, 40],
            [150, 21],
            [139, 112],
            [47, 120]
        ],
        [ // 3ème journée
            [60, 18],
            [40, 35],
            [81, 150],
            [112, 97],
            [21, 47],
            [120, 139]
        ],
        [ // 4ème journée
            [40, 60],
            [150, 18] ,
            [35, 112],
            [47, 81],
            [97, 120],
            [139, 21]
        ],
        [ // 5ème journée
            [60, 150],
            [112, 40],
            [18, 47],
            [120, 35],
            [81, 139],
            [21, 97]
        ],
        [ // 6ème journée
            [112, 60],
            [47, 150],
            [40, 120],
            [139, 18],
            [35, 21],
            [97, 81]
        ],
        [ // 7ème journée
            [60, 47],
            [120, 112],
            [150, 139],
            [21, 40],
            [18, 97],
            [81, 35]
        ],
        [ // 8ème journée
            [120, 60],
            [139, 47],
            [112, 21],
            [97, 150],
            [40, 81],
            [35, 18]
        ],
        [ // 9ème journée
            [60, 139],
            [21, 120],
            [47, 97],
            [81, 112],
            [150, 35],
            [18, 40]
        ],
        [ // 10ème journée
            [21, 60],
            [97, 139],
            [120, 81],
            [35, 47],
            [112, 18],
            [40, 150]
        ],
        [ // 11ème journée
            [60, 97],
            [81, 21],
            [139, 35],
            [18, 120],
            [47, 40],
            [150, 112]
        ]
    ];

    $diffAllerRetour = 11; // Différence en nombre de jours entre le match aller et le retour

    $donnees = [
    'heure' => $heure,
    'saisonId' => $saisonId,
    'idsEquipes' => $idsEquipes,
    'rencontres' => $rencontres,
    'diffAllerRetour' => $diffAllerRetour
    ];

    genererCalendrier($donnees);
}
