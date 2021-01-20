<?php
    $sports = index('sports')
        ->sortBy('home_position')
        ->slice(0, 5);
?>

<!-- Footer -->
<footer class="col-12 footer font-small indigo text-white bg-dark mt-auto">
    <!-- Footer Links -->
    <div class="container text-center text-md-left">
        <div class="row d-flex flex-basis-1 text-center">
            @foreach ($sports as $sport)
                <div class="col-6 col-md-4 col-lg-2 px-3">
                    <a href="{{ route('sport.index', ['sport' => \Str::slug($sport->nom)]) }}">
                        <h5 class="font-weight-bold mt-3 mb-2 text-white text-center">{{ $sport->nom }}</h5>
                    </a>
                    <ul class="list-unstyled">
                        <?php 
                            $competitions = index('competitions')
                                ->where('sport_id', $sport->id)
                                ->sortByDesc('index_position')
                                ->slice(0, 5);
                        ?>
                        @foreach ($competitions as $competition)
                            <li>
                                <a class="text-light"
                                    href="{{ route('competition.index', ['sport' => \Str::slug($sport->nom), 'competition' => \Str::slug($competition->nom)]) }}">{{ $competition->nom }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
            <div class="col-6 col-md-4 col-lg-2 px-3">
                <a href="{{ route('home') }}">
                    <h5 class="font-weight-bold mt-3 mb-2 text-white text-center">Accueil</h5>
                </a>
                <ul class="list-unstyled">
                    <li>
                        <a class="text-light" href="{{ route('contact') }}">Contactez-nous</a>
                    </li>
                    <li>
                        <a class="text-light" href="{{ route('politique') }}">Notre politique des cookies</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Footer Links -->

    <!-- Social buttons -->
    <div class="row footer-social justify-content-center py-2 border-top border-secondary">
            <a target="_blank" href="https://www.facebook.com/mayottesport.actu" class="mx-3"><img src="{{ asset('storage/img/facebook.png') }}" alt="MayotteSport sur Facebook" title="MayotteSport sur Facebook" class="img-fluid bg-dark"></a>
            <a target="_blank" href="https://twitter.com/mayottesport" class="mx-3"><img src="{{ asset('storage/img/twitter.png') }}" alt="MayotteSport sur Twitter" title="MayotteSport sur Twitter" class="img-fluid bg-dark"></a>
            <a target="_blank" href="https://www.instagram.com/mayottesport.actu" class="mx-3"><img src="{{ asset('storage/img/instagram.png') }}" alt="MayotteSport sur Instagram" title="MayotteSport sur Instagram" class="img-fluid bg-dark"></a>
    </div>
    <!-- Social buttons -->

    <!-- Copyright -->
    <div class="row footer-copyright justify-content-center text-center py-3 bg-body">© {{ date('Y') }} Copyright:
        <a class="px-1 text-green-light" href="{{ asset('/') }}">mayottesport.com</a>
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->

@include('cookieConsent::index')

<script src="{{ asset(mix('js/app.js')) }}"></script>
<script src="{{ asset('node_modules/select2/select2.js') }}"></script>
<script src="{{ asset('node_modules/tinymce/tinymce.js') }}"></script>
<script src="{{ asset('/js/outils.js') }}"></script>
<script>
    $(document).ready(function() {
        // Centrage du lien actif dans le menu déroulant
        let navbarScrollX = $('#navbar-scroll-x')
        let active = $('#navbar-scroll-x .active')
        centerItVariableWidth(active, navbarScrollX)

        // On empèche de cliquer sur les liens non-cliquables
        $('.non-cliquable').on('click', function(e) {
            e.preventDefault()
        })

        // $('#cookiesParametres').on('shown.bs.modal', function () {
        //     $('#cookiesParametresBouton').trigger('focus')
        // })
    })
</script>
@yield('script')
