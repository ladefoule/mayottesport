<?php
    $sports = index('sports')
        ->sortBy('home_position')
        ->slice(0, 5);
?>

<!-- Footer -->
<footer class="col-12 footer font-small indigo text-white bg-dark mt-auto border border-danger">
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
            <div class="col-4 col-md-2 px-1">
                <h5 class="font-weight-bold mt-3 mb-2">MS.com</h5>
                <ul class="list-unstyled">
                    <li>
                        <a class="text-light" href="#!">S'inscrire</a>
                    </li>
                    <li>
                        <a class="text-light" href="#!">Se connecter</a>
                    </li>
                    <li>
                        <a class="text-light" href="#!">Contact</a>
                    </li>
                    <li>
                        <a class="text-light" href="#!">Cookies</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Footer Links -->

    @include('cookieConsent::index')

    <!-- Copyright -->
    <div class="row footer-copyright justify-content-center text-center py-3 bg-body">© {{ date('Y') }} Copyright:
        <a class="px-1 text-green-light" href="{{ asset('/') }}">mayottesport.com</a> - FB - TW
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->

<!-- Modal -->
<div class="modal fade" id="cookiesParametres" tabindex="-1" aria-labelledby="cookiesParametresLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cookiesParametresLabel">Paramétrer l'utilisation des cookies</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="px-3 mx-3 text-left">
                    <span class="text-left">
                        Merci de paramétrer vos choix concernant l'utilisation des cookies sur notre site.
                    </span>
                    <div>
                        <div class="d-flex justify-content-end align-items-center">
                            <span>Cookies de fonctionnement (obligatoires)</span>
                        </div>
                
                        <div class="d-flex justify-content-end align-items-center">
                            <span>Google Analytics</span>
                            <label class="ml-3 switch m-1">
                                <input type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('politique') }}">
                    <button class="btn btn-link text-white bg-secondary">Notre politique de confidentialité</button>
                </a>
                <button class="js-cookie-consent-agree cookie-consent__agree btn btn-primary" {{-- data-dismiss="modal" --}}>Valider</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset(mix('js/app.js')) }}"></script>
<script src="{{ asset('node_modules/select2/select2.js') }}"></script>
<script src="{{ asset('node_modules/tinymce/tinymce.js') }}"></script>
<script src="{{ asset('/js/outils.js') }}"></script>
<script>
    $(document).ready(function() {
        var navbarMobile = qs('.navbar-mobile')

        // Affichage du menu Mobile
        $('.navbar-toggler').on('click', function() {
            let state = navbarMobile.dataset.state
            if (state == 'hidden') {
                navbarMobile.style.left = 0
                state = 'visible'
            } else if (state == 'visible') {
                navbarMobile.style.left = '-250px'
                state = 'hidden'
            }

            navbarMobile.dataset.state = state
        })

        // Masquage du menu Mobile
        $('footer,section').on('click', function(e) {
            if (e.target != navbarMobile && navbarMobile.dataset.state == 'visible') {
                navbarMobile.dataset.state = 'hidden'
                navbarMobile.style.left = '-250px'
            }
        })

        // Centrage du lien actif dans le menu déroulant
        let navbarScrollX = $('#navbar-scroll-x')
        let active = $('#navbar-scroll-x .active')
        centerItVariableWidth(active, navbarScrollX)

        // On empèche de cliquer sur les liens non-cliquables
        $('.non-cliquable').on('click', function(e) {
            e.preventDefault()
        })

        $('#cookiesParametres').on('shown.bs.modal', function () {
            $('#cookiesParametresBouton').trigger('focus')
        })
    })
</script>
@yield('script')
