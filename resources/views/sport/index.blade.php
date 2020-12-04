@extends('layouts.sport')

@section('title', "$sport->nom - Les derniers résultats")

@section('content')

<div class="row py-3 bg-white justify-content-center border rounded mx-0">
    @foreach ($journees as $journee)
    <div class="col-12 text-center pb-3 px-2 row justify-content-between">
        <h3 class="col-12 h4 border-bottom-calendrier py-2"><a href="{{ route('competition.index', ['sport' => strToUrl($sport->nom), 'competition' => strToUrl($journee['competition_nom'])]) }}">{{ $journee['competition_nom'] }}</a></h3>
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
</div>

@endsection
