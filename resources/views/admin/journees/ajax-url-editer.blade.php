<?php
use Illuminate\Support\Facades\Validator;

$rules = [
    'saison_id' => 'required|exists:saisons,id'
];

$validator = Validator::make(request()->all(), $rules);
if ($validator->fails()) {
    echo 'Erreur';
    exit();
}

$request = $validator->validate();
$saisonId = $request['saison_id'];
echo route('journees.multi.editer', ['id' => $saisonId]);
