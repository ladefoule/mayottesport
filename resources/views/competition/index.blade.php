@extends('layouts.' . $sport)

@section('title', $competition)

@section('content')

<div class="row m-0 my-3 bg-white rounded p-3">
    <div class="col-lg-8 pl-3">
        {!! $derniereJournee !!}
        {!! $prochaineJournee !!}
    </div>
    <div class="d-none d-lg-block col-lg-4 pl-5 pr-0">
        {!! $classement !!}
    </div>
</div>

@endsection
