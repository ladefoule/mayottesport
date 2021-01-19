@extends('layouts.site')

@section('title', $article->titre)

@section('content')
<div class="p-lg-3">
    <div class="row m-0 bg-white">
        <div class="col-12 p-0 d-flex flex-wrap justify-content-center">
            <h1 class="col-12 titre-page-article py-3">
                <span class="categorie">{{ $article->categorie }}</span>
                {{ $article->titre }}
            </h1>
            <div class="col-12 d-flex justify-content-center">
                <img src="{{ $article->img }}" alt="{{ $article->titre }}" title="{{ $article->titre }}" class="img-fluid">
            </div>

            <!-- Create the editor container -->
            <div class="col-12 pt-3">
            <div class="font-weight-bold">
                {!! $article->preambule !!}
            </div>
                {!! $article->article !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section('section-droite')
<div class="my-3 bg-white">
    <div class="col-12 text-center p-3 bg-white">
        <a class="mb-3 btn btn-success w-50" href="{{ route('article.update', ['uniqid' => $article->uniqid]) }}">Modifier l'article</a>
        <a class="mb-3 btn btn-primary w-50" href="{{ route('article.create') }}">Nouvel article</a>
        <a class="mb-3 btn btn-danger w-50" href="{{ asset('/adminsharp') }}">Administration</a>
    </div>
</div>
@endsection
