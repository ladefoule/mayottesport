<?php
// On insère une saison de Coupe de Mayotte (football)
$saison = App\Saison::create([
    'annee_debut' => date('Y'),
    'annee_fin' => date('Y'),
    'nb_journees' => 2,
    'competition_id' => 4,
    'created_at' => now(),
    'updated_at' => now()
]);

$saisonId = $saison->id;
$heure = '18:00';

$terrains = [
   81 => 1, // $idEquipe => $idTerrain
   60 => 2,//
   35 => 3,//
   97 => 4,
   21 => 5,//
   18 => 6,//
   40 => 7,//
   120 => 8,//
   139 => 9,
   150 => 10,//
   112 => 9,//
   47 => 8//
];

$rencontres = [
    [// 1ère journée
        [81, 60],
        [35, 97],
        [21, 18],
        [40, 139],
        [120, 150],
        [112, 47]
    ]
];

$diffAllerRetour = 1; // Différence en nombre de jours entre le match aller et le retour

$donnees = [
   'heure' => $heure,
   'saisonId' => $saisonId,
   'terrains' => $terrains,
   'rencontres' => $rencontres,
   'diffAllerRetour' => $diffAllerRetour
];

genererCalendrier($donnees);
