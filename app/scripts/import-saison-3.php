<?php
// On insère 5 saisons de Ligue 1 (football)
for ($i=0; $i < 5; $i++) {
    $saison = App\Saison::create([
        'annee_debut' => date('Y') - $i,
        'annee_fin' => date('Y') - $i + 1,
        'nb_journees' => 10,
        'bareme_id' => 1,
        'competition_id' => 3,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    $saisonId = $saison->id;
    $heure = '21:00';

    $terrains = [
    209 => 1, // $idEquipe => $idTerrain
    210 => 2,//
    211 => 3,//
    212 => 4,
    213 => 5,//
    214 => 6,//
    ];

    $rencontres = [
        [
            [209, 210],
            [211, 212],
            [213, 214],
        ],
        [
            [214, 209],
            [212, 211],
            [210, 213],
        ],
        [
            [212, 209],
            [210, 211],
            [214, 213],
        ],
        [
            [209, 210],
            [211, 212],
            [213, 214],
        ],
        [
            [209, 210],
            [211, 212],
            [213, 214],
        ],
    ];

    $diffAllerRetour = 5; // Différence en nombre de jours entre le match aller et le retour

    $donnees = [
    'heure' => $heure,
    'saisonId' => $saisonId,
    'terrains' => $terrains,
    'rencontres' => $rencontres,
    'diffAllerRetour' => $diffAllerRetour
    ];

    genererCalendrier($donnees);
}
