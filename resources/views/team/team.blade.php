@extends('layouts.team')

@section('title', request()->team->nom . ' - ' . request()->sport->nom)

@section('content')

<div class="row d-flex flex-wrap m-0 bg-white rounded p-3">
    {{-- <h1 class="h4 text-center p-2 col-12">{{ request()->team->nom . ' - Calendrier et résultats'}}</h1>
    <div class="col-12 d-flex flex-nowrap justify-content-center align-items-center pb-3">
        <a id="previous" data-id="{{ $journee->numero - 1 }}" href="" class="float-right pr-3 @if ($journee->numero == 1) cursor-default non-cliquable @endif" style="font-size: 1.4rem"><i class="fas fa-chevron-left"></i></a>
        <select class="form-control col-6 col-sm-4 col-md-3 px-2" name="journee" id="journees">
            @foreach ($journees as $journee_)
                <option data-href="{{ route('competition.day', ['sport' => strToUrl(request()->sport->nom),'competition' => strToUrl(request()->competition->nom),'journee' => $journee_->numero]) }}"
                    value="{{ $journee_->numero }}" @if($journee->numero == $journee_->numero) selected @endif>{{ niemeJournee($journee_->numero) }}</option>
            @endforeach
        </select>
        <a id="next" data-id="{{ $journee->numero + 1 }}" href="" class="float-left pl-3 @if ($journee->numero == $saison->nb_journees) cursor-default non-cliquable @endif" style="font-size: 1.4rem"><i class="fas fa-chevron-right"></i></a>
    </div>
    <div class="col-lg-8 d-flex flex-wrap p-0">
        <div class="col-12 pb-3 mb-3 px-0">
            <div class="px-3" id="matches">
                {!! $calendrierJourneeHtml !!}
            </div>
        </div>
    </div> --}}
    <div class="col-lg-4 pl-5 pr-0 text-center">
        PUB
    </div>
</div>

@endsection

@section('script')
<script>
$(document).ready(function(){

})

function ajax(journee)
{
    var matches = qs('#matches')
    var journees = qs('#journees')
    var previous = qs('#previous')
    var next = qs('#next')
    var saison = "<?php //echo $saison->id ?>"
    $.ajax({
        type: 'GET',
        url: "<?php echo route('day.calendar.display') ?>",
        data:{journee, saison},
        success:function(data){
            matches.innerHTML = data
            previous.classList.remove('cursor-default', 'non-cliquable')
            next.classList.remove('cursor-default', 'non-cliquable')
            if(journee == 1)
                previous.classList.add('cursor-default', 'non-cliquable')
            if(journee == journees.length)
                next.classList.add('cursor-default', 'non-cliquable')

            previous.dataset.id = journee > 1 ? (journee-1) : 1
            next.dataset.id = journee < journees.length ? parseInt(journee)+1 : journees.length
            options = qsa('option', journees)
            options.forEach(option => {
                if(option.value == journee)
                    option.selected = 'selected'
                else
                    option.removeAttribute('selected')
            });
        }
    })
}
</script>
@endsection
