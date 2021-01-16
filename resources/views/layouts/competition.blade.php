<?php
    $hrefSport= request()->hrefSport;
    $hrefIndex = request()->hrefIndex;
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
                    <a href="{{ $hrefSport }}" class="d-lg-none font-weight-bold text-secondary mr-3 border-bottom-scroll-x">
                        {{ $sport->nom }}
                    </a>
                    <a href="" class="d-lg-none mr-3 non-cliquable cursor-default">
                        {!! \Config::get('listes.boutons.right') !!}
                    </a>
                    <a href="{{ $hrefIndex }}" class="font-weight-bold mr-3 border-bottom-scroll-x @if(request()->route()->getName() == 'competition.index') active @else text-secondary @endif">
                        {{ Str::upper($competition->nom) }}
                    </a>
                    <a href="" class="mr-3 non-cliquable cursor-default">
                        {!! \Config::get('listes.boutons.right') !!}
                    </a>
                    {{-- <a href="{{ $hrefActualite }}" class="border-bottom-scroll-x font-weight-bold @if(request()->route()->getName() == 'competition.index') active @else text-secondary @endif pr-3">
                        L'actualité
                    </a> --}}
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
        @if(View::hasSection('section-droite'))
        <section id="section-droite" class="col-4 d-none d-lg-block pl-0">
            @yield('section-droite')
        </section>
        @endif
    </div>

    {{-- Footer --}}
    @include('layouts.include.footer')
    {{-- Fin Footer --}}
</body>

</html>
