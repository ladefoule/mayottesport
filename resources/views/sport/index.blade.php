@extends('layouts.sport')

@section('title', "$sport->nom - Toute l'actualité et tous les résultats")

{{-- @section('content') --}}

    {{-- <div class="row justify-content-center"> --}}
        {{-- <img src="/storage/img/marche-droits-tv-football-suisse-2048x980.jpg" alt="" class="img-fluid" height="10px"> --}}
        {{-- <div class="col-12 text-center p-3">
            <h1 class="h4">Accueil football : actus et résultats</h1>
        </div> --}}

        {{-- @include('modele-onglets') --}}
    {{-- </div> --}}
{{-- @endsection --}}

@include('onglet.section-content-home-et-sport')
@include('onglet.section-droite-home-et-sport')

@section('script')
    <script>
        $(document).ready(function() {
            // Gestion des onglets dans le main
            var cibles = qsa('#prochains-content,#resultats-content,#actualites-content')
            var onglets = qsa('#onglets-content .onglet') 
            ongletSwitch(cibles, onglets)

            // Gestion des onglets du bloc de droite
            cibles = qsa('#prochains-section-droite,#resultats-section-droite,#fil-actu-section-droite')
            onglets = qsa('#section-droite .onglet') 
            ongletSwitch(cibles, onglets)
        })
    </script>
@endsection
