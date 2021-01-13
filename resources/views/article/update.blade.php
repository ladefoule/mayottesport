@extends('layouts.site')

@section('title', "Modification d'un article")

@section('content')
<div class="row pt-3 bg-white">
    <h1 class="col-12 h3 text-center">Modification d'un article</h1>

    <form action="" method="POST" class="needs-validation col-lg-8 d-flex flex-wrap p-0" id="formulaire">
        @csrf

        <div class="col-12 justify-content-center pb-3">
            <label>Titre <span class="text-danger text-weight-bold">*</span></label>
            <input name="titre" type="text" value="{{ old('titre') ?? $article->titre }}" pattern=".{10,200}" data-msg="Le titre doit contenir au moins 10 caractères" class="form-control @error('titre') is-invalid @enderror">
        </div>

        <div class="col-12 justify-content-center pb-3">
            <label>Image</label>
            <select name="img" id="images" class="form-control input-optionnel @error('img') is-invalid @enderror">
                <option value="">Sans image</option>
                @foreach ($images as $image)
                    <option value="{{ $image['value'] }}" @if(old('img') == $image['value'] || (! old('img') && $article->img == $image['value'])) selected @endif>{{ $image['title'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 pb-3">
            <label for="preambule">Préambule <span class="text-danger text-weight-bold">*</span></label>
            <textarea id="preambule" name="preambule" class="form-control @error('preambule') is-invalid @enderror">{{ old('preambule') ?? $article->preambule }}</textarea>
        </div>

        <div class="col-12 pb-3">
            <label for="article">Article</label>
            <textarea id="article" name="article" class="form-control @error('article') is-invalid @enderror">{{ old('article') ?? $article->article }}</textarea>
        </div>

        <div class="col-12 pb-3">
            <label for="article">Catégorie</label>
            <select name="sport_id" class="form-control input-optionnel @error('sport_id') is-invalid @enderror">
                <option value="">Général</option>
                @foreach ($sports as $sport)
                    <option value="{{ $sport->id }}" @if(old('sport_id') == $sport->id || (! old('sport_id') && $sport->id == $article->sport_id)) selected @endif>{{ $sport->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12 mt-3">
            <div class="mt-3 col-12 alert alert-danger text-dark px-3 d-none" id="messageErreur"></div>
        </div>

        <div class="col-12 d-flex justify-content-center pb-3">
            <button class="btn btn-primary px-5">Mettre à jour</button>
        </div>
    </form>
    <div class="col-lg-4 text-center p-3">
        <a class="mb-3 btn btn-primary w-50" href="{{ route('article.create') }}">Nouvel article</a>
        <a class="mb-3 btn btn-danger w-50" href="{{ asset('/adminsharp') }}">Administration</a>
   </div>
</div>
@endsection

@section('script')
<script src="{{ asset('node_modules/tinymce/tinymce.js') }}"></script>
<script>
$(document).ready(function(){
    $('#images').select2();
    verifierMonFormulaireEnJS('formulaire')
    tinymceFunc('#preambule,#article', "<?php echo route('images_list') ?>")
})
</script>
@endsection
