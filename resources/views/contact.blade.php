{{-- @extends('layouts.app') --}}

@extends('layouts.site')

@section('title', 'Contact')

@section('content')
{{-- <div class="row justify-content-center"> --}}
    <div class="col-md-10 col-lg-9 col-xl-8 m-auto p-3">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        
        <div class="card">
            <div class="card-header h5 text-green">Contact</div>

            <div class="text-danger text-right pr-3 pt-2">* champs obligatoires</div>

            <div class="card-body">
                <form method="POST" action="" id="formulaire">
                    @csrf

                    <div class="form-group row pb-2">
                        <label for="nom" class="col-md-3 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Nom</label>

                        <div class="col-md-7">
                            <input id="nom" type="text" pattern=".{3,30}" class="form-control @error('nom') is-invalid @enderror" name="nom"
                                value="{{ old('nom') }}" style="font-size: 1.0rem" required autocomplete="nom" autofocus
                                data-msg="Le champ <span class='text-danger font-italic'>Nom</span> n'est pas valide.">

                            @error('nom')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="form-group row pb-2">
                        <label for="prenom" class="col-md-3 col-form-label text-md-right">Prénom</label>

                        <div class="col-md-7">
                            <input id="prenom" type="text" pattern="\0|.{3,30}" class="input-optionnel form-control @error('prenom') is-invalid @enderror" name="prenom"
                                value="{{ old('prenom') }}" style="font-size: 1.0rem" autocomplete="Prénom" autofocus
                                data-msg="Le champ <span class='text-danger font-italic'>prenom</span> n'est pas valide.">

                            @error('prenom')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div> --}}

                    <div class="form-group row pb-2">
                        <label for="email" class="col-md-3 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Email</label>

                        <div class="col-md-7">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" style="font-size: 1.0rem" required autocomplete="email" autofocus
                                data-msg="Le champ <span class='text-danger font-italic'>Email</span> n'est pas valide.">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row pb-2 mb-0">
                        <label for="message" class="col-md-3 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Message</label>

                        <div class="col-md-7">
                            <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" rows="5" style="font-size: 1.0rem">{{ old('message') }}</textarea>

                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row pb-0 pt-3">
                        <div class="offset-md-3 col-md-7">
                            {!! captcha_img('flat') !!}
                        </div>
                    </div>

                    <div class="form-group row pb-0 pt-2">
                        <label for="captcha" class="col-md-3 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Captcha</label>
                        <div class="col-md-7">
                            <input class="form-control @error('captcha') is-invalid @enderror" data-msg="Merci de saisir le <span class='text-danger font-italic'>Captcha</span> correspondant à l'image." type="text" name="captcha">

                            @error('captcha')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="offset-md-3 col-md-7 alert alert-danger text-dark px-3 d-none" id="messageErreur"></div>

                    <div class="form-group row pb-0 mb-0">
                        <div class="col-md-8 offset-md-3">
                            <button class="btn btn-success">Envoyer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
{{-- </div> --}}
@endsection

@section('script')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    verifierMonFormulaireEnJS('formulaire')
})
</script>
@endsection
