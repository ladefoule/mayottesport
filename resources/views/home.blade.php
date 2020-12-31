@extends('layouts.site')

@section('title', 'Accueil de notre site')

@section('content')

<div class="row bg-white justify-content-center">
    <div class="col-12 text-center pt-3">
        <h1 class="h4">MayotteSport.com : l'actualité sportive de l'île de Mayotte</h1>
    </div>

    <div class="col-lg-8">
        @csrf
        @foreach ($articles as $article)
        <div class="p-0 d-flex flex-wrap justify-content-center">
            <h2 class="col-12 h2 pt-3 font-weight-bold">{{ $article->titre }}</h2>
            <div class="col-8 d-flex justify-content-center">
                <img src="/storage/img/{{ $article->img }}" alt="" class="img-fluid">
            </div>

            <!-- Create the editor container -->
            <div id="editor-{{ $article->uniqid }}" data-url="{{ route('article.ajax', ['uniqid' => $article->uniqid]) }}" class="col-12 border-0" style="font-size:1.0rem">

            </div>
        </div>
        @endforeach
    </div>
    <div class="col-4 d-none d-lg-block">
        @foreach ($sports as $sport)
            <div class="col-12 text-center my-2 px-3">
                <span class="h2 font-italic">
                    <a class="text-body" href="{{ route('sport.index', ['sport' => \Str::slug($sport->nom)]) }}">
                        {{ $sport->nom }}
                    </a>
                </span>
            </div>
            @foreach ($sport->journees as $journee)
            <div class="col-12 text-center pb-3 justify-content-between">
                <h3 class="col-12 h4 border-bottom-calendrier py-2">
                    <a href="{{ route('competition.index', ['sport' => \Str::slug($sport->nom), 'competition' => \Str::slug($journee['competition_nom'])]) }}">
                        {{ $journee['competition_nom'] }}
                    </a>
                </h3>
                <div class="pl-0">
                    {!! $journee['journee_render'] !!}
                </div>
            </div>
            @endforeach
        @endforeach
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function(){
        var token = qs('input[name=_token]').value

        let uniqids = "<?php echo $liste; ?>"
        uniqids = uniqids.split(',')
        uniqids.forEach(uniqid => {
            var quill = new Quill('#editor-' + uniqid, {
                theme: 'bubble',
                modules: {
                    toolbar: []
                },
                readOnly: true,
            });

            $.ajax({
                type: 'POST',
                url: qs('#editor-' + uniqid).dataset.url,
                data: {_token:token},
                success:function(data){
                    quill.setContents(
                        JSON.parse(data)
                    )
                }
            })
        });
    })
</script>
@endsection
