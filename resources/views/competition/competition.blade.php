<!doctype html>
<html lang="fr">

<?php
    $hrefIndex = request()->hrefIndex;
    $hrefClassement = request()->hrefClassement;
    $hrefCalendrier = request()->hrefCalendrier;
    $hrefPalmares = request()->hrefPalmares;
    $competition = request()->competition;
?>

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/datatables.min.css">
    <link rel="stylesheet" href="/css/select2.min.css">
    <link rel="stylesheet" href="/css/style.css?t={{ now() }}">{{-- A ENLEVER EN PRODUCTION --}}
    <title>@yield('title') | mayottesport.com</title>
</head>

<body>
    {{-- Navbar principal --}}
    @include('layouts.include.navbar-sports')
    {{-- Fin Navbar principal --}}

    {{-- Section scroll X --}}
    <section class="container-lg-fluid border-bottom" style="background-color: rgba(255, 255, 255, 0.7) !important">
        <div class="container">
            <div class="row overflow-x-auto py-3" id="navbar-scroll-x">
                <div class="d-flex justify-content-start align-items-center px-3">
                    <a href="{{ $hrefIndex }}" class="flex-shrink-0 text-body font-weight-bold pr-3 float-left nom-competition">
                        {{ $competition->nom }}
                    </a>
                    <span class="pr-3">
                        <i class="fas fa-angle-right"></i>
                    </span>
                    @if ($hrefClassement)
                    <a href="{{ $hrefClassement }}" class="flex-shrink-0 @if(request()->route()->getName() == 'competition.classement') text-info @else text-secondary @endif font-weight-bold pr-3">
                        Le classement
                    </a>
                    @endif
                    @if ($hrefCalendrier)
                    <a href="{{ $hrefCalendrier }}" class="d-flex flex-shrink-0 @if(request()->route()->getName() == 'competition.calendrier-resultats') text-danger @else text-secondary @endif font-weight-bold pr-3">
                        Les résultats
                    </a>
                    @endif
                    <a href="{{ $hrefPalmares }}" class="flex-shrink-0 @if(request()->route()->getName() == 'competition.champions') text-success @else text-secondary @endif font-weight-bold">
                        Le palmarès
                    </a>
                </div>
            </div>
        </div>
    </section>
    {{-- Fin Section scroll X --}}

    {{-- Main --}}
    <section class="container-lg p-3">
        @yield('content')
    </section>
    {{-- Fin Main --}}

    {{-- Footer --}}
    @include('layouts.include.footer')
    {{-- Fin Footer --}}
</body>

</html>
