<?php
    $sports = index('sports');
    $competitions = index('competitions');
?>

<!-- Footer -->
<footer class="col-12 footer font-small indigo text-white h-auto bg-dark mt-auto">
    <!-- Footer Links -->
    <div class="container text-center text-md-left">
        <div class="row d-flex flex-basis-1 text-center">
            @foreach ($sports as $sport)
                <div class="col-6 col-md-4 col-lg-2 p-0">
                    <a href="{{ route('sport.index', ['sport' => $sport->slug]) }}">
                        <h5 class="h4 font-weight-bold mt-3 mb-2 text-white justify-content-center d-flex align-items-center">
                            <img class="img-fluid mr-2" src="{{ asset('/storage/img/icons/' . $sport->slug .'.png') }}" width="18" height="18">
                            {{ $sport->nom }}
                        </h5>
                    </a>
                    <ul class="list-unstyled">
                        <?php 
                            $competitionsNavbar = index('competition_sport')
                                ->where('sport_id', $sport->id)
                                ->sortBy('position');

                        ?>
                        @foreach ($competitionsNavbar->slice(0, 5) as $competition)
                            <li>
                                <a class="text-light"
                                    href="{{ route('competition.index', ['sport' => $sport->slug, 'competition' => $competitions[$competition->competition_id]->slug]) }}">{{ $competitions[$competition->competition_id]->nom }}</a>
                            </li>
                        @endforeach
                        @if(count($competitionsNavbar) > 5)
                            <li>
                                <a class="btn btn-link text-center" type="button" data-toggle="modal" data-target="#navbarModal" data-sport="{{ $sport->slug }}">Voir+</a>
                            </li>
                        @endif
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
            <a target="_blank" href="https://www.facebook.com/mayottesport" class="mx-3"><img src="{{ asset('storage/img/facebook.png') }}" alt="MayotteSport sur Facebook" title="MayotteSport sur Facebook" class="img-fluid bg-dark"></a>
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

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-S0HRBTP8PC"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-S0HRBTP8PC');
</script>

<script data-ad-client="ca-pub-6802785230681286" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

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

        // Si le bouton d'activation du modal comporte un data-sport, alors on affiche (ouvre) le menu du sport associé
        $('#navbarModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var sport = button.data('sport') // Extract info from data-* attributes

            let menu = $('.dropdown.'+sport+' .dropdown-menu')
            if(menu){
                menu.dropdown('show')
            }
        })
    })
</script>
@yield('script')
