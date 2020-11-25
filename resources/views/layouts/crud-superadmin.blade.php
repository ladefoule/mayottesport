<?php
    $tablesSuperAdmin = App\CrudTable::whereIn('nom', config('constant.superadmin-tables'))->orderBy('nom')->get();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/fontello/css/fontello.css">
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
                <div class="d-flex justify-content-start align-items-center pl-3 flex-shrink-0" style="margin:0;font-size:0.9rem">
                    @foreach ($tablesSuperAdmin as $table)
                    <a href="{{ route('crud.index', ['table' => str_replace('_', '-', $table->nom)]) }}">
                        {{-- Le isset en dessous c'est pour pouvoir accéder à la page des tables 'crudables' qui n'est pas liée au middleware VerifTable --}}
                        <button class="btn btn-sm mx-2 px-3 btn-outline-dark @if (isset(request()->crudTable) && $table->nom == request()->crudTable->nom) btn-dark text-white @endif">
                            {{ \Str::ucfirst(\Str::camel($table->nom)) }}
                        </button>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    {{-- Fin Section scroll X --}}

    {{-- Main --}}
    <section class="container-lg">
        @yield('content')
    </section>
    {{-- Fin Main --}}

    {{-- Footer --}}
    @include('layouts.include.footer')
    {{-- Fin Footer --}}
</body>

</html>
