{{-- Header --}}
@include('layouts.include.header')
{{-- Fin Header --}}

<body>
    {{-- Navbar principal --}}
    @include('layouts.include.navbar-sports')
    {{-- Fin Navbar principal --}}

    {{-- Section scroll X --}}
    <section class="container-lg">
        @yield('content')
    </section>
    {{-- Fin Section scroll X --}}

    {{-- Footer --}}
    @include('layouts.include.footer')
    {{-- Fin Footer --}}
</body>

</html>
