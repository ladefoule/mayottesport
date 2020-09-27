<?php
use Illuminate\Support\Facades\DB;
use App\Equipe;
use App\ChampJournee;
use App\Championnat;
use App\ChampSaison;
use App\ChampMatch;

$saisonId = 1;
$championnat = ChampSaison::whereId($saisonId)->first()->championnat->nom;
?>
@extends('layouts.site')

@section('title', 'Accueil de notre site')

@section('content')

<div class="row justify-content-center">
    {{-- <div class="col-12 text-center">
        <h1 class="h4">Mayotte sport : l'ensemble des résultats de l'île</h1>
    </div> --}}
    <div class="col-12 text-center mt-4">
        <span class="h2 font-italic">Football</span>
    </div>

    <div class="col-12 text-center py-3 row justify-content-between">
        <h3 class="col-12 h4 border-bottom-calendrier py-2"><?= $championnat ?></h3>
        <?php
            $journeeNumero = 1;
            $journee = ChampJournee::whereChampSaisonId($saisonId)->whereNumero($journeeNumero)->first();
        ?>
        <div class="col-lg-8 pl-3">
            {!! $journee->afficherCalendrier() !!}
        </div>
        <div class="d-none d-lg-block col-lg-4 pl-5 pr-0">
            {!! $saison = ChampSaison::find($saisonId)->afficherClassementSimplifie() !!}
        </div>
    </div>

    <div class="col-12 text-center py-3 row justify-content-between">
        <h3 class="col-12 h4 border-bottom-calendrier py-2"><?= $championnat ?></h3>
        <?php
            $journeeNumero = 15;
            $journee = ChampJournee::whereChampSaisonId($saisonId)->whereNumero($journeeNumero)->first();
        ?>
        <div class="col-lg-8 pl-3">
            {!! $journee->afficherCalendrier() !!}
        </div>
        <div class="d-none d-lg-block col-lg-4 pl-5 pr-0">
            {!! $saison = ChampSaison::find($saisonId)->afficherClassementSimplifie() !!}
        </div>
    </div>
</div>

@endsection
