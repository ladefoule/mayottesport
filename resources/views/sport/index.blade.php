@extends('layouts.sport')

@section('title', "$sport->nom - Toute l'actualité et tous les résultats")

@section('content')

    <div class="row justify-content-center">
        {{-- <img src="/storage/img/marche-droits-tv-football-suisse-2048x980.jpg" alt="" class="img-fluid" height="10px"> --}}
        {{-- <div class="col-12 text-center p-3">
            <h1 class="h4">Accueil football : actus et résultats</h1>
        </div> --}}

        @include('modele-onglets')
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            ongletSwitch()
        })
    </script>
@endsection
