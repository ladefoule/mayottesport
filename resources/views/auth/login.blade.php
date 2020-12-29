{{-- @extends('layouts.app') --}}

@extends('layouts.site')

@section('title', 'Connexion')

@section('content')
<div class="row justify-content-center p-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Connexion</div>

            <div class="text-danger text-right pr-3 pt-2">* champs obligatoires</div>

            <div class="card-body">
                <form method="POST" action="{{ route('login') }}" id="formulaire">
                    @csrf

                    <div class="form-group row pb-2">
                        <label for="email" class="col-md-4 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Email</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                value="superadmin@superadmin.fr" value="{{ old('email') }}" required autocomplete="email" autofocus
                                data-msg="Le champ <span class='text-danger font-italic'>Email</span> n'est pas valide.">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Mot de passe</label>

                        <div class="col-md-6">
                            <input
                                id="password" pattern=".{8,100}" type="password" class="form-control @error('password') is-invalid @enderror"
                                value="00000000" name="password" required autocomplete="current-password"
                                data-msg="Le champ <span class='text-danger font-italic'>Mot de passe</span> doit comporter entre 8 et 50 caractères.">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    Se souvenir de moi
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row px-3">
                        <div class="offset-md-4 col-md-6 mt-3 alert alert-danger text-dark px-3 d-none" id="messageErreur"></div>
                    </div>

                    <div class="form-group row pb-2 mb-0">
                        <div class="col-md-8 offset-md-4">
                            {{-- <div id="submit" class="btn btn-primary">
                                Se connecter
                            </div> --}}
                            <button class="btn btn-primary">Se connecter</button>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Mot de passe oublié?
                                </a>
                            @endif
                        </div>
                        <div class="col-md-8 offset-md-4 mt-3">
                            <a class="btn btn-success" href="{{ route('register') }}">
                                Pas encore inscrit(e) ? Créez votre accès.
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    verifierMonFormulaireEnJS('formulaire')
})
</script>
@endsection
