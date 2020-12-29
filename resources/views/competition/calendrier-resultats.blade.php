@extends('layouts.competition')

<?php
    $journeeActuelle = $journee;
    $competition = request()->competition;
?>

@section('title', $competition->nom . ' - Calendrier et résultats - '.niemeJournee($journeeActuelle->numero).' - ' . request()->sport->nom)

@section('content')
<div class="row d-flex flex-wrap">
    <h1 class="h4 text-center col-12 p-3">{{ $competition->nom . ' - Calendrier et résultats'}}</h1>
    <div class="col-12 d-flex flex-nowrap justify-content-center align-items-center pb-3">
        <a id="previous" data-id="{{ $journeeActuelle->numero - 1 }}" href="" class="float-right pr-3 @if ($journeeActuelle->numero == 1) cursor-default non-cliquable @endif" style="font-size: 1.4rem">{!! \Config::get('listes.boutons.left') !!}</a>
        <select class="form-control col-6 col-sm-4 col-md-3 px-2" name="journee" id="journees">
            @foreach ($journees as $journee)
                <option value="{{ $journee->numero }}" @if($journeeActuelle->numero == $journee->numero) selected @endif>
                    {{ niemeJournee($journee->numero) }}
                </option>
            @endforeach
        </select>
        <a id="next" data-id="{{ $journeeActuelle->numero + 1 }}" href="" class="float-left pl-3 @if ($journeeActuelle->numero == $saison->nb_journees) cursor-default non-cliquable @endif" style="font-size: 1.4rem">{!! \Config::get('listes.boutons.right') !!}</a>
    </div>
    <div class="col-lg-9 d-flex flex-wrap px-3 pb-3">
        <div class="col-12" id="matches">
            {!! $calendrierJourneeHtml !!}
        </div>
    </div>
    <div class="d-flex col-lg-3 justify-content-center px-3 pb-3">
        <div class="border h-100 w-100 p-3 text-center">
            PUB
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
$(document).ready(function(){
    $('#journees').on('change', function() {
        option = this.options[this.selectedIndex]
        journee = option.value
        ajax(journee)
    })
    $('#previous, #next').on('click', function(e) {
        e.preventDefault()
        journee = this.dataset.id
        if(! this.classList.contains('non-cliquable'))
            ajax(journee)
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
