@extends('layouts.competition')

@section('title', $title)

@section('content')
<div class="p-lg-3">
    <div class="row bg-white shadow-div justify-content-center m-0">
        <div class="col-12 pb-3 px-2">
            <h1 class="h4 text-center col-12 px-3 py-4">{{ $competition->nom . ' - Calendrier et résultats'}}</h1>
            @if($calendrierJourneeHtml)
                <div class="col-12 d-flex flex-nowrap justify-content-center align-items-center pb-3">
                    <a id="previous" href="{{ $hrefJourneePrecedente }}" class="float-right pr-3 @if ($journeeActuelle->numero == 1) cursor-default non-cliquable @endif" style="font-size: 1.4rem">{!! \Config::get('listes.boutons.left') !!}</a>
                    <select class="form-control col-6 col-sm-4 col-md-3 px-2" name="journee" id="journees">
                        @foreach ($journees as $journee)
                            <option value="{{ $journee->href }}" @if($journeeActuelle->numero == $journee->numero) selected @endif>
                                    {{ $journee->nom }}
                            </option>
                        @endforeach
                    </select>
                    <a id="next" href="{{ $hrefJourneeSuivante }}" class="float-left pl-3 @if ($journeeActuelle->numero == $saison->nb_journees) cursor-default non-cliquable @endif" style="font-size: 1.4rem">{!! \Config::get('listes.boutons.right') !!}</a>
                </div>
                <div class="col-12 px-0" id="matches">
                    {!! $calendrierJourneeHtml !!}
                </div>
            @endif

            {{-- PUB --}}
            <div class="col-12 m-auto py-3 px-2">
                @include('pub.google-display-responsive')
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function(){
    $('#journees').on('change', function() {
        option = this.options[this.selectedIndex]
        href = option.value
        window.location = href
    })
})

function ajax(journee)
{
    var matches = qs('#matches')
    var journees = qs('#journees')
    var previous = qs('#previous')
    var next = qs('#next')
    var saison = "<?php echo $saison->id ?>"
    $.ajax({
        type: 'GET',
        url: "<?php echo route('journee.render') ?>",
        data:{journee,saison},
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
