@extends('layouts.site')

@section('title', $article->titre)

@section('content')
<div class="row">
    <div class="col-lg-8 p-0 d-flex flex-wrap justify-content-center">
        <h2 class="col-12 h2 pt-3 font-weight-bold">{{ $article->titre }}</h2>
        <div class="col-8 d-flex justify-content-center">
            <img src="/storage/img/{{ $article->img }}" alt="" class="img-fluid">
        </div>

        <!-- Create the editor container -->
        <div class="col-12 pt-3">
            {!! $article->preambule !!}
            {!! $article->texte !!}
        </div>

        <div class="col-12">
            <a href="{{ route('article.update', ['uniqid' => $article->uniqid]) }}"><button class="btn btn-primary">Modifier</button></a>
            {{-- <a href="'{{ route('article.delete', ['uniqid' => $article->uniqid]) }}"><button class="btn btn-primary">Supprimer</button></a> --}}
        </div>
    </div>
</div>
@endsection
