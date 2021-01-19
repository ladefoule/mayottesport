@if($cookieConsentConfig['enabled'] && ! $alreadyConsentedWithCookies)

    <!-- Modal de personnalisation des cookies -->
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
                    <button type="button" class="js-cookie-consent-agree cookie-consent__agree btn btn-primary" data-dismiss="modal">Valider</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Fin Modal --}}

    {{-- Cookies consent message --}}
    @include('cookieConsent::dialogContents')
    {{-- Fin Cookies consent message --}}

    <script>
        // $('#parametrerCookies').on('click', function (){
        //     qs('#parametresCookies').classList.toggle('d-none')
        // })

        // $('#cookiesParametres').modal('show');

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
