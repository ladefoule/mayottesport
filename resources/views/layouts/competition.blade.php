<?php
    $hrefSport= request()->hrefSport;
    $hrefIndex = request()->hrefIndex;
    $hrefActualite = request()->hrefActualite;
    $hrefClassement = request()->hrefClassement;
    $hrefCalendrier = request()->hrefCalendrier;
    $hrefPalmares = request()->hrefPalmares;
    $competition = request()->competition;
    $sport = request()->sport;
    $routeName = request()->route()->getName();
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
                    <a href="{{ $hrefSport }}" class="text-body font-weight-bold mr-3 nom-sport">
                        {{ $sport->nom }}
                    </a>
                    <a href="" class="mr-3 non-cliquable cursor-default">
                        {!! \Config::get('listes.boutons.right') !!}
                    </a>
                    <a href="{{ $hrefIndex }}" class="text-body font-weight-bold mr-3 nom-competition">
                        {{ $competition->nom }}
                    </a>
                    <a href="" class="mr-3 non-cliquable cursor-default">
                        {!! \Config::get('listes.boutons.right') !!}
                    </a>
                    <a href="{{ $hrefActualite }}" class="border-bottom-scroll-x font-weight-bold @if(request()->route()->getName() == 'competition.actualite') active @else text-secondary @endif pr-3">
                        L'actu
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
                    <a href="{{ $hrefPalmares }}" class="border-bottom-scroll-x font-weight-bold @if(request()->route()->getName() == 'competition.champions') active @else text-secondary @endif">
                        Le palmarès
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{-- Fin Section scroll X --}}

    {{-- Main --}}
    <main class="container-lg bg-white">
        @yield('content')
    </main>
    {{-- Fin Main --}}

    {{-- Footer --}}
    @include('layouts.include.footer')
    {{-- Fin Footer --}}
</body>

</html>
