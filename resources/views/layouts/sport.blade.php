<?php
    $sport = request()->sport; // Middleware Sport
    $competitions = index('competitions')->where('sport_id', $sport->id);
?>

{{-- Header --}}
@include('layouts.include.header')
{{-- Fin Header --}}

<body class="d-flex flex-wrap align-items-stretch">
    {{-- Navbar principal --}}
    @include('layouts.include.navbar-sports')
    {{-- Fin Navbar principal --}}

    @if(count($competitions) > 0)
    {{-- Section scroll X --}}
    <section class="col-12 navbar-scroll-x container-lg-fluid">
        <div class="container-lg">
            <div class="row overflow-x-auto py-3" id="navbar-scroll-x">
                <div class="d-flex justify-content-start align-items-center px-0 flex-shrink-0">
                    @foreach ($competitions as $competition)
                        <a class="mr-3" href="{{ route('competition.index', ['sport' => \Str::slug($sport->nom), 'competition' => \Str::slug($competition->nom)]) }}">
                            <button class="btn btn-sm px-3 btn-light">
                                {{ $competition->nom }}
                            </button>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    {{-- Fin Section scroll X --}}
    @endif

    {{-- Main --}}
    <section class="container-lg bg-white">
        @yield('content')
    </section>
    {{-- Fin Main --}}

    {{-- Footer --}}
    @include('layouts.include.footer')
    {{-- Fin Footer --}}
</body>

</html>
