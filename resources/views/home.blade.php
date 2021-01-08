@extends('layouts.site')

@section('title', 'Accueil de notre site')

@section('content')

    <div class="row bg-white justify-content-center">
        <div class="col-12 text-center p-3 d-none">
            <h1 class="h4">MayotteSport.com : l'actualité sportive de Mayotte</h1>
        </div>

        <div class="col-12 d-lg-none p-0 d-flex text-center p-3">
            <a href="" id="actualites" data-cible="bloc-actualites" data-autre="resultats"
                class="d-block col-6 p-3 border btn btn-secondary onglet @if($articles) active @endif">Actualités</a>
            <a href="" id="resultats" data-cible="bloc-resultats" data-autre="actualites"
                class="d-block col-6 p-3 border btn btn-secondary onglet @if(! $articles) active @endif">Résultats</a>
        </div>

        {{-- classique écran large --}}
        <div class="col-12 d-none d-lg-flex p-0">
            <div class="col-8 pr-lg-3 px-2">
                {!! $articles !!}
            </div>
            <div class="col-4 p-2 bg-resultats pt-3" style="font-size: 0.9rem;">
                <h2 class="alert alert-danger h2 text-center">Les résultats</h2>
                @foreach ($sports as $sport)
                    {!! $sport->journees !!}

                @endforeach
            </div>
        </div>

        {{-- avec onglets --}}
        <div class="col-12 d-lg-none p-3">
            <div id="bloc-actualites" class="@if(! $articles) d-none @endif">
                {!! $articles !!}
            </div>
            <div id="bloc-resultats" class="@if($articles) d-none @endif">
                @foreach ($sports as $sport)
                    {!! $sport->journees !!}

                @endforeach
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
