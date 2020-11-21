{{-- @extends('layouts.app') --}}

@extends('layouts.site')

@section('title', 'Connexion')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Connexion</div>

            <div class="card-body">
                <form method="POST" action="{{ route('login') }}" id="formulaire">
                    @csrf

                    <div class="form-group row pb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                value="magik.systematik@hotmail.com" value="{{ old('email') }}" required autocomplete="email" autofocus
                                data-msg="Le champ <span class='text-danger font-italic'>Email</span> doit être un email valide.">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row pb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Mot de passe</label>

                        <div class="col-md-6">
                            <input
                                id="password" pattern=".{8,100}" type="password" class="form-control @error('password') is-invalid @enderror"
                                value="01010101" name="password" required autocomplete="current-password"
                                data-msg="Le champ <span class='text-danger font-italic'>Mot de passe</span> doit comporter au moins 8 caractères.">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row pb-3">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    Se souvenir de moi
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-12 alert alert-danger text-dark px-3 d-none" id="messageErreur"></div>
                    </div>

                    <div class="form-group row pb-3 mb-0">
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
