@extends('layouts.sport')

@section('title', "$sport->nom - Les derniers résultats")

@section('content')
<div class="row justify-content-center">
    @foreach ($journees as $journee)
    <div class="col-12 text-center px-3 row justify-content-between">
        <h3 class="col-12 h4 border-bottom-calendrier py-2"><a href="{{ route('competition.index', ['sport' => \Str::slug($sport->nom), 'competition' => \Str::slug($journee['competition_nom'])]) }}">{{ $journee['competition_nom'] }}</a></h3>
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
