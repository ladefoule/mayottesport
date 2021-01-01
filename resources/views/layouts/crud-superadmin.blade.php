<?php
    $tablesSuperAdmin = index('crud_tables')->whereIn('nom', config('listes.tables-superadmin'))
        ->merge(index('crud_tables')->whereIn('nom', config('listes.tables-gestion-crud')));
?>

{{-- Header --}}
@include('layouts.include.header')
{{-- Fin Header --}}

<body class="d-flex flex-column">
    {{-- Navbar principal --}}
    @include('layouts.include.navbar-admin')
    {{-- Fin Navbar principal --}}

    {{-- Section scroll X --}}
    <section class="navbar-scroll-x container-lg-fluid border-bottom bg-white">
        <div class="container-lg">
            <div class="row overflow-x-auto py-3" id="navbar-scroll-x">
                <div class="d-flex justify-content-start align-items-center pl-3 flex-shrink-0">
                    @foreach ($tablesSuperAdmin as $table)
                    <a class="@if (isset(request()->crudTable) && $table->nom == request()->crudTable->nom) active @endif" href="{{ route('crud.index', ['table' => Str::slug($table->nom)]) }}">
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
    <section class="container-lg h-100 bg-white">
        @yield('content')
    </section>
    {{-- Fin Main --}}

    {{-- Footer --}}
    @include('layouts.include.footer')
    {{-- Fin Footer --}}
</body>

</html>
