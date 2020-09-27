<?php
use App\ChampSaison;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

$rules = [
    'champ_saison_id' => 'required|exists:champ_saisons,id'
];

$validator = Validator::make(request()->all(), $rules);
if ($validator->fails()) {
    echo 'Erreur';
    exit();
}

// On récupère le tableau filtré de la requète
$request = $validator->validate();

$saisonId = $request['champ_saison_id'];
$saison = ChampSaison::findOrFail($saisonId);
if ($saison == null){
    echo 'Erreur';
    exit();
}

$nbJournees = $saison->nb_journees;
for ($i = 1; $i <= $nbJournees; $i++){
    $journee = DB::table('champ_journees')->where([ ['champ_saison_id', '=', $saisonId], ['numero', '=', $i] ])->first();
    $numero = $journee->numero ?? $i;
    $id = $journee->id ?? 0;
    $date = $journee->date ?? date('Y-m-d');

    echo $id . '$$';
    echo $numero . '$$';
    echo $date;

    if($i < $nbJournees)
        echo '|';
}
