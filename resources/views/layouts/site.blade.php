{{-- Header --}}
@include('layouts.include.header')
{{-- Fin Header --}}

<body class="d-flex flex-wrap">
    {{-- Navbar principal --}}
    @include('layouts.include.navbar-sports')
    {{-- Fin Navbar principal --}}

    {{-- Section scroll X --}}
    <main class="container-lg top-50 bg-white p-3">
        @yield('content')
    </main>
    {{-- Fin Section scroll X --}}

    {{-- Footer --}}
    @include('layouts.include.footer')
    {{-- Fin Footer --}}

</body>

</html>
