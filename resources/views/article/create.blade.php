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
                    <input name="titre" type="text" value="{{ $titre ?? '' }}" class="form-control">
                </div>
            </div>

            <div class="col-12 form-row justify-content-center mb-4">
                <div class="col-md-6 d-flex flex-wrap">
                    <label>Image</label>
                    <input name="img" type="text" value="{{ $img ?? '' }}" class="form-control">
                </div>
            </div>

            <!-- Create the editor container -->
            {{-- <label for="about">About me</label> --}}
            <input name="texte" type="hidden">
            <input name="about" type="hidden">
            <div id="editor-container" class="w-100 border">

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
            ['blockquote', 'code-block'],

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

        // var quill = new Quill('#editor', {
        //     theme: 'snow',
        //      placeholder: 'Rédiger votre article...',
        //     modules: {
        //         toolbar: toolbarOptions
        //     },
        // });

        var quill = new Quill('#editor-container', {
        modules: {
            toolbar: [
            ['bold', 'italic'],
            ['link', 'blockquote', 'code-block', 'image'],
            [{ list: 'ordered' }, { list: 'bullet' }]
            ]
        },
        placeholder: 'Rédiger votre article...',
        theme: 'snow'
        });

        var form = document.querySelector('#formulaire');
        form.onsubmit = function(e) {
            // e.preventDefault()
        // Populate hidden form on submit
        var about = document.querySelector('input[name=texte]');
        about.value = JSON.stringify(quill.getContents());

        console.log("Submitted", $(form).serialize(), $(form).serializeArray());

        // No back end to actually submit to!
        alert('Open the console to see the submit data!')
        return true;
        };

        // var form = document.querySelector('#formulaire');
        // form.addEventListener('submit', function(e) {
        //     // e.preventDefault()
        //     // Populate hidden form on submit
        //     var texte = document.querySelector('input[name=texte]');
        //     texte.value = JSON.stringify(quill.getContents());

        //     console.log("Submitted", $(form).serialize(), $(form).serializeArray());

        //     // No back end to actually submit to!
        //     // alert('Open the console to see the submit data!')
        //     // return false;
        // });
    })
</script>
@endsection
