@extends('layouts.site')

@section('title', 'Accueil de notre site')

@section('content')

<div class="row bg-white justify-content-center">
    {{-- <div class="col-12 text-center">
        <h1 class="h4">Mayotte sport : l'actualité sportive de l'île de Mayotte</h1>
    </div> --}}

    @foreach ($sports as $sport)
        <div class="col-12 text-center mb-2 px-3">
            <span class="h2 font-italic">
                <a class="text-body" href="{{ route('sport.index', ['sport' => \Str::slug($sport->nom)]) }}">
                    {{ $sport->nom }}
                </a>
            </span>
        </div>
        @foreach ($sport->journees as $journee)
        <div class="col-12 text-center pb-3 px-3 row justify-content-between">
            <h3 class="col-12 h4 border-bottom-calendrier py-2">
                <a href="{{ route('competition.index', ['sport' => \Str::slug($sport->nom), 'competition' => \Str::slug($journee['competition_nom'])]) }}">
                    {{ $journee['competition_nom'] }}
                </a>
            </h3>
            <div class="col-lg-8 pl-3">
                {!! $journee['journee_render'] !!}
            </div>
            <div class="d-none d-lg-block col-lg-4 pl-5 pr-0">
                @if ($journee['saison_classement'])
                    {!! $journee['saison_classement'] !!}
                @endif
            </div>
        </div>
        @endforeach
    @endforeach
</div>

@endsection
