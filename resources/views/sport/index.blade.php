@extends('layouts.sport')

@section('title', "$sport - Les derniers résultats")

@section('content')

<div class="row py-3 bg-white justify-content-center rounded">
    @foreach ($liste as $competition)
    <div class="col-12 text-center px-2 row justify-content-between">
        <h3 class="col-12 h4 border-bottom-calendrier py-2">{{ $competition['nom'] }}</h3>
        <div class="col-lg-8 pl-3">
            {!! $competition['journee'] !!}
        </div>
        <div class="d-none d-lg-block col-lg-4 pl-5 pr-0">
            @if ($competition['classement'])
                {!! $competition['classement'] !!}
            @endif
        </div>
    </div>
    @endforeach
</div>

@endsection
