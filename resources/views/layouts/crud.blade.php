<?php
    $crudTable = request()->crudTable;
    $navbarCrudTables = App\CrudTable::navbarCrudTables();

    // On place la table au début de la collection
    $crud = $navbarCrudTables[$crudTable->nom];
    $navbarCrudTables->pull($crudTable->nom);
    $navbarCrudTables = $navbarCrudTables->prepend($crud, $crudTable->nom);
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/fontello/css/fontello.css">
    <link rel="stylesheet" href="/css/select2.min.css">
    <link rel="stylesheet" href="/css/datatables.min.css">
    <link rel="stylesheet" href="/css/style.css?t={{ now() }}">{{-- A ENLEVER EN PRODUCTION --}}
    <title>@yield('title') | mayottesport.com</title>
</head>
<body>
    {{-- Navbar principal --}}
    @include('layouts.include.navbar-admin')
    {{-- Fin Navbar principal --}}

    {{-- Section scroll X --}}
    <div class="container-lg-fluid border-bottom bg-white" {{-- style="background-color: rgba(255, 255, 255, 0.7) !important" --}}>
        <div class="container-lg">
            <div class="row overflow-x-auto py-3" id="navbar-scroll-x">
                <div class="d-flex justify-content-start align-items-center pl-3 flex-shrink-0" style="margin:0;font-size:0.9rem">
                    @foreach ($navbarCrudTables as $table)
                    <a class="mr-3" href="{{ $table['route'] }}">
                        <button class="btn btn-sm px-3 btn-outline-dark @if ($table['nom'] == $crudTable->nom) btn-dark text-white @endif">
                            {{ $table['nom_pascal_case'] }}
                        </button>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    {{-- Fin Section scroll X --}}

    <section class="crud container-lg">
        @yield('content')
    </section>

    {{-- Footer --}}
    @include('layouts.include.footer')
</body>

</html>
