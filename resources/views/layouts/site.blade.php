@include('layouts.include.header')

<body class="d-flex flex-wrap">
    @include('layouts.include.navbar-classique')

    <div class="d-flex flex-wrap col-12 justify-content-center mx-auto p-0 top-main-site" style="max-width: 1300px">
        <main class="col-12 @if(View::hasSection('section-droite')) col-lg-8 @endif p-0">
            @yield('content')
        </main>
        @if(View::hasSection('section-droite'))
            <section id="section-droite" class="col-4 d-none d-lg-block pl-0">
                @yield('section-droite')
            </section>
        @endif
    </div>

    @include('layouts.include.footer')
</body>
</html>
