@extends('layouts.site')

@section('title', 'Mise à jour de mon profil')

@section('content')
<div class="col-md-10 col-lg-9 col-xl-8 m-auto p-3">
    <div class="card">
        <div class="card-header d-flex align-items-center px-2">
            <span class="d-inline mr-3 crud-titre">{!! \Config::get('listes.boutons.user') !!} Mise à jour de mon compte</span>
            <a href="{{ route('profil') }}" title="Voir" class="text-decoration-none ml-2">
                <button class="btn-sm btn-success text-white">
                    {!! \Config::get('listes.boutons.voir') !!}
                    <span class="d-none d-lg-inline ml-1">Profil</span>
                </button>
            </a>
        </div>

        <div class="text-danger text-right pr-3 pt-2">* champs obligatoires</div>

        <div class="card-body px-3">
            <form action="" method="POST" enctype="multipart/form-data" class="needs-validation" id="formulaire">
                @csrf

                <div class="form-group row mb-3">
                    <label for="name" class="col-md-3 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Nom</label>

                    <div class="col-md-7">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" pattern=".{3,50}" data-msg="Le <span class='text-danger font-italic'>Nom</span> doit contenir plus de 3 caractères." name="name"  value="{{ old('name') ?? $user->name }}" required autocomplete="name" autofocus>

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
                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror input-optionnel" pattern="\0|.{3,50}" data-msg="Le <span class='text-danger font-italic'>Prénom</span> doit comporter plus de 3 caractères." name="first_name" value="{{ old('first_name') ?? $user->first_name }}" autocomplete="first_name" autofocus>

                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label for="pseudo" class="col-md-3 col-form-label text-md-right"><span class="text-danger text-weight-bold">*</span> Pseudo</label>

                    <div class="col-md-7">
                        <input id="pseudo" type="text" class="form-control @error('pseudo') is-invalid @enderror" pattern=".{3,50}" data-msg="Le <span class='text-danger font-italic'>Pseudo</span> doit comporter au moins 3 caractères." name="pseudo" value="{{ old('pseudo') ?? $user->pseudo }}" autocomplete="pseudo" autofocus>

                        @error('pseudo')
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
                            @foreach ($regions as $region)
                                <option @if (old('region_id') == $region->id || $user->region_id == $region->id) selected @endif value="{{ $region->id }}">{{ $region->nom }}</option>
                            @endforeach
                        </select>
                        @error('region_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="nom" class="col-md-3 col-form-label text-md-right">Changer d'avatar</label>

                    <div class="col-md-7">
                        <input type="file" name="avatar" class="form-control input-optionnel @error('avatar') is-invalid @enderror">

                        @error('region_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-12 alert alert-danger text-dark px-3 d-none" id="messageErreur"></div>
                </div>

                <div class="form-group row">
                    <div class="col-md-7 offset-md-4 d-flex justify-content-center">
                        <button class="btn btn-primary px-5">Valider</button>
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
    $('#formulaire select').select2();
     verifierMonFormulaireEnJS('formulaire')
})
</script>
@endsection
