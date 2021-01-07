@extends('layouts.site')

@section('title', 'Accueil de notre site')

@section('content')

<div class="row bg-white justify-content-center p-3">
    <div class="col-12 text-center">
        <h1 class="h4">MayotteSport.com : l'actualité sportive de Mayotte</h1>
    </div>

    <div class="col-lg-8 p-0 pr-lg-3">
        {!! $articles !!}
    </div>
    <div class="col-4 d-none d-lg-block p-0">
        {!! $journees !!}
    </div>
</div>
@endsection
