@extends('layouts.sport')

@section('title', $title)

@section('content')

<div class="row d-flex flex-wrap m-0 bg-white rounded p-3">
    <h1 class="h4 text-center p-2 col-12">{{ $equipe->nom_complet ?? $equipe->nom }}</h1>
    <div class="col-12 d-flex flex-nowrap justify-content-center align-items-center pb-3">
        <select class="form-control col-6 col-sm-4 col-md-3 px-2" name="journee" id="journees">
            <option value="">Sélectionner</option>
            @foreach ($competitions as $competition)
                <option value="{{ $competition->id }}">
                    {{ $competition->nom }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-8 d-flex flex-wrap p-0">
        <div class="col-12 pb-3 mb-3 px-0">
            <div class="px-3 border" id="matches">
                @foreach ($matches as $match)
                    {{-- <a href="{{ $match['url'] }}" class="text-decoration-none text-body match-calendrier">
                        <div class="row d-flex flex-nowrap py-2 border-bottom @if($i==0) border-top @endif">
                            <div class="col-5 p-0 d-flex justify-content-between align-items-center @if($match['score_eq_dom'] > $match['score_eq_ext']) font-weight-bold @endif">
                                <div>
                                    <img src="{{ $match['fanion_eq_dom'] }}" alt="{{ $match['nom_eq_dom'] }}" class="fanion-calendrier pr-2">
                                </div>
                                <div class="text-right">
                                    {{ $match['nom_eq_dom'] }}
                                </div>
                            </div>
                            <div class="col-2 d-flex justify-content-center align-items-center p-0">
                                {!! $match['score'] !!}
                            </div>
                            <div class="col-5 p-0 d-flex justify-content-between align-items-center @if($match['score_eq_dom'] < $match['score_eq_ext']) font-weight-bold @endif">
                                <div class="text-left">
                                    {{ $match['nom_eq_ext'] }}
                                </div>
                                <div>
                                    <img src="{{ $match['fanion_eq_ext'] }}" alt="{{ $match['nom_eq_ext'] }}" class="fanion-calendrier pl-2">
                                </div>
                            </div>
                        </div>
                    </a> --}}
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-4 pl-5 pr-0 text-center border">
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
        url: "<?php echo route('journee.calendrier') ?>",
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
