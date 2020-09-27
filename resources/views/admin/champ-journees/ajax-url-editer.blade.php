<?php
use Illuminate\Support\Facades\Validator;

$rules = [
    'champ_saison_id' => 'required|exists:champ_saisons,id'
];

$validator = Validator::make(request()->all(), $rules);
if ($validator->fails()) {
    echo 'Erreur';
    exit();
}

$request = $validator->validate();
$saisonId = $request['champ_saison_id'];
echo route('champ-journees.multi.editer', ['id' => $saisonId]);
