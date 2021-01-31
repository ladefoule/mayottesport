@extends('layouts.competition')

@section('title', $sport->nom . ' - ' . $competition->nom_complet . ' - L\'actualité de la compétition')

@section('content')
<div class="p-lg-3 h-100">
    <div class="col-12 px-3 pt-3 bg-white shadow-div">
        {{-- <div class="col-12">
            <h1 class="h3 text-center m-auto p-3">{{ $sport->nom }} - {{ $competition->nom_complet }}</h1>
        </div> --}}

        <div class="p-0 col-12">
            {!! $articles !!}
        </div>

        {{-- PUB --}}
        <div class="col-12 m-auto py-3 px-2">
            @include('pub.google-display-responsive')
        </div>
    </div>
</div>
@endsection