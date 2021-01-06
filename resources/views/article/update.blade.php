@extends('layouts.site')

@section('title', "Modification d'un article")

@section('content')
<div class="row pt-3">
    <div class="col-lg-8 p-0">
        <h1 class="col-12 h3 text-center">Modification d'un article</h1>

        <form action="" method="POST" class="needs-validation col-12 d-flex flex-wrap p-0" id="formulaire">
            @csrf

            <div class="col-12 justify-content-center pb-3">
                <label>Titre</label>
                <input name="titre" type="text" value="{{ old('titre') ?? $article->titre }}" pattern=".{10,200}" data-msg="Le titre doit contenir au moins 10 caractères" class="form-control">
            </div>

            <div class="col-12 justify-content-center pb-3">
                <label>Image</label>
                <select name="img" id="images" class="form-control input-optionnel">
                    <option value="">Sans image</option>
                    @foreach ($images as $image)
                        <option value="{{ $image['value'] }}" @if(old('img') == $image['value'] || (! old('img') && $article->img == $image['value'])) selected @endif>{{ $image['title'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 pb-3">
                <label for="preambule">Préambule</label>
                <textarea id="preambule" name="preambule" class="form-control">{{ old('preambule') ?? $article->preambule }}</textarea>
            </div>

            <div class="col-12 pb-3">
                <label for="texte">Article</label>
                <textarea id="texte" name="texte" class="form-control">{{ old('texte') ?? $article->texte }}</textarea>
            </div>

            <div class="col-12 pb-3">
                <label for="texte">Sport</label>
                <select name="sport_id" class="form-control">
                    <option value="">Aucun</option>
                    @foreach ($sports as $sport)
                        <option value="{{ $sport->id }}" @if(old('sport_id') == $sport->id || (! old('sport_id') && $sport->id == $article->sport_id)) selected @endif>{{ $sport->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-check form-check-inline ml-3">
                <input type="checkbox" name="valide" class="form-check-input" @if(old('texte') ?? $article->valide) checked @endif>
                <label class="form-check-label">Validé</label>
            </div>

            <input type="hidden" name="uniqid" value="{{ $article->uniqid }}">
            <input type="hidden" name="user_id" value="{{ $article->user_id }}">

            <div class="col-12 mt-3">
                <div class="mt-3 col-12 alert alert-danger text-dark px-3 d-none" id="messageErreur"></div>
            </div>

            <div class="col-12 d-flex justify-content-center pb-3">
                <button class="btn btn-primary px-5">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('node_modules/tinymce/tinymce.js') }}"></script>
<script>
$(document).ready(function(){
    $('#images').select2();
    verifierMonFormulaireEnJS('formulaire')
    tinymceFunc('#preambule,#texte', "<?php echo route('images_list') ?>")
})
</script>
@endsection
