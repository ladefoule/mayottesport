<?php
    $sport = request()->sport; // Middleware Sport
    $competitions = index('competitions')->where('sport_id', $sport->id);
?>

{{-- Header --}}
@include('layouts.include.header')
{{-- Fin Header --}}

<body class="d-flex flex-column">
    {{-- Navbar principal --}}
    @include('layouts.include.navbar-sports')
    {{-- Fin Navbar principal --}}

    {{-- Section scroll X --}}
    <section class="navbar-scroll-x container-lg-fluid bg-white border-bottom">
        <div class="container-lg">
            <div class="row overflow-x-auto py-3" id="navbar-scroll-x">
                <div class="d-flex justify-content-start align-items-center px-3 flex-shrink-0">
                    @foreach ($competitions as $competition)
                        <a class="mr-3" href="{{ route('competition.index', ['sport' => strToUrl($sport->nom), 'competition' => strToUrl($competition->nom)]) }}">
                            <button class="btn btn-sm px-3 btn-outline-dark">
                                {{ $competition->nom }}
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
