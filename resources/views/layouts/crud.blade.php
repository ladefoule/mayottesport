<?php
    $crudTable = request()->crudTable;
    $navbarCrudTables = App\CrudTable::navbarCrudTables();

    // On place la table au début de la collection
    $crud = $navbarCrudTables[$crudTable->nom];
?>

{{-- Header --}}
@include('layouts.include.header')
{{-- Fin Header --}}

<body class="d-flex flex-wrap">
    {{-- Navbar principal --}}
    @include('layouts.include.navbar-admin')
    {{-- Fin Navbar principal --}}

    {{-- Section scroll X --}}
    <div class="col-12 navbar-scroll-x container-lg-fluid border-bottom bg-white" {{-- style="background-color: rgba(255, 255, 255, 0.7) !important" --}}>
        <div class="container-lg">
            <div class="row overflow-x-auto py-3" id="navbar-scroll-x">
                <div class="d-flex justify-content-start align-items-center pl-3 flex-shrink-0" style="margin:0;font-size:0.9rem">
                    @foreach ($navbarCrudTables as $table)
                    <a class="mr-3 @if ($table['nom'] == $crudTable->nom) active @endif" href="{{ $table['route'] }}">
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

    <section class="container-lg {{-- bg-white --}} mb-auto">
        @yield('content')
    </section>

    {{-- Footer --}}
    @include('layouts.include.footer')
</body>

</html>
