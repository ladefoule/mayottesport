@extends('layouts.site')

@section('title', "Rédaction d'un article")

@section('content')
<div class="row pt-3">
    <h1 class="col-12 h3 text-center">Rédaction d'un nouvel article</h1>

    <div class="card-body">
        <form action="" method="POST" class="needs-validation col-12 d-flex flex-wrap p-0" id="formulaire">
            @csrf

            <div class="col-12 justify-content-center pb-3">
                <label>Titre</label>
                <input name="titre" type="text" value="{{ old('titre') }}" pattern=".{10,200}" data-msg="Le titre doit contenir au moins 10 caractères" class="form-control">
            </div>

            <div class="col-12 justify-content-center pb-3">
                <label>Image</label>
                <input name="img" type="text" value="{{ old('img') }}" class="form-control input-optionnel">
            </div>

            <div class="col-12 pb-3">
                <label for="preambule">Préambule</label>
                <textarea id="preambule" name="preambule" class="form-control">{{ old('preambule') }}</textarea>
            </div>

            <div class="col-12 pb-3">
                <label for="texte">Article</label>
                <textarea id="texte" name="texte" class="form-control">{{ old('texte') }}</textarea>
            </div>

            <div class="col-12 mt-3">
                <div class="mt-3 col-12 alert alert-danger text-dark px-3 d-none" id="messageErreur"></div>
            </div>

            <div class="col-12 d-flex justify-content-center pb-3">
                <button class="btn btn-primary px-5">Validerr</button>
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
            menubar: false,
            selector: '#preambule,#texte',
            width:'100%',
            plugins: 'quickbars,link,advlist,autoresize',
            advlist_bullet_styles: 'square',
            advlist_number_styles: 'lower-alpha,lower-roman,upper-alpha,upper-roman'
        });
    })
</script>
@endsection
