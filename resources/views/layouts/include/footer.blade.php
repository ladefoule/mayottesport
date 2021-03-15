<?php
    $sports = index('sports')->sortBy('home_position');
    $competitions = index('competitions');
?>

<!-- Footer -->
<footer class="col-12 footer indigo text-white h-auto bg-dark mt-auto">
    <!-- Footer Links -->
    <div class="container text-center text-md-left p-0">
        <div class="row d-flex text-center">
            @foreach ($sports as $sport)
                <div class="col-6 col-md-4 col-lg-2 p-0">
                    <a class="h5 pt-3 pb-0 text-white justify-content-center d-flex align-items-center" href="{{ route('sport.index', ['sport' => $sport->slug]) }}">
                        <img class="img-fluid mr-2" alt="Ballon de {{ $sport->nom }}" src="{{ asset('/storage/img/icons/' . $sport->slug .'.png') }}" width="18" height="18">
                        {{ $sport->nom }}
                    </a>
                    <ul class="list-unstyled">
                        <?php 
                            $competitionsNavbar = index('competition_sport')
                                ->where('sport_id', $sport->id)
                                ->sortBy('position')
                                ->slice(0, 5);

                        ?>
                        @foreach ($competitionsNavbar as $competition)
                            <li>
                                <a class="text-white footer-link"
                                    href="{{ route('competition.index', ['sport' => $sport->slug, 'competition' => $competitions[$competition->competition_id]->slug_complet]) }}">{{ $competitions[$competition->competition_id]->nom }}</a>
                            </li>
                        @endforeach
                        @if(count($competitions->where('sport_id', $sport->id)) > count($competitionsNavbar))
                            <li class="py-1 pb-sm-0">
                                <a class="{{-- btn-sm btn-light --}} text-primary text-center" data-toggle="modal" data-target="#navbarModal" data-sport="{{ $sport->slug }}">Voir+</a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endforeach
            <div class="col-6 col-md-4 col-lg-2 px-3">
                <a class="h5 pt-3 pb-0 text-white justify-content-center d-flex align-items-center" href="{{ route('home') }}">
                    {!! config('listes.boutons.home') !!} Accueil
                </a>
                <ul class="list-unstyled">
                    <li>
                        <a class="footer-link text-white" href="{{ route('contact') }}">Contactez-nous</a>
                    </li>
                    <li>
                        <a class="footer-link text-white" href="{{ route('politique') }}">Notre politique des cookies</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Footer Links -->

    <!-- Social buttons -->
    <div class="row footer-social justify-content-center py-2 border-top border-secondary">
        <a target="_blank" href="https://www.facebook.com/mayottesport" class="mx-3"><img src="{{ asset('storage/img/icons/facebook.png') }}" alt="MayotteSport sur Facebook" title="MayotteSport sur Facebook" class="img-fluid bg-dark"></a>
        <a target="_blank" href="https://twitter.com/mayottesport" class="mx-3"><img src="{{ asset('storage/img/icons/twitter.png') }}" alt="MayotteSport sur Twitter" title="MayotteSport sur Twitter" class="img-fluid bg-dark"></a>
        <a target="_blank" href="{{ asset('rss.xml') }}" class="mx-3"><img src="{{ asset('storage/img/icons/flux-rss.png') }}" alt="S'abonner aux flux rss" title="S'abonner aux flux rss" class="img-fluid bg-dark"></a>
    </div>
    <!-- Social buttons -->

    <!-- Copyright -->
    <div class="row d-flex flex-wrap footer-copyright justify-content-center text-center py-3 bg-body">
        <p class="w-100 p-0 m-0">
            © {{ date('Y') }} Copyright:
            <a class="px-1 text-success" href="{{ asset('/') }}">mayottesport.com</a>
        </p>
        <p class="w-100 p-0 m-0 realisation">
            Réalisé par <a href="{{ route('contact') }}" class="text-success">Web Solutions</a>
        </p>
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->

<script src="{{ asset(mix('js/app.js')) }}"></script>
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
    })
</script>
@yield('script')

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-S0HRBTP8PC"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-S0HRBTP8PC');
</script>

{{-- Adsense --}}
<script data-ad-client="ca-pub-6802785230681286" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

{{-- Cookies consent : Modal --}}
@include('cookieConsent::index')
