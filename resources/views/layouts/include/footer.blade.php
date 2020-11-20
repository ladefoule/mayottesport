<!-- Footer -->
<footer class="page-footer font-small indigo text-white bg-dark">
    <!-- Footer Links -->
    @if (request()->sports)
    <div class="container text-center text-md-left">
        <div class="row d-flex {{-- flex-nowrap --}} flex-basis-1 text-center">
            @foreach (request()->sports as $sport)
            <div class="col-4 col-md-2 mx-auto px-2">
                <a href="{{ route('sport.index', ['sport' => strToUrl($sport->nom)]) }}"><h5 class="font-weight-bold mt-3 mb-2 text-white">{{ $sport->nom }}</h5></a>
                <ul class="list-unstyled">
                    @foreach ($sport->competitions as $i => $competition)
                    <li>
                        <a href="{{ route('competition.index', ['sport' => strToUrl($sport->nom), 'competition' => strToUrl($competition->nom)]) }}">{{ $competition->nom }}</a>
                    </li>
                    <?php if($i == 4) break; ?>
                @endforeach
                </ul>
            </div>
            @endforeach
            <div class="col-4 col-md-2 mx-auto px-2">
                <h5 class="font-weight-bold mt-3 mb-2">MS.com</h5>
                <ul class="list-unstyled">
                    <li>
                    <a href="#!">S'inscrire</a>
                    </li>
                    <li>
                    <a href="#!">Se connecter</a>
                    </li>
                    <li>
                    <a href="#!">Contact</a>
                    </li>
                    <li>
                    <a href="#!">Cookies</a>
                    </li>
                </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Footer Links -->
    <!-- Copyright -->
    <div class="footer-copyright text-center py-3 bg-body">© {{ date('Y') }} Copyright:
        <a href="{{ config('app.url') }}"> mayottesport.com</a> - FB - TW
    </div>
    <!-- Copyright -->
</footer>
<!-- Footer -->

<script src="https://kit.fontawesome.com/fa79ab8443.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="/js/datatables.min.js"></script>
<script src="/js/select2.min.js"></script>
<script src="/js/outils.js"></script>
<script>
$(document).ready(function(){
    var navbarMobile = qs('.navbar-mobile')
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

    $('footer,section').on('click', function(e){
        if(e.target != navbarMobile && navbarMobile.dataset.state == 'visible'){
            navbarMobile.dataset.state = 'hidden'
            navbarMobile.style.left = '-250px'
        }
    })
})
</script>
@yield('script')
