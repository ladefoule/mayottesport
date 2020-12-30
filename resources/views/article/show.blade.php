@extends('layouts.site')

@section('title', "Rédaction d'un article")

@section('content')
<div class="row pt-3">
    <h1 class="col-12 h3 text-center">{{ $article->titre }}</h1>
    {{-- <div class="col-12">
        <img src="/storage/img/{{ $article->img }}" alt="" class="img-fluid">
    </div> --}}

    <!-- Create the editor container -->
    {{-- <label for="about">About me</label> --}}
    <div id="scrolling-container">
        <div id="editor" class="col-12">

        </div>
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
        //     modules: {
        //         toolbar: toolbarOptions
        //     },
        // });

        // var quill = new Quill('#editor', {
        //     modules: {
        //         toolbar: [
        //         ['bold', 'italic'],
        //         ['link', 'blockquote', 'code-block', 'image'],
        //         [{ list: 'ordered' }, { list: 'bullet' }],
        //         ['clean']
        //         ]
        //     },
        //     placeholder: 'Rédiger votre article...',
        //     theme: 'snow',
        // });

        var quill = new Quill('#editor', {
            theme: 'snow',
            scrollingContainer: '#scrolling-container',
            readOnly: true
        });

        let token = qs('input[name=_token]').value

        $.ajax({
            type: 'POST',
            url: "<?php echo route('article.ajax', ['uniqid' => $article->uniqid]) ?>",
            data: {_token:token},
            success:function(data){
                quill.setContents(
                    JSON.parse(data)
                )
            }
        })
    })
</script>
@endsection
