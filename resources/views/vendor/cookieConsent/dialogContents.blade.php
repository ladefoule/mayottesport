<div class="js-cookie-consent cookie-consent m-0 row text-body text-right bg-white border">
    <div class="p-3">
        <span class="cookie-consent__message text-left">
            Ce site nécessite l'utilisation de cookies pour fonctionner correctement. 
            Les cookies nous permettent entre autres de garder votre session active tout au long de votre navigation sur le site, de sécuriser notre site ou de récolter des statistiques sur nos visiteurs et/ou membres.
            En poursuivant votre navigation vous acceptez que MayotteSport.com et ses partenaires utilisent des cookies ou traceurs pour stocker et/ou accéder à des informations sur votre terminal et traitent des données personnelles comme votre adresse IP ou les pages que vous visitez.
        </span>

        <div class="pt-3 text-left">
            <button id="cookiesParametresBouton" type="button" class="btn-lg px-3 btn-secondary" data-toggle="modal" data-target="#cookiesParametres">
                Paramétrer
            </button>
                    
            <button class="js-cookie-consent-agree cookie-consent__agree btn-lg bg-success border-success text-white float-right px-5">
                Accepter
            </button>
        </div>
    </div>
</div>

<style>
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 34px;
        height: 14px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 10px;
        width: 12px;
        left: 4px;
        bottom: 2.5px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: green;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(13px);
        -ms-transform: translateX(13px);
        transform: translateX(13px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 17px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

</style>
