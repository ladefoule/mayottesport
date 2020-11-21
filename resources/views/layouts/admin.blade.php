<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/datatables.min.css">
    <link rel="stylesheet" href="/css/select2.min.css">
    <link rel="stylesheet" href="/css/style.css?t={{ now() }}">{{-- A ENLEVER EN PRODUCTION --}}
    <title>@yield('title') | mayottesport.com</title>
</head>

<body>
    {{-- Navbar principal --}}
    @include('layouts.include.navbar-admin')
    {{-- Fin Navbar principal --}}

    {{-- Section scroll X --}}
    <section class="container-lg-fluid border-bottom" style="background-color: rgba(255, 255, 255, 0.7) !important">
        <div class="container-lg">
            <div class="row overflow-x-auto py-3 mx-0" id="navbar-scroll-x">
                <div class="d-flex justify-content-start align-items-center pl-3" style="margin:0;font-size:0.9rem">
                    <a href="{{ route('journees.multi.select') }}"><button class="btn btn-sm mx-2 btn-outline-dark">Journées (multi)</button></a>
                    {{-- <a href="{{ route('matches.lister') }}"><button class="btn mx-2 @if(Str::endsWith($routeFoot, $path)) btn-light @else btn-outline-light @endif">Matches de foot</button></a> --}}
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
