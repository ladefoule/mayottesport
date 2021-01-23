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

<body class="d-flex flex-wrap">
    {{-- Navbar principal --}}
    @include('layouts.include.navbar-classique')
    {{-- Fin Navbar principal --}}

    <div class="col-12 mx-auto p-0">
        {{-- Section scroll X --}}
        <div class="navbar-scroll-x top-main-site container-fluid bg-white border-bottom">
            <div class="container">
                <div class="row overflow-x-auto py-3" id="navbar-scroll-x">
                    <div class="d-flex justify-content-start align-items-center p-0 flex-shrink-0">
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

        <div class="d-flex flex-wrap col-12 justify-content-center mx-auto p-0" style="max-width: 1300px">
            <main class="col-lg-8 col-xl-8 p-0">
                @yield('content')
            </main>
            <section id="section-droite" class="col-4 d-none d-lg-block pl-0">
                <div class="my-3 bg-white shadow-div">
                    <div class="col-12 d-flex text-center p-2">
                        <span data-cible="resultats-section-droite"
                            class="d-block col-6 p-3 border btn btn-secondary onglet @if($resultats) active @endif">Résultats</span>
                        <span data-cible="prochains-section-droite"
                            class="d-block col-6 p-3 border btn btn-secondary onglet @if(! $resultats) active @endif">À venir</span>
                    </div>
                    <div id="resultats-section-droite" class="col-12 px-2 pt-0 @if(! $resultats) d-none @endif">
                        @if($resultats)
                            <p class="col-12 nom-competition m-0 border-bottom-calendrier text-center p-0">
                                <a href="{{ $hrefIndex }}">
                                    {{ $competition->nom }}
                                </a>
                            </p>
                        @endif
                        @foreach ($resultats as $resultat)
                            <div class="p-3">
                                {!! $resultat !!}
                            </div>
                        @endforeach
                    </div>
                    <div id="prochains-section-droite" class="col-12 px-2 pt-0 d-none @if(!$resultats) d-block @endif">
                        @if($prochains)
                            <p class="col-12 nom-competition m-0 border-bottom-calendrier text-center p-0">
                                <a href="{{ $hrefIndex }}">
                                    {{ $competition->nom }}
                                </a>
                            </p>
                        @endif
                        @foreach ($prochains as $prochain)
                            <div class="p-3">
                                {!! $prochain !!}
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </div>

    {{-- Footer --}}
    @include('layouts.include.footer')
    {{-- Fin Footer --}}
</body>

</html>
<script>
    $(document).ready(function() {
        // Gestion des onglets dans le main
        var cibles = qsa('#prochains-content,#resultats-content,#actualites-content')
        var onglets = qsa('main .onglet') 
        ongletSwitch(cibles, onglets)

        // Gestion des onglets du bloc de droite
        cibles = qsa('#prochains-section-droite,#resultats-section-droite')
        onglets = qsa('#section-droite .onglet') 
        ongletSwitch(cibles, onglets)
    })
</script>
