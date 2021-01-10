@extends('layouts.sport')

@section('title', "$sport->nom - Toute l'actualité et tous les résultats")

@section('content')

    <div class="row justify-content-center">
        {{-- <img src="/storage/img/marche-droits-tv-football-suisse-2048x980.jpg" alt="" class="img-fluid" height="10px"> --}}
        {{-- <div class="col-12 text-center p-3">
            <h1 class="h4">Accueil football : actus et résultats</h1>
        </div> --}}

        <div class="col-12 d-lg-none py-0 d-flex text-center px-3 pt-3">
            <a href="" id="actualites"
                class="d-block col-4 p-3 border btn btn-secondary onglet @if($articles) active @endif">Actualités</a>
            <a href="" id="resultats"
                class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $articles && $resultats) active @endif">Résultats</a>
            <a href="" id="prochains"
                class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $resultats && ! $prochains) active @endif">À venir</a>
        </div>
    
        {{-- classique écran large --}}
        <div class="col-12 d-none d-lg-flex p-0">
            <div class="col-8 px-3 py-0">
                {!! $articles !!}
            </div>
            <div class="col-4 p-2 bg-resultats">
                <h2 class="alert alert-danger h2 text-center py-4">Les résultats</h2>
                {!! $resultats !!}
            </div>
        </div>

        {{-- avec onglets --}}
        <div class="col-12 d-lg-none p-3">
            <div id="bloc-actualites" class="@if(! $articles) d-none @endif">
                {!! $articles !!}
            </div>
            <div id="bloc-resultats" class="@if($articles) d-none @endif">
                {!! $resultats !!}
            </div>
            <div id="bloc-prochains" class="@if($articles) d-none @endif">
                {!! $prochains !!}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            ongletSwitch()
        })
    </script>
@endsection
