@extends('layouts.competition')

@section('title', $competition->nom . ' - ' . $sport->nom)

@section('content')
<div class="p-lg-3 h-100">
    {{-- classique écran large --}}
    <div class="d-none d-lg-flex h-100 p-0">
        <div class="col-12 px-3 pt-2 bg-white">
            @if(! $articles && $competition)
                <div class="row">
                    {{-- Image pour les compétitions/sports sans articles liés --}}
                    @if (\Storage::disk('public')->exists('img/competition/'. $competition->slug .'.jpg'))
                        <img src="{{ asset('/storage/img/competition/'. $competition->slug .'.jpg') }}" alt="" class="img-fluid m-auto">
                    @else
                        <img src="{{ asset('/storage/img/sport/'. $sport->slug .'.jpg') }}" alt="" class="img-fluid m-auto">
                    @endif
                </div>
            @endif
            {!! $articles !!}
        </div>
    </div>

    {{-- avec onglets --}}
    <div class="col-12 d-lg-none d-flex text-center p-3 bg-white">
        <span data-cible="actualites-content"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if($articles) active @endif">Actualités</span>
        <span data-cible="resultats-content"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $articles && $resultats) active @endif">Résultats</span>
        <span data-cible="prochains-content"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $resultats && ! $articles) active @endif">À venir</span>
    </div>

    <div class="col-12 d-lg-none bg-white pt-0">
        <div id="actualites-content" class="@if(! $articles) d-none @endif">
            {!! $articles !!}
        </div>
        <div id="resultats-content" class="@if($articles || !$resultats) d-none @endif">
            @foreach ($resultats as $resultat)
                <div class="p-3">
                    {!! $resultat !!}
                </div>
            @endforeach
        </div>
        <div id="prochains-content" class="@if($articles || $resultats) d-none @endif">
            @foreach ($prochains as $prochain)
                <div class="p-3">
                    {!! $prochain !!}
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection