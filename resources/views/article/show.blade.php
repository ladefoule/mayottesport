@extends('layouts.site')

@section('title', "Rédaction d'un article")

@section('content')
<div class="row">
    <div class="col-lg-8 p-0 d-flex flex-wrap justify-content-center">
        <h2 class="col-12 h2 pt-3 font-weight-bold">{{ $article->titre }}</h2>
        <div class="col-lg-8 d-flex justify-content-center">
            <img src="/storage/img/{{ $article->img }}" alt="" class="img-fluid">
        </div>

        <!-- Create the editor container -->
        <div id="editor" class="col-12 border-0" style="font-size:1.0rem">

        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        var quill = new Quill('#editor', {
            theme: 'bubble',
            readOnly: true,
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
