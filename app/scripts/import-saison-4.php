<?php
$saisonId = 3;
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
    ]
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
