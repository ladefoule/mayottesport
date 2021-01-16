@extends('layouts.competition')

@section('title', $competition . ' - ' . $sport)

@section('content')
<div class="p-0 h-100">
    {{-- classique écran large --}}
    <div class="d-none d-lg-flex h-100 p-3">
        <div class="col-12 px-3 bg-white">
            <?php 
                $sport = request()->sport;
                $competition = request()->competition;
            ?>
            @if(! $articles && $competition)
                {{-- <h1 class="h3 p-3 text-center">{{ $sport->nom . ($competition ? ' - ' . $competition->nom : '') }}</h1> --}}
                <div class="col-12 d-flex p-0">
                    {{-- Image par défaut pour les compétitions/sports sans articles liés --}}
                    <img src="{{ asset('/storage/img/as-rosador-de-passamainty-2015.jpg') }}" alt="" class="img-fluid m-auto">
                </div>
            @endif
            {!! $articles !!}
        </div>
    </div>

    {{-- avec onglets --}}
    <div class="col-12 d-lg-none d-flex text-center px-3 pt-3 bg-white">
        <a href="" id="actualites"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if($articles) active @endif">Actualités</a>
        <a href="" id="resultats"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $articles && $resultats) active @endif">Résultats</a>
        <a href="" id="prochains"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $resultats && ! $articles) active @endif">À venir</a>
    </div>

    <div class="col-12 d-lg-none bg-white">
        <div id="bloc-actualites" class="@if(! $articles) d-none @endif">
            {!! $articles !!}
        </div>
        <div id="bloc-resultats" class="@if($articles || !$resultats) d-none @endif">
            @foreach ($resultats as $resultat)
                <div class="p-3">
                    {!! $resultat !!}
                </div>
            @endforeach
        </div>
        <div id="bloc-prochains" class="@if($articles || $resultats) d-none @endif">
            @foreach ($prochains as $prochain)
                <div class="p-3">
                    {!! $prochain !!}
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('section-droite')
<div class="my-3 bg-white" {{-- style="background-color:#ebeff3" --}}>
    {{-- <h2 class="alert alert-danger h4 text-center py-4">Les derniers résultats</h2> --}}
    <div class="col-12 d-flex text-center px-3 pt-3 bg-white">
        <a href="" id="resultats-bloc-droite"
            class="d-block col-6 p-3 border btn btn-secondary onglet @if($resultats) active @endif">Résultats</a>
        <a href="" id="prochains-bloc-droite"
            class="d-block col-6 p-3 border btn btn-secondary onglet @if(! $resultats && ! $articles) active @endif">À venir</a>
    </div>
    <div id="bloc-resultats-bloc-droite" class="col-12 px-2 @if(! $resultats) d-none @endif">
        @foreach ($resultats as $resultat)
            <div class="p-3">
                {!! $resultat !!}
            </div>
        @endforeach
    </div>
    <div id="bloc-prochains-bloc-droite" class="col-12 px-2 d-none @if(!$resultats) d-block @endif">
        @foreach ($prochains as $prochain)
            <div class="p-3">
                {!! $prochain !!}
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var onglets = qsa('.onglet')
            ongletSwitch(onglets)
        })
    </script>
@endsection