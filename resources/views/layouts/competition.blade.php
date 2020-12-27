<?php
    $hrefIndex = request()->hrefIndex;
    $hrefClassement = request()->hrefClassement;
    $hrefCalendrier = request()->hrefCalendrier;
    $hrefPalmares = request()->hrefPalmares;
    $competition = request()->competition;
    $routeName = request()->route()->getName();
?>

{{-- Header --}}
@include('layouts.include.header')
{{-- Fin Header --}}

<body class="d-flex flex-column">
    {{-- Navbar principal --}}
    @include('layouts.include.navbar-sports')
    {{-- Fin Navbar principal --}}

    {{-- Section scroll X --}}
    <div class="navbar-scroll-x container-lg-fluid bg-white border-bottom"{{--  style="background-color: rgba(255, 255, 255, 0.7) !important" --}}>
        <div class="container">
            <div class="row overflow-x-auto py-3" id="navbar-scroll-x">
                <div class="d-flex justify-content-start align-items-center px-3 flex-shrink-0">
                    <a href="{{ $hrefIndex }}" class="text-body font-weight-bold mr-3 nom-competition">
                        {{ $competition->nom }}
                    </a>
                    <a href="" class="mr-3 non-cliquable cursor-default">
                        {!! \Config::get('listes.boutons.right') !!}
                    </a>
                    @if ($hrefClassement)
                    <a href="{{ $hrefClassement }}" class="@if(request()->route()->getName() == 'competition.classement') text-body font-weight-bold active @else text-secondary @endif pr-3">
                        Le classement
                    </a>
                    @endif
                    @if ($hrefCalendrier)
                    <a href="{{ $hrefCalendrier }}" class="d-flex @if(request()->route()->getName() == 'competition.calendrier-resultats') text-body font-weight-bold active @else text-secondary @endif pr-3">
                        Calendrier et résultats
                    </a>
                    @endif
                    <a href="{{ $hrefPalmares }}" class="@if(request()->route()->getName() == 'competition.champions') text-body font-weight-bold active @else text-secondary @endif">
                        Le palmarès
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{-- Fin Section scroll X --}}

    {{-- Main --}}
    <section class="container-lg {{-- p-2 --}} bg-fond">
        @yield('content')
    </section>
    {{-- Fin Main --}}

    {{-- Footer --}}
    @include('layouts.include.footer')
    {{-- Fin Footer --}}
</body>

</html>
