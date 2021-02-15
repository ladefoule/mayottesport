@extends('layouts.site')

@section('title', "Mayotte Sport : actualités, résultats et classements")

@section('head')
    <meta name="language" content="fr">
	<meta http-equiv="Content-Language" content="fr">
	<meta name="robots" content="index, follow">
	<meta name="identifier-url" content="https://www.mayottesport.com">
	<meta name="geo.region" content="YT">
	<meta name="google-site-verification" content="soJrYiV9rN9l0ETGHzaE7b6ARM1aamr-AkfMl0kJxmo">
	<meta name="geo.placename" content="Mzouazia">
	<meta name="geo.position" content="-12.9260444; 45.1038634">
	<meta name="icbm" content="-12.9260444, 45.1038634">
	<meta name="author" content="M. ALI MOUSSA (Web Solutions)">
	<meta name="description" content="Présente toute l'actualité du sport à Mayotte avec calendriers, résultats et classements des principaux championnats et compétitions de l'île au lagon.">
	<meta name="keywords" content="mayotte, sport, mayottesport, football, foot, volley-ball, volley, actualité, actu, basketball, basket, handball, hand, résultat, classement, calendrier, maoré, mahorais, ile, 976, news, mzouasia, mzouazia,">
@endsection

@include('onglet.section-main-home-et-sport')
@include('onglet.section-droite-home-et-sport')

@section('script')
    <script>
        $(document).ready(function() {
            // Gestion des onglets dans le main
            var cibles = qsa('#fil-actu-content,#resultats-content,#a-la-une-content')
            var onglets = qsa('#onglets-content .onglet') 
            ongletSwitch(cibles, onglets)

            // Gestion des onglets du bloc de droite
            cibles = qsa('#prochains-section-droite,#resultats-section-droite,#fil-actu-section-droite')
            onglets = qsa('#section-droite .onglet') 
            ongletSwitch(cibles, onglets)
        })
    </script>
@endsection
