<!-- Modal de personnalisation des cookies -->
{{-- <div class="modal fade" id="cookiesParametres" tabindex="-1" aria-labelledby="cookiesParametresLabel" aria-hidden="true">
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
</div> --}}
{{-- Fin Modal --}}

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