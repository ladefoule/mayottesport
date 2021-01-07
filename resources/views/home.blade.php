@extends('layouts.site')

@section('title', 'Accueil de notre site')

@section('content')

<div class="row bg-white justify-content-center p-3">
    <div class="col-12 text-center">
        <h1 class="h4">MayotteSport.com : l'actualité sportive de Mayotte</h1>
    </div>

    <div class="col-lg-8 p-0 pr-lg-3">
        @csrf
        @php $i = 0; @endphp
        @foreach ($articles as $article)
        <div class="d-flex flex-wrap justify-content-center border-bottom py-3">
            <a class="col-12 d-flex flex-wrap justify-content-center text-body p-0" href="{{ $article->href }}">
                @if ($i == 0)
                    <h1 class="col-12 titre-premier-article p-0">{{ $article->titre }}</h1>
                    <div class="col-10 my-3 p-0">
                        <img src="{{ $article->img }}" alt="{{ $article->titre }}" title="{{ $article->titre }}" class="img-fluid">
                    </div>
                    <div class="col-12 border-0 p-0">
                        {!! $article->preambule !!}
                    </div>
                    <p class="w-100 text-secondary text-left">Publié le {{ $article->publie_le }}</p>
                @else
                    <div class="col-6 p-0 pr-1">
                        <h1 class="col-12 titre-article p-0">{{ $article->titre }}</h1>
                        <div class="col-12 border-0 p-0 d-none d-sm-block resume">
                            {!! $article->preambule !!}
                        </div>
                        <p class="text-secondary">Publié le {{ $article->publie_le }}</p>
                    </div>
                    <div class="col-6 p-0">
                        <img src="{{ $article->src_img }}" alt="{{ $article->titre }}" title="{{ $article->titre }}" class="img-fluid">
                    </div>
                @endif
            </a>
        </div>
        @php $i++; @endphp
        @endforeach
    </div>
    <div class="col-4 d-none d-lg-block p-0">
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
