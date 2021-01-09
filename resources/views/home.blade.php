@extends('layouts.site')

@section('title', 'Accueil de notre site')

@section('content')
    <div class="row justify-content-center bg-white">
        <div class="col-12 text-center p-3">
            <h1 class="h4">MayotteSport.com : l'actualité sportive de Mayotte</h1>
        </div>

        <div class="col-12 d-lg-none py-0 d-flex text-center px-3">
            <a href="" id="actualites" data-cible="bloc-actualites" data-autre="resultats"
                class="d-block col-6 p-3 border btn btn-secondary onglet @if($articles) active @endif">Actualités</a>
            <a href="" id="resultats" data-cible="bloc-resultats" data-autre="actualites"
                class="d-block col-6 p-3 border btn btn-secondary onglet @if(! $articles) active @endif">Résultats</a>
        </div>

        {{-- classique écran large --}}
        <div class="col-12 d-none d-lg-flex p-0">
            <div class="col-8 px-3 py-0">
                {!! $articles !!}
            </div>
            <div class="col-4 p-2 bg-resultats">
                <h2 class="alert alert-danger h2 text-center py-4">Les résultats</h2>
                @foreach ($sports as $sport)
                    @if($sport->journees)
                        <div class="col-12 text-center my-2 px-3">
                            <span class="h2 font-italic">
                                <a class="text-body" href="{{ route('sport.index', ['sport' => \Str::slug($sport->nom)]) }}">
                                {{ $sport->nom }}
                                </a>
                            </span>
                        </div>
                        {!! $sport->journees !!}
                    @endif
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
                    @if($sport->journees)
                        <div class="col-12 text-center my-2 px-3">
                            <span class="h2 font-italic">
                                <a class="text-body" href="{{ route('sport.index', ['sport' => \Str::slug($sport->nom)]) }}">
                                {{ $sport->nom }}
                                </a>
                            </span>
                        </div>
                        {!! $sport->journees !!}
                    @endif
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
