<!doctype html>
<html lang="fr">

<?php
    // $hrefIndex = request()->hrefIndex;
    // $competitions = request()->competitions;
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
            <div class="row overflow-x-auto py-3 border" id="navbar-scroll-x">
                <div class="d-flex justify-content-start align-items-center px-3">
                    <span class="flex-shrink-0 text-body font-weight-bold pr-3 float-left nom-competition">
                        {{ $equipe->nom }}
                    </span>
                    <span class="pr-3">
                        <i class="fas fa-angle-right"></i>
                    </span>
                    @foreach ($competitions as $competition)
                        <a href="{{ route('competition.index', ['competition' => strToUrl($competition->nom), 'sport' => strToUrl($sport->nom)]) }}" class="flex-shrink-0 text-body font-weight-bold pr-3">{{ $competition->nom }}</a>
                    @endforeach
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
