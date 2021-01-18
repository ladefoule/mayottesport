<div class="js-cookie-consent cookie-consent px-3 mx-3 alert alert-danger text-right border">
    <span class="cookie-consent__message">
        Ce site nécessite l'autorisation de cookies pour fonctionner correctement.
        {{-- {!! trans('cookieConsent::texts.message') !!} --}}
    </span>

    <button class="js-cookie-consent-agree cookie-consent__agree btn btn-success bg-success">
        Accepter
        {{-- {{ trans('cookieConsent::texts.agree') }} --}}
    </button>

    <div class="pt-3">
        <a href="{{ route('politique') }}"><button class="btn btn-info text-white bg-primary">Notre politique de confidentialité</button></a>
    </div>
</div>
