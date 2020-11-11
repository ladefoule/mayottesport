@extends('layouts.competition')

@section('title', $competition . ' - ' . $sport)

@section('content')

<div class="row d-flex flex-wrap m-0 my-3 bg-white rounded p-3">
    <div class="col-12 d-flex flex-wrap justify-content-center align-items-center pb-3">
        <h1 class="h4 text-center p-2 col-12">{{ Str::ucfirst($sport) . ' - ' . $competition }}</h1>
        <a href="" class="float-right pr-3">précédente</a>
        <select class="form-control col-6 col-sm-4 col-md-3" name="journee" id="journee">
            @foreach ($journees as $journee)
                <option value="{{ $journee->numero }}">{{ niemeJournee($journee->numero) }}</option>
            @endforeach
        </select>
        <a href="" class="float-left pl-3">suivante</a>
    </div>
    <div class="col-lg-8 d-flex flex-wrap p-0">
        <div class="col-12 pb-3 mb-3 px-0">
            {{-- <h3 class="alert alert-danger text-center">{{ $journee }}</h3> --}}
            <div class="px-3">
                {!! $calendrierJournee !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4 pl-5 pr-0 text-center">
        PUB
    </div>
</div>

@endsection
