@extends('layouts.competition')

@section('title', $competition . ' - ' . $sport)

@section('content')
<div class="row mt-lg-3">
    <div class="col-12 d-lg-none d-flex text-center px-3 py-3">
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
            {!! $journees !!}
        </div>
    </div>

    {{-- avec onglets --}}
    <div class="col-12 d-lg-none">
        <div id="bloc-actualites" class="@if(! $articles) d-none @endif">
            {!! $articles !!}
        </div>
        <div id="bloc-resultats" class="@if($articles) d-none @endif">
            {!! $journees !!}
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