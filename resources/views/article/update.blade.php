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
                <input name="img" type="text" value="{{ old('img') ?? $article->img }}" class="form-control input-optionnel">
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
                        <option value="{{ $sport->id }}" @if($sport->id == $article->sport_id) selected @endif>{{ $sport->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-check form-check-inline ml-3">
                <input type="checkbox" name="valide" class="form-check-input" @if(old('texte') ?? $article->valide) checked @endif>
                <label class="form-check-label">Validé</label>
            </div>

            <input type="hidden" name="uniqid" value="{{ $article->uniqid }}">

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
        verifierMonFormulaireEnJS('formulaire')

        tinymce.init({
            menubar: true,
            selector: '#preambule,#texte',
            width:'100%',
            font_formats:"Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black,avant garde; Book Antiqua=book antiqua,palatino; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Symbol=symbol; Tahoma=tahoma,arial,helvetica,sans-serif; Terminal=terminal,monaco; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva; Webdings=webdings; Wingdings=wingdings,zapf dingbats",
            plugins: 'quickbars,link,advlist,autoresize',
            advlist_bullet_styles: 'square',
            advlist_number_styles: 'lower-alpha,lower-roman,upper-alpha,upper-roman',
        });
    })
</script>
@endsection
