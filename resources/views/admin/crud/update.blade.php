@extends('layouts.' . request()->layout)

@section('title', $title)

@section('content')

<div class="row card mx-1">
    <div class="card-header d-flex align-items-center">
        <span class="d-inline mr-3" style="font-size: 1.6em"><i class="fas fa-database"></i> {{ $h1 }}</span>
        <a href="{{ $hrefs['index'] }}" title="Liste" class="text-decoration-none ml-2">
            <button class="btn-sm btn-warning text-body">
                {!! \Config::get('constant.boutons.lister') !!}
                <span class="d-none d-lg-inline ml-1">Liste</span>
            </button>
        </a>
        <a href="{{ $hrefs['show'] }}" title="Voir" class="text-decoration-none ml-2">
            <button class="btn-sm btn-success text-white">
                {!! \Config::get('constant.boutons.voir') !!}
                <span class="d-none d-lg-inline ml-1">Voir</span>
            </button>
        </a>
        <a href="{{ $hrefs['delete'] }}" title="Supprimer" class="text-decoration-none ml-2">
            <button class="btn-sm btn-danger">
                {!! \Config::get('constant.boutons.supprimer') !!}
                <span class="d-none d-lg-inline ml-1">Supprimer</span>
            </button>
        </a>
        <a href="" class="back d-none d-sm-inline position-absolute text-decoration-none text-dark pr-3" style="right:0"><i class="fas fa-long-arrow-alt-left"></i> retour</a>
    </div>

    <div class="text-danger text-right pr-3 pt-2">* champs obligatoires</div>

    <div class="card-body">
        <form action="" method="POST" class="needs-validation" id="formulaire">
            @csrf
            @foreach($donnees as $attribut => $infos)
                <?php
                    $inputType = $infos['input_type'];
                    $optionnel = $infos['optionnel'];
                    $labelAttribut = $infos['label'];
                    $valeur = $infos['valeur'];
                    $dataMsg = $infos['data_msg'];
                    $pattern = $infos['pattern'];
                    $min = $infos['min'];
                    $max = $infos['max'];
                    $className = $infos['class'];
                    $selectListe = $infos['select_liste'];
                ?>
                <div class="form-row mb-3">
                    {{-- Si l'attribut est de type checkbox --}}
                    @if ($inputType == 'checkbox')
                        <div class="form-check form-check-inline">
                            <input
                                type="checkbox" name="{{ $attribut }}" class="{{ $className }} @error($attribut) is-invalid @enderror"
                                    @if (old($attribut) OR $valeur) checked @endif>
                            <label class="form-check-label">{{ $labelAttribut }}</label>
                        </div>

                    {{-- Si l'attribut n'est pas de type checkbox --}}
                    @else
                        <label for="{{$attribut}}">{{$labelAttribut}}@if (! $optionnel) <span class="text-danger text-weight-bold">*</span> @endif</label>

                        {{-- Si l'attribut est une référence à une autre table --}}
                        @if (isset($infos['select']))
                            <select name="{{ $attribut }}" id="{{ $attribut }}" class="{{ $className }} @error($attribut) is-invalid @enderror" <?= $dataMsg ?>>
                                <option value="">&nbsp;</option>
                                @foreach ($infos['select'] as $id => $instanceFK)
                                    <option value="{{ $id }}"
                                        @if ($infos['valeur'] == $id || old($attribut) == $id) selected @endif>
                                        {{ $instanceFK->crud_name }}
                                    </option>
                                @endforeach
                            </select>

                        {{-- Si l'attribut est de type select --}}
                        @elseif ($inputType == 'select' && $selectListe)
                            <select name="{{ $attribut }}" id="{{ $attribut }}" class="{{ $className }} @error($attribut) is-invalid @enderror" <?= $dataMsg ?>>
                                <option value="">Sélectionner</option>
                                @foreach ($selectListe as $id => $value)
                                    <option value="{{ $id }}"
                                        @if (old($attribut) == $id || $valeur == $id) selected @endif>
                                        {{ $value[1] }}
                                    </option>
                                @endforeach
                            </select>

                        {{-- Si l'attribut est de type textarea --}}
                        @elseif ($inputType == 'textarea')
                            <?php
                                $contenuTextarea = old($attribut) ?? $valeur;
                            ?>
                            <textarea name="{{ $attribut }}" class="{{ $className }} @error($attribut) is-invalid @enderror" rows="3">{{ $contenuTextarea }}</textarea>

                        {{-- Pour tous les autres cas on affiche un input text --}}
                        @else
                            <input
                                type="{{ $inputType }}"
                                name="{{ $attribut }}"
                                class="{{ $className }} @error($attribut) is-invalid @enderror"
                                value="{{ old($attribut) ?? $valeur }}"
                                <?= $min ?> <?= $max ?> <?= $pattern ?> <?= $dataMsg ?>
                            >
                        @endif
                    @endif
                    @error($attribut)
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            @endforeach

            <div class="form-row mt-3">
                <div class="col-12 alert alert-danger text-dark px-3 d-none" id="messageErreur"></div>
            </div>

            <div class="form-row justify-content-center">
                <button class="btn btn-primary px-5">Valider</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#formulaire select').select2();
    retour()
    verifierMonFormulaireEnJS('formulaire')
})
</script>
@endsection
