@extends('layouts.site')

@section('title', "Confirmer le mot de passe")

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 p-3">
        <div class="card">
            <div class="card-header">Confirmer le mot de passe</div>

            <div class="card-body">
                Veuillez confirmer votre mot de passe avant de continuer.

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="form-group row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Mot de passe</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Confirmer le mot de passe
                            </button>

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
