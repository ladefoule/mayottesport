@extends('layouts.site')

@section('title', "Rédaction d'un article")

@section('content')
<div class="row pt-3">
    <h1 class="col-12 h3 text-center">Rédaction d'un nouvel article</h1>

    <div class="card-body">
        <form action="" method="POST" class="needs-validation w-100 d-flex flex-wrap" id="formulaire">
            @csrf

            <div class="col-12 form-row justify-content-center mb-3">
                <div class="col-md-6 d-flex flex-wrap">
                    <label>Titre</label>
                    <input name="titre" type="text" value="{{ old('titre') }}" pattern=".{3,50}" data-msg="Le titre doit contenir au moins 3 caractères" class="form-control">
                </div>
            </div>

            <div class="col-12 form-row justify-content-center mb-4">
                <div class="col-md-6 d-flex flex-wrap">
                    <label>Image</label>
                    <input name="img" type="text" value="{{ old('img') }}" class="form-control input-optionnel">
                </div>
            </div>

            <!-- Create the editor container -->
            {{-- <label for="about">About me</label> --}}
            <input name="texte" type="hidden">
            <div id="editor-container" class="col-12 border">

            </div>

            <div class="col-12 form-row mt-3">
                <div class="mt-3 col-12 alert alert-danger text-dark px-3 d-none" id="messageErreur"></div>
            </div>

            <div class="col-12 form-row justify-content-center">
                <button class="btn btn-primary px-5">Valider</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<!-- Initialize Quill editor -->
{{-- <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet"> --}}
{{-- <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script> --}}
<script>
    $(document).ready(function(){
        var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
            ['link', 'blockquote', 'code-block', /* 'image' */],


            [{ 'header': 1 }, { 'header': 2 }],               // custom button values
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
            [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
            [{ 'direction': 'rtl' }],                         // text direction

            [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

            [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
            [{ 'font': [] }],
            [{ 'align': [] }],

            ['clean']                                         // remove formatting button
        ];

        var quill = new Quill('#editor-container', {
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Rédiger votre article...',
            theme: 'snow'
        });

        var form = document.querySelector('#formulaire');
        var titre = document.querySelector('input[name=titre]');
        titre.addEventListener('change', e => checkOne(e.target))
        var img = document.querySelector('input[name=img]');
        img.addEventListener('change', e => checkOne(e.target))
        var divErreurs = qs('#messageErreur')
        form.onsubmit = function(e) {
            e.preventDefault()

            // On transfère le contenu de l'article (en json) dans l'input hidden texte
            var texte = document.querySelector('input[name=texte]');
            texte.value = JSON.stringify(quill.getContents());

            if(!checkOne(titre) || !checkOne(img))
                return false

            if (texte.value.length < 56){
                divErreurs.innerHTML = "L'article doit contenir au moins 30 caractères."
                divErreurs.classList.remove('d-none')
                texte.classList.add('is-invalid')
                texte.classList.remove('is-valid')
                texte.focus()
                return false;
            }

            form.submit()
            // console.log("Submitted", $(form).serialize(), $(form).serializeArray());

            // No back end to actually submit to!
            // alert('Open the console to see the submit data!')
            // return true;
        };
    })
</script>
@endsection
