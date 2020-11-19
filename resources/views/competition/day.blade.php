@extends('layouts.competition')

@section('title', request()->competition->nom . ' - Calendrier et résultats - ' . niemeJournee($currentDay->numero) . ' - '
    . request()->sport->nom)

@section('content')
<?php //dd($currentDay) ?>
    <day-calendar
        competition="{{ request()->competition->nom }}"
        :days="{{ $journees }}"
        previous="{{ $hrefJourneePrecedente }}"
        next="{{ $hrefJourneeSuivante }}"
        current="{{ $currentDay->numero }}"
        :matches="{{ $calendrier }}"
    >
    </day-calendar>
    {{-- <div class="row d-flex flex-wrap m-0 bg-white rounded p-3">
        <h1 class="h4 text-center p-2 col-12">{{ request()->competition->nom . ' - Calendrier et résultats' }}</h1>
        <div class="col-12 d-flex flex-nowrap justify-content-center align-items-center pb-3">
            @if ($hrefJourneePrecedente)
                <a href="{{ $hrefJourneePrecedente }}" class="float-right pr-2">précédente</a>
            @endif
            <select class="form-control col-6 col-sm-4 col-md-3 px-2" name="journee" id="journee">
                @foreach ($journees as $journee_)
                    <option
                        data-href="{{ route('competition.day', ['sport' => strToUrl(request()->sport->nom), 'competition' => strToUrl(request()->competition->nom), 'journee' => $journee_->numero]) }}"
                        value="{{ $journee_->numero }}" @if ($journee->numero == $journee_->numero) selected
                @endif>{{ niemeJournee($journee_->numero) }}</option>
                @endforeach
            </select>
            @if ($hrefJourneeSuivante)
                <a href="{{ $hrefJourneeSuivante }}" class="float-left pl-2">suivante</a>
            @endif
        </div>
        <div class="col-lg-8 d-flex flex-wrap p-0">
            <div class="col-12 pb-3 mb-3 px-0">
                <div class="px-3">
                    {!! $calendrierJourneeHtml !!}
                </div>
            </div>
        </div>
        <div class="col-lg-4 pl-5 pr-0 text-center">
            PUB
        </div>
    </div> --}}

@endsection

@section('script')
    <script>
        // $(document).ready(function(){
        //     $('#journee').on('change', function() {
        //         option = this.options[this.selectedIndex]
        //         document.location = option.dataset.href
        //     })
        // })

    </script>
@endsection
