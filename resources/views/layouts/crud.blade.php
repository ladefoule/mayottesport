<?php
    $navbarCrudTables = App\CrudTable::navbarCrudTables();
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/select2.min.css">
    <link rel="stylesheet" href="/css/datatables.min.css">
    <link rel="stylesheet" href="/css/style.css?t={{ now() }}">{{-- A ENLEVER EN PRODUCTION --}}
    <title>@yield('title') | mayottesport.com</title>
</head>
<body>
    @include('layouts.include.navbar-admin')
    <div class="container-lg-fluid border-bottom" style="background-color: rgba(255, 255, 255, 0.7) !important">
        <div class="container-lg">
            <div class="row overflow-x-auto py-3 mx-0" id="navbar-scroll-x">
                <div class="d-flex justify-content-start align-items-center pl-3 flex-shrink-0" style="margin:0;font-size:0.9rem">
                    @foreach ($navbarCrudTables as $table)
                    <a href="{{ $table['route'] }}">
                        <button class="btn btn-sm mx-2 px-3 btn-outline-dark">
                            {{ $table['nom_pascal_case'] }}
                        </button>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <section class="container-lg p-3">
        @yield('content')
    </section>

    {{-- Footer --}}
    @include('layouts.include.footer')
</body>

</html>
