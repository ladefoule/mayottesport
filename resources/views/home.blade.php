<?php
use Illuminate\Support\Facades\DB;
use App\Equipe;
use App\Journee;
use App\Competition;
use App\Saison;
use App\Match;

$saisonId = 1;
$competition = Saison::whereId($saisonId)->first()->competition->nom;
?>
@extends('layouts.site')

@section('title', 'Accueil de notre site')

@section('content')

<div class="row mx-0 bg-white justify-content-center rounded">
    {{-- <div class="col-12 text-center">
        <h1 class="h4">Mayotte sport : l'ensemble des résultats de l'île</h1>
    </div> --}}
    <div class="col-12 text-center mt-4">
        <span class="h2 font-italic">Football</span>
    </div>

    @foreach ($sports as $sport)
        @foreach ($sport->liste as $competition)
        <div class="col-12 text-center py-3 row justify-content-between">
            <h3 class="col-12 h4 border-bottom-calendrier py-2">{{ $competition['nom'] }}</h3>
            <div class="col-lg-8 pl-3">
                {!! $competition['journee'] !!}
            </div>
            <div class="d-none d-lg-block col-lg-4 pl-5 pr-0">
                @if ($competition['classement'])
                    {!! $competition['classement'] !!}
                @endif
            </div>
        </div>
        @endforeach
    @endforeach
</div>

@endsection
