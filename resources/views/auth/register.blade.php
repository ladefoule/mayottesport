@extends('layouts.site')

@section('title', "S'inscrire")

@section('content')
<div class="col-md-10 col-lg-9 col-xl-8 m-auto p-3">
    {{-- <div class="container border border-danger w-100">
        <div class="row justify-content-center p-3"> --}}
        <div class="card">
            <div class="card-header position-relative text-green h5">S'inscrire</div>

            <div class="text-danger text-right pr-3 pt-2">* champs obligatoires</div>

            <div class="card-body pb-0">
                <form method="POST" action="{{ route('register') }}" id="formRegister">
                    @csrf

                    <div class="form-group row mb-3">
                        <label for="name" class="col-md-3 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Nom</label>

                        <div class="col-md-7">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" pattern=".{3,50}" data-msg="Le champ <span class='text-danger font-italic'>Nom</span> doit contenir entre 3 et 50 caractères." name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="first_name" class="col-md-3 col-form-label text-md-right">Prénom</label>

                        <div class="col-md-7">
                            <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror input-optionnel" pattern="\0|.{3,50}" data-msg="Le champ <span class='text-danger font-italic'>Prénom</span> peut être soit nul soit comporter entre 3 et 50 caractères." name="first_name" value="{{ old('first_name') }}" autocomplete="first_name" autofocus>

                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="email" class="col-md-3 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Email</label>

                        <div class="col-md-7">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" data-msg="Le champ <span class='text-danger font-italic'>Email</span> doit être un email valide." name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="region_id" class="col-md-3 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Région</label>

                        <div class="col-md-7">
                            <select data-msg="Le champ <span class='text-danger font-italic'>Lieu</span> doit être égal à l'une des options proposées." name="region_id" class="form-control @error('region_id') is-invalid @enderror">
                                <option value=""></option>
                                @foreach (index('regions') as $region)
                                    <option @if (old('region_id') == $region->id) selected @endif value="{{ $region->id }}">{{ $region->nom }}</option>
                                @endforeach
                            </select>
                            @error('region_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="password" class="col-md-3 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Mot de passe</label>

                        <div class="col-md-7">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" pattern=".{8,50}" data-msg="Le champ <span class='text-danger font-italic'>Mot de passe</span> doit comporter entre 8 et 50 caractères." name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-3 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Confirmation</label>

                        <div class="col-md-7">
                            <input id="password-confirm" type="password" class="form-control" pattern=".{8,50}" data-msg="Le champ <span class='text-danger font-italic'>Confirmation</span> doit être identique au mot de passe." name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="form-group row pb-0 pt-2">
                        <div class="offset-md-3 col-md-7">
                            {!! captcha_img() !!}
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

                    <div class="form-group row mb-0 px-3">
                        <div class="col-md-7 offset-md-3 alert alert-danger text-dark d-none" id="messageErreur"></div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-7 offset-md-3">
                            {{-- <div id="submit" class="btn btn-primary">S'inscrire</div> --}}
                            <button class="btn btn-success px-5">S'inscrire</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    {{-- </div> --}}
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        verifierMonFormulaireEnJS('formRegister')
    });
</script>
@endsection
