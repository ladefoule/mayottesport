@extends('layouts.site')

@section('title', "Changement de mot de passe")

@section('content')
    <div class="col-md-10 col-lg-9 col-xl-8 m-auto p-3">
        <div class="card">
            <div class="card-header">Changement de mot de passe</div>

            <div class="card-body">
                <form method="POST" action="{{ route('profil.update-password.post') }}" id="formulaire">
                    @csrf
                    <div class="form-group row mb-3 pb-3">
                        <label for="current_password" class="col-md-4 col-form-label text-md-right">Mot de passe actuel</label>

                        <div class="col-md-6">
                            <input id="current_password" type="password" pattern=".{8,50}" data-msg="Le <span class='text-danger font-italic'>Mot de passe (actuel)</span> doit comporter entre 8 et 50 caractères." class="form-control @error('current_password') is-invalid @enderror" name="current_password" value="{{ $current_password ?? old('current_password') }}" required autocomplete="current_password" autofocus>

                            @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3 pt-3">
                        <label for="new-password" class="col-md-4 col-form-label text-md-right">Nouveau mot de passe</label>

                        <div class="col-md-6">
                            <input id="new-password" type="password" pattern=".{8,50}" data-msg="Le <span class='text-danger font-italic'>Mot de passe (nouveau)</span> doit comporter entre 8 et 50 caractères." class="form-control @error('new_password') is-invalid @enderror" name="new_password" required autocomplete="new-new_password">

                            @error('new_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="new-password-confirm" class="col-md-4 col-form-label text-md-right">Confirmer le mot de passe</label>

                        <div class="col-md-6">
                            <input id="new-password-confirm" type="password" pattern=".{8,50}" data-msg="Le <span class='text-danger font-italic'>Mot de passe (confirmation)</span> doit comporter entre 8 et 50 caractères." class="form-control" name="new_password_confirmation" required autocomplete="new-new_password">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-12 alert alert-danger text-dark px-3 d-none" id="messageErreur"></div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-success px-5">
                                Valider
                            </button>
                        </div>
                    </div>
                </form>
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