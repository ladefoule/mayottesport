@extends('layouts.sport')

@section('title', "$sport->nom - Toute l'actualité et tous les résultats")

@include('onglet.section-main-home-et-sport')
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
