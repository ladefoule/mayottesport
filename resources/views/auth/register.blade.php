@extends('layouts.site')

@section('title', "S'inscrire")

@section('content')
<div class="row justify-content-center mx-0">
    <div class="col-lg-10 px-0">
        <div class="card">
            <div class="card-header position-relative">S'inscrire</div>

            <div class="text-danger text-right pr-3 pt-2">* champs obligatoires</div>

            <div class="card-body pb-0">
                <form method="POST" action="{{ route('register') }}" id="formRegister">
                    @csrf

                    <div class="form-group row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Nom</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" pattern=".{3,50}" data-msg="Le champ <span class='text-danger font-italic'>Nom</span> doit contenir entre 3 et 50 caractères." name="name"  value="ALI MOUSSA" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="first_name" class="col-md-4 col-form-label text-md-right">Prénom</label>

                        <div class="col-md-6">
                            <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror input-optionnel" pattern="\0|.{3,50}" data-msg="Le champ <span class='text-danger font-italic'>Prénom</span> peut être soit nul soit comporter entre 3 et 50 caractères." name="first_name" value="Moussa" value="{{ old('first_name') }}" autocomplete="first_name" autofocus>

                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="email" class="col-md-4 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Email</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" data-msg="Le champ <span class='text-danger font-italic'>Email</span> doit être un email valide." name="email" value="magik.systematik@hotmail.com" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    @php
                        $regions = App\Region::all();

                        // On récupère l'ID de la catégorie 'Super-Administrateur'
                        $idSuperAdmin = App\Role::firstWhere('nom', 'LIKE', "superadmin")->id;
                    @endphp

                    <div class="form-group row mb-3">
                        <label for="region_id" class="col-md-4 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Région</label>

                        <div class="col-md-6">
                            <select data-msg="Le champ <span class='text-danger font-italic'>Lieu</span> doit être égal à l'une des options proposées." name="region_id" class="form-control @error('region_id') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($regions as $region)
                                    <option @if (/* old('region_id') == $region->region_id */ $region->id == 1) selected @endif value="{{ $region->id }}">{{ $region->nom }}</option>
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
                        <label for="password" class="col-md-4 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Mot de passe</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" value="01010101" pattern=".{8,50}" data-msg="Le champ <span class='text-danger font-italic'>Mot de passe</span> doit comporter entre 8 et 50 caractères." name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Confirmation</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" pattern=".{8,50}" data-msg="Le champ <span class='text-danger font-italic'>Confirmation</span> doit être identique au mot de passe." name="password_confirmation" value="01010101" required autocomplete="new-password">
                        </div>
                    </div>

                    {{-- Par défaut (en dev) les utilisateurs seront des superadmin --}}
                    <input type="hidden" name="role_id" value="{{ $idSuperAdmin }}">

                    <div class="form-group row mb-0 px-3">
                        <div class="col-md-6 offset-md-4 alert alert-danger text-dark d-none" id="messageErreur"></div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4 d-flex justify-content-center">
                            {{-- <div id="submit" class="btn btn-primary">S'inscrire</div> --}}
                            <button class="btn btn-primary px-5">S'inscrire</button>
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
    $(document).ready(function(){
        verifierMonFormulaireEnJS('formRegister')
    });
</script>
@endsection
