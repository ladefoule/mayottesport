@if($cookieConsentConfig['enabled'] && ! $alreadyConsentedWithCookies)

    {{-- Cookies consent message --}}
    {{-- @include('cookieConsent::dialogContents') --}}
    {{-- Fin Cookies consent message --}}

    <!-- Modal de personnalisation des cookies -->
    <div class="modal fade" id="cookiesConsent" tabindex="-1" aria-labelledby="cookiesConsentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cookiesConsentLabel">Consentement à l'utilisation des cookies</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="px-1">
                        <span class="text-left">
                            Ce site nécessite l'utilisation de cookies pour fonctionner correctement. 
                            Les cookies nous permettent entre autres de garder votre session active tout au long de votre navigation sur le site, de sécuriser notre site ou de récolter des statistiques sur nos visiteurs et/ou membres.
                            En poursuivant votre navigation vous acceptez que MayotteSport.com et ses partenaires utilisent des cookies ou traceurs pour stocker et/ou accéder à des informations sur votre terminal et traitent des données personnelles comme votre adresse IP ou les pages que vous visitez.
                            {{-- Pour plus d'informations, vous pouvez consultez notre <a href="{{ route('politique') }}">politique de confidentialité</a> --}}
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="mr-auto" href="{{ route('politique') }}">
                        <button class="btn btn-link text-white bg-secondary">Notre politique de confidentialité</button>
                    </a>
                    <button type="button" class="js-cookie-consent-agree cookie-consent__agree btn-lg btn-success px-5" data-dismiss="modal">Valider</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Fin Modal --}}

    <script>
        // $('#parametrerCookies').on('click', function (){
        //     qs('#parametresCookies').classList.toggle('d-none')
        // })

        $('#cookiesConsent').modal('show');

        window.laravelCookieConsent = (function () {

            const COOKIE_VALUE = 1;
            const COOKIE_DOMAIN = '{{ config('session.domain') ?? request()->getHost() }}';

            function consentWithCookies() {
                setCookie('{{ $cookieConsentConfig['cookie_name'] }}', COOKIE_VALUE, {{ $cookieConsentConfig['cookie_lifetime'] }});
                hideCookieDialog();
            }

            function cookieExists(name) {
                return (document.cookie.split('; ').indexOf(name + '=' + COOKIE_VALUE) !== -1);
            }

            function hideCookieDialog() {
                const dialogs = document.getElementsByClassName('js-cookie-consent');

                for (let i = 0; i < dialogs.length; ++i) {
                    dialogs[i].style.display = 'none';
                }
            }

            function setCookie(name, value, expirationInDays) {
                const date = new Date();
                date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
                document.cookie = name + '=' + value
                    + ';expires=' + date.toUTCString()
                    + ';domain=' + COOKIE_DOMAIN
                    + ';path=/{{ config('session.secure') ? ';secure' : null }}'
                    + '{{ config('session.same_site') ? ';samesite='.config('session.same_site') : null }}';
            }

            if (cookieExists('{{ $cookieConsentConfig['cookie_name'] }}')) {
                hideCookieDialog();
            }

            const buttons = document.getElementsByClassName('js-cookie-consent-agree');

            for (let i = 0; i < buttons.length; ++i) {
                buttons[i].addEventListener('click', consentWithCookies);
            }

            return {
                consentWithCookies: consentWithCookies,
                hideCookieDialog: hideCookieDialog
            };
        })();
    </script>
@endif
