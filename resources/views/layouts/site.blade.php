{{-- Header --}}
@include('layouts.include.header')
{{-- Fin Header --}}

<body class="d-flex flex-column">
    {{-- Navbar principal --}}
    @include('layouts.include.navbar-sports')
    {{-- Fin Navbar principal --}}

    {{-- Section scroll X --}}
    <section class="container-lg top-50 p-2 bg-fond">
        @yield('content')
    </section>
    {{-- Fin Section scroll X --}}

    {{-- Footer --}}
    @include('layouts.include.footer')
    {{-- Fin Footer --}}

</body>

</html>
