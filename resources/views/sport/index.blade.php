@extends('layouts.sport')

@section('title', "$sport->nom - Les derniers résultats")

@section('content')

<div class="row justify-content-center">
    <img src="/storage/img/marche-droits-tv-football-suisse-2048x980.jpg" alt="" class="img-fluid" height="10px">
    <div class="col-12 text-center pt-3">
        <h1 class="h4">Accueil football : actus, résultats</h1>
    </div>

    <div class="col-lg-8">
      {!! $articles !!}
    </div>
    <div class="d-none d-lg-block col-4">
        {!! $journees !!}
    </div>
</div>
@endsection
