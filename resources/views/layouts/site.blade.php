{{-- Header --}}
@include('layouts.include.header')
{{-- Fin Header --}}

<body class="d-flex flex-wrap">
    {{-- Navbar principal --}}
    @include('layouts.include.navbar-sports')
    {{-- Fin Navbar principal --}}

    {{-- Section scroll X --}}
    <div class="d-flex flex-wrap col-12 justify-content-center mx-auto p-0 top-main-site" style="max-width: 1400px">
        {{-- <section class="col-3 d-none d-xl-block border border-success">
            @yield('section-gauche')
        </section> --}}
        <main class="col-lg-8 col-xl-8 p-0 {{-- mx-auto mx-0 --}} border border-danger">
            @yield('content')
        </main>
        @if(View::hasSection('section-droite'))
        <section class="col-4 d-none d-lg-block border border-success">
            @yield('section-droite')
        </section>
        @endif
    </div>
    {{-- Fin Section scroll X --}}

    {{-- Footer --}}
    @include('layouts.include.footer')
    {{-- Fin Footer --}}

</body>

</html>
