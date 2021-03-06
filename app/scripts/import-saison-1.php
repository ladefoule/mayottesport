<?php
// On insère 5 saisons de Régional 1 (football)
for ($i=0; $i < 2; $i++) {
    $saison = App\Saison::create([
        'annee_debut' => date('Y') - $i,
        'annee_fin' => date('Y') - $i + 1,
        'finie' => $i==0 ? 0 : 1,
        'nb_journees' => 22,
        'bareme_id' => 1,
        'competition_id' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    $saisonId = $saison->id;
    $heure = '15:30';

    $terrains = [
    '29' => 1, // $idEquipe => $idTerrain
    '27' => 2,
    '26' => 3,
    '97' => 4,
    '2' => 5,
    '1' => 6,
    '4' => 7,
    '138' => 8,
    '13' => 9,
    '162' => 10,
    '96' => 9,
    '45' =>8
    ];

    $rencontres = [
        [// 1ère journée
            [29, 27],
            [26, 97],
            [2, 1],
            [4, 138],
            [13, 162],
            [96, 45]
        ],
        [ // 2ème journée
            [27, 26],
            [1, 29],
            [97, 4],
            [162, 2],
            [138, 96],
            [45, 13]
        ],
        [ // 3ème journée
            [27, 1],
            [4, 26],
            [29, 162],
            [96, 97],
            [2, 45],
            [13, 138]
        ],
        [ // 4ème journée
            [4, 27],
            [162, 1] ,
            [26, 96],
            [45, 29],
            [97, 13],
            [138, 2]
        ],
        [ // 5ème journée
            [27, 162],
            [96, 4],
            [1, 45],
            [13, 26],
            [29, 138],
            [2, 97]
        ],
        [ // 6ème journée
            [96, 27],
            [45, 162],
            [4, 13],
            [138, 1],
            [26, 2],
            [97, 29]
        ],
        [ // 7ème journée
            [27, 45],
            [13, 96],
            [162, 138],
            [2, 4],
            [1, 97],
            [29, 26]
        ],
        [ // 8ème journée
            [13, 27],
            [138, 45],
            [96, 2],
            [97, 162],
            [4, 29],
            [26, 1]
        ],
        [ // 9ème journée
            [27, 138],
            [2, 13],
            [45, 97],
            [29, 96],
            [162, 26],
            [1, 4]
        ],
        [ // 10ème journée
            [2, 27],
            [97, 138],
            [13, 29],
            [26, 45],
            [96, 1],
            [4, 162]
        ],
        [ // 11ème journée
            [27, 97],
            [29, 2],
            [138, 26],
            [1, 13],
            [45, 4],
            [162, 96]
        ]
    ];

    $diffAllerRetour = 11; // Différence en nombre de jours entre le match aller et le retour

    $donnees = [
    'heure' => $heure,
    'saisonId' => $saisonId,
    'terrains' => $terrains,
    'rencontres' => $rencontres,
    'diffAllerRetour' => $diffAllerRetour
    ];

    genererCalendrier($donnees);
}
