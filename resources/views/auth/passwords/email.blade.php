@extends('layouts.site')

@section('title', "Réinitialisation du mot de passe")

@section('content')
{{-- <div class="row justify-content-center"> --}}
    <div class="col-md-10 col-lg-9 col-xl-8 m-auto p-3">
        <div class="card">
            <div class="card-header">Réinitialisation du mot de passe</div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group row mb-3">
                        <label for="email" class="col-md-3 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Adresse email</label>

                        <div class="col-md-7">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row pb-0 pt-2">
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

                    <div class="form-group row mb-3">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Envoyer le lien de réinitialisation
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
{{-- </div> --}}
@endsection
