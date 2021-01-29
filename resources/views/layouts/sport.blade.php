<?php
    $sport = request()->sport; // Middleware Sport
    $competitions = $sport->competitionsNavbar()->orderBy('position')->get();
?>

{{-- Header --}}
@include('layouts.include.header')

<body class="d-flex flex-wrap">
    {{-- Navbar principal --}}
    @include('layouts.include.navbar-classique')

    <div class="col-12 mx-auto p-0">
        @if(count($competitions) > 0)
        {{-- Section scroll X --}}
        <section class="col-12 p-0 navbar-scroll-x top-main-site container-lg-fluid">
            <div class="container-lg">
                <div class="row overflow-x-auto py-3" id="navbar-scroll-x">
                    <div class="d-flex justify-content-start align-items-center flex-shrink-0">
                        @foreach ($competitions as $i => $competition)
                            <a class="mr-3 @if($i==0) ml-3 @endif" href="{{ route('competition.index', ['sport' => $sport->slug, 'competition' => $competition->slug_complet]) }}">
                                <button class="btn btn-sm px-3 btn-white bg-white">
                                    {{ $competition->nom }}
                                </button>
                            </a>
                        @endforeach

                        {{-- Si on a plus de compétitions que affichés --}}
                        @if(count($sport->competitions) > count($competitions))
                            <button class="mr-3 btn btn-link" type="button" data-toggle="modal" data-target="#navbarModal" data-sport="{{ $sport->slug }}">Voir+</button>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        @endif

        <div class="d-flex flex-wrap col-12 justify-content-center mx-auto mb-auto p-0 @if(count($competitions) == 0) top-main-site @endif" style="max-width: 1300px">
            <main class="col-12 @if(View::hasSection('section-droite')) col-lg-8 @endif p-0">
                
                @yield('content')
            </main>
            @if(View::hasSection('section-droite'))
            <section id="section-droite" class="col-4 d-none d-lg-block pl-0">
                @yield('section-droite')
            </section>
            @endif
        </div>
    </div>

    {{-- Footer --}}
    @include('layouts.include.footer')
</body>

</html>
