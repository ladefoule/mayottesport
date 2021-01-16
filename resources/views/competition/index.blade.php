@extends('layouts.competition')

@section('title', $competition . ' - ' . $sport)

@section('content')
<div class="p-lg-3 h-100">
    {{-- classique écran large --}}
    <div class="d-none d-lg-flex h-100 p-0">
        <div class="col-12 px-3 bg-white">
            <?php 
                $sport = request()->sport;
                $competition = request()->competition;
            ?>
            @if(! $articles && $competition)
                <div class="row">
                    {{-- Image par défaut pour les compétitions/sports sans articles liés --}}
                    <img src="{{ asset('/storage/img/as-rosador-de-passamainty-2015.jpg') }}" alt="" class="img-fluid m-auto">
                </div>
            @endif
            {!! $articles !!}
        </div>
    </div>

    {{-- avec onglets --}}
    <div class="col-12 d-lg-none d-flex text-center p-3 bg-white">
        <a href="" data-cible="actualites"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if($articles) active @endif">Actualités</a>
        <a href="" data-cible="resultats"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $articles && $resultats) active @endif">Résultats</a>
        <a href="" data-cible="prochains"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $resultats && ! $articles) active @endif">À venir</a>
    </div>

    <div class="col-12 d-lg-none bg-white pt-0">
        <div class="bloc-actualites @if(! $articles) d-none @endif">
            {!! $articles !!}
        </div>
        <div class="bloc-resultats @if($articles || !$resultats) d-none @endif">
            @foreach ($resultats as $resultat)
                <div class="p-3">
                    {!! $resultat !!}
                </div>
            @endforeach
        </div>
        <div class="bloc-prochains @if($articles || $resultats) d-none @endif">
            @foreach ($prochains as $prochain)
                <div class="p-3">
                    {!! $prochain !!}
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection