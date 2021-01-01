@extends('layouts.site')

@section('title', 'Accueil de notre site')

@section('content')

<div class="row bg-white justify-content-center">
    <div class="col-12 text-center py-3">
        <h1 class="h4">MayotteSport.com : l'actualité sportive de Mayotte</h1>
    </div>

    <div class="col-lg-8 p-0">
        @csrf
        @foreach ($articles as $article)
        <div class="p-0 d-flex flex-wrap justify-content-center">
            <h1 class="col-12 h2 py-3 font-weight-bold">{{ $article->titre }}</h1>
            <div class="col-10 d-flex justify-content-center pb-3">
                <img src="/storage/img/{{ $article->img }}" alt="" class="img-fluid">
            </div>


            <div id="" class="col-12 border-0">
                {!! $article->preambule !!}
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
