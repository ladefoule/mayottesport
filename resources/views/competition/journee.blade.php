@extends('layouts.competition')

@section('title', $competition . ' - Calendrier et résultats - '.niemeJournee($journee->numero).' - ' . $sport)

<?php
$sportNomKebab = strToUrl($sport);
$competitionNomKebab = strToUrl($competition);
?>

@section('content')

<div class="row d-flex flex-wrap m-0 my-3 bg-white rounded p-3">
    <div class="col-12 d-flex flex-wrap justify-content-center align-items-center pb-3">
        <h1 class="h4 text-center p-2 col-12">{{ Str::ucfirst($sport) . ' - ' . $competition }}</h1>
        @if ($hrefJourneePrecedente)
            <a href="{{ $hrefJourneePrecedente }}" class="float-right pr-3">précédente</a>
        @endif
        <select class="form-control col-6 col-sm-4 col-md-3" name="journee" id="journee">
            @foreach ($journees as $journee_)
                <option data-href="{{ route('competition.journee', ['sport' => $sportNomKebab,'competition' => $competitionNomKebab,'journee' => $journee_->numero]) }}"
                    value="{{ $journee_->numero }}" @if($journee->numero == $journee_->numero) selected @endif>{{ niemeJournee($journee_->numero) }}</option>
            @endforeach
        </select>
        @if ($hrefJourneeSuivante)
            <a href="{{ $hrefJourneeSuivante }}" class="float-left pl-3">suivante</a>
        @endif
    </div>
    <div class="col-lg-8 d-flex flex-wrap p-0">
        <div class="col-12 pb-3 mb-3 px-0">
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

@section('script')
<script>
$(document).ready(function(){
    $('#journee').on('change', function() {
        option = this.options[this.selectedIndex]
        document.location = option.dataset.href
    })
})
</script>
@endsection
