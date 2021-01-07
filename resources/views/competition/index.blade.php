@extends('layouts.competition')

@section('title', $competition . ' - ' . $sport)

@section('content')
<div class="row d-flex flex-wrap justify-content-between text-center pb-3">
    <div class="col-12 py-3">
        <h1 class="h4">{{ $sport . ' - ' . $competition }}</h1>
    </div>
    <div class="col-lg-8">
      {!! $articles !!}
    </div>
    <div class="d-none d-lg-block col-4">
        {!! $journees !!}
    </div>
</div>
@endsection
