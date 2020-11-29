<?php

// var_dump(request()->all());

// echo uniqid();
// echo rand(0,99999999);

// echo array_search('test', [5 => ['test', 'test'], 55 => 'test5']);

echo 'Cache vidée !';
Cache::flush();

$crudAttributInfos = [
    [1, 1, '1'],
    // [2, 1, '2'],
    [3, 1, '2'],
    [4, 1, '2'],
    [6, 1, '1'],
    [7, 1, '2'],
    [9, 1, '1'],
    [10, 1, '2'],
    [18, 1, '2'],
    [19, 1, '1'],
    [27, 1, '1'],
    [28, 1, '2'],
    [29, 1, '3'],
    [45, 1, '1'],
    [46, 1, '2'],
    [49, 1, '1'],
    [53, 1, '1'],
    [54, 1, '2'],
    [55, 1, '1'],
    [61, 1, '1'],
    [62, 1, '2'],
    [63, 1, '3'],
    [64, 1, '1'],
    [66, 1, '2'],
    [80, 1, '1'],
    [84, 1, '1']
];

// foreach ($crudAttributInfos as $donnees) {
//     $crudAttributInfo = new App\CrudAttributInfo([
//         'crud_attribut_id' => $donnees[0],
//         'propriete_id' => $donnees[1],
//         'valeur' => $donnees[2]
//     ]);
//     $crudAttributInfo->save();
// }
