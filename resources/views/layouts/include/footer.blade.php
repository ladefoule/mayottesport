<?php
    $sports = index('sports')->sortBy('home_position')->slice(0,5);
    $competitions = index('competitions');
?>
<!-- Footer -->
<footer class="col-12 footer font-small indigo text-white bg-dark mt-auto">
    <!-- Footer Links -->
    <div class="container text-center text-md-left">
        <div class="row d-flex {{-- flex-nowrap --}} flex-basis-1 text-center">
            @foreach ($sports as $sport)
            <div class="col-4 col-md-2 mx-auto px-2">
                <a href="{{ route('sport.index', ['sport' => \Str::slug($sport->nom)]) }}"><h5 class="font-weight-bold mt-3 mb-2 text-white">{{ $sport->nom }}</h5></a>
                <ul class="list-unstyled">
                    @foreach ($competitions->where('sport_id', $sport->id)->sortBy('index_position')->slice(0,5) as $competition)
                        <li>
                            <a class="text-primary" href="{{ route('competition.index', ['sport' => \Str::slug($sport->nom), 'competition' => \Str::slug($competition->nom)]) }}">{{ $competition->nom }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
            <div class="col-4 col-md-2 mx-auto px-2">
                <h5 class="font-weight-bold mt-3 mb-2">MS.com</h5>
                <ul class="list-unstyled">
                    <li>
                        <a class="text-primary" href="#!">S'inscrire</a>
                    </li>
                    <li>
                        <a class="text-primary" href="#!">Se connecter</a>
                    </li>
                    <li>
                        <a class="text-primary" href="#!">Contact</a>
                    </li>
                    <li>
                        <a class="text-primary" href="#!">Cookies</a>
                    </li>
                </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Links -->

    <!-- Copyright -->
    <div class="row footer-copyright justify-content-center text-center py-3 bg-body">© {{ date('Y') }} Copyright:
        <a href="{{ config('app.url') }}"> mayottesport.com</a> - FB - TW
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->

<script src="{{ config('app.url') }}{{ mix('js/app.js') }}"></script>
<script src="{{ config('app.url') }}{{ mix('js/all.js') }}"></script>
{{-- <script src="{{ config('app.url') }}/js/select2.min.js"></script> --}}
<script src="{{ config('app.url') }}/js/outils.js"></script>
<script>
$(document).ready(function(){
    var navbarMobile = qs('.navbar-mobile')

    // Affichage du menu Mobile
    $('.navbar-toggler').on('click', function(){
        let state = navbarMobile.dataset.state
        if(state == 'hidden'){
            navbarMobile.style.left = 0
            state = 'visible'
        }else if(state == 'visible'){
            navbarMobile.style.left = '-250px'
            state = 'hidden'
        }

        navbarMobile.dataset.state = state
    })

    // Masquage du menu Mobile
    $('footer,section').on('click', function(e){
        if(e.target != navbarMobile && navbarMobile.dataset.state == 'visible'){
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
})
</script>
@yield('script')
