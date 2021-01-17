@extends('layouts.site')

@section('title', 'Accueil de notre site')
{{-- 
@section('content')
    <div class="container justify-content-center bg-white mt-4 border border-danger m-auto h-100 w-100">
        <div class="col-12 text-center p-3 d-none">
            <h1 class="h4">MayotteSport.com : l'actualité sportive de Mayotte</h1>
        </div>
    </div>
@endsection

@section('section-droite')
<div> --}}

@include('onglet.section-content-home-et-sport')
@include('onglet.section-droite-home-et-sport')

@section('script')
    <script>
        $(document).ready(function() {
            // Gestion des onglets dans le main
            var cibles = qsa('main .bloc-prochains,main .bloc-resultats,main .bloc-actualites')
            var onglets = qsa('main .onglet') 
            ongletSwitch(cibles, onglets)

            // Gestion des onglets du bloc de droite
            cibles = qsa('#section-droite .bloc-prochains,#section-droite .bloc-resultats,#section-droite .bloc-fil-actu')
            onglets = qsa('#section-droite .onglet') 
            ongletSwitch(cibles, onglets)
        })
    </script>
@endsection
