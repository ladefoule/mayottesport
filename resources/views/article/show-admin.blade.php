@extends('layouts.site')

@section('title', $article->titre)

@section('content')
<div class="row bg-white">
    <div class="col-lg-8 p-0 d-flex flex-wrap justify-content-center">
        <h1 class="col-12 titre-article py-3">{{ $article->titre }}</h1>
        <div class="col-10 d-flex justify-content-center">
            <img src="{{ $article->img }}" alt="{{ $article->titre }}" title="{{ $article->titre }}" class="img-fluid">
        </div>

        <!-- Create the editor container -->
        <div class="col-12 pt-3">
           <div class="font-weight-bold">
              {!! $article->preambule !!}
           </div>
            {!! $article->texte !!}
        </div>
    </div>
    <div class="col-lg-4 text-center p-3">
         <a class="mb-3 btn btn-success w-50" href="{{ route('article.update', ['uniqid' => $article->uniqid]) }}">Modifier l'article</a>
         <a class="mb-3 btn btn-primary w-50" href="{{ route('article.create') }}">Nouvel article</a>
         <a class="mb-3 btn btn-danger w-50" href="{{ asset('/adminsharp') }}">Administration</a>
    </div>
</div>
@endsection
