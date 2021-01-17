<?php
    $hrefSport= request()->hrefSport;
    $hrefIndex = request()->hrefIndex;
    $hrefClassement = request()->hrefClassement;
    $hrefCalendrier = request()->hrefCalendrier;
    $hrefPalmares = request()->hrefPalmares;
    $competition = request()->competition;
    $sport = request()->sport;
    $routeName = request()->route()->getName();

    $resultats = request()->resultats;
    $prochains = request()->prochains;
?>

{{-- Header --}}
@include('layouts.include.header')
{{-- Fin Header --}}

<body>
    {{-- Navbar principal --}}
    @include('layouts.include.navbar-sports')
    {{-- Fin Navbar principal --}}

    {{-- Section scroll X --}}
    <div class="navbar-scroll-x container-fluid bg-white border-bottom">
        <div class="container">
            <div class="row overflow-x-auto py-3" id="navbar-scroll-x">
                <div class="d-flex justify-content-start align-items-center px-3 flex-shrink-0">
                    <a href="{{ $hrefSport }}" class="d-lg-none font-weight-bold text-secondary mr-3 border-bottom-scroll-x">
                        {{ $sport->nom }}
                    </a>
                    <a href="" class="d-lg-none mr-3 non-cliquable cursor-default">
                        {!! \Config::get('listes.boutons.right') !!}
                    </a>
                    <a href="{{ $hrefIndex }}" class="font-weight-bold mr-3 border-bottom-scroll-x @if(request()->route()->getName() == 'competition.index') active @else text-body @endif">
                        {{ Str::upper($competition->nom) }}
                    </a>
                    <a href="" class="mr-3 non-cliquable cursor-default">
                        {!! \Config::get('listes.boutons.right') !!}
                    </a>
                    @if ($hrefClassement)
                    <a href="{{ $hrefClassement }}" class="border-bottom-scroll-x font-weight-bold @if(request()->route()->getName() == 'competition.classement') active @else text-secondary @endif pr-3">
                        Le classement
                    </a>
                    @endif
                    @if ($hrefCalendrier)
                    <a href="{{ $hrefCalendrier }}" class="border-bottom-scroll-x font-weight-bold @if(request()->route()->getName() == 'competition.calendrier-resultats') active @else text-secondary @endif pr-3">
                        Calendrier et résultats
                    </a>
                    @endif
                    <a href="{{ $hrefPalmares }}" class="border-bottom-scroll-x font-weight-bold @if(request()->route()->getName() == 'competition.palmares') active @else text-secondary @endif">
                        Le palmarès
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{-- Fin Section scroll X --}}

    <div class="d-flex flex-wrap col-12 justify-content-center mx-auto p-0" style="max-width: 1400px">
        <main class="col-lg-8 col-xl-8 p-0">
            @yield('content')
        </main>
        <section id="section-droite" class="col-4 d-none d-lg-block pl-0">
            <div class="my-3 bg-white" {{-- style="background-color:#ebeff3" --}}>
                <div class="col-12 d-flex text-center p-3 bg-white">
                    <a href="" data-cible="resultats"
                        class="d-block col-6 p-3 border btn btn-secondary onglet @if($resultats) active @endif">Résultats</a>
                    <a href="" data-cible="prochains"
                        class="d-block col-6 p-3 border btn btn-secondary onglet @if(! $resultats) active @endif">À venir</a>
                </div>
                <div class="bloc-resultats col-12 px-2 pt-0 @if(! $resultats) d-none @endif">
                    @foreach ($resultats as $resultat)
                        <div class="p-3">
                            {!! $resultat !!}
                        </div>
                    @endforeach
                </div>
                <div class="bloc-prochains col-12 px-2 pt-0 d-none @if(!$resultats) d-block @endif">
                    @foreach ($prochains as $prochain)
                        <div class="p-3">
                            {!! $prochain !!}
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

    {{-- Footer --}}
    @include('layouts.include.footer')
    {{-- Fin Footer --}}
</body>

</html>
<script>
    $(document).ready(function() {
        // Gestion des onglets dans le main
        var cibles = qsa('main .bloc-prochains,main .bloc-resultats,main .bloc-actualites')
        var onglets = qsa('main .onglet') 
        ongletSwitch(cibles, onglets)

        // Gestion des onglets du bloc de droite
        cibles = qsa('#section-droite .bloc-prochains,#section-droite .bloc-resultats')
        onglets = qsa('#section-droite .onglet') 
        ongletSwitch(cibles, onglets)
    })
</script>
