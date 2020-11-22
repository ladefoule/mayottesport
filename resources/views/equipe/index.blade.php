@extends('layouts.sport')

@section('title', $title)

@section('content')

<div class="row d-flex flex-wrap m-0 bg-white rounded p-3">
    <h1 class="h4 text-center p-2 col-12">{{ $equipe->nom_complet ?? $equipe->nom }}</h1>
    <div class="col-12 d-flex flex-nowrap justify-content-center align-items-center pb-3">
        <select class="form-control col-6 col-sm-4 col-md-3 px-2 mr-3" name="journee" id="journees">
            <option value="">Compétitions</option>
            @foreach ($competitions as $competition)
                <option value="{{ $competition->id }}" @if($competition->id == $derniereCompetition->id) selected @endif>
                    {{ $competition->nom }}
                </option>
            @endforeach
        </select>
        <select class="form-control col-6 col-sm-4 col-md-3 px-2" name="journee" id="journees">
            <option value="">Saisons</option>
            @foreach ($saisons as $saison)
                <option value="{{ $saison->id }}" @if($saison->id == $derniereSaison->id) selected @endif>
                    {{ $saison->nom }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-8 d-flex flex-wrap justify-content-center pt-3 px-0">
        @foreach ($matches as $i => $match)
            <?php
                $equipeDomId = $match->equipe_id_dom;
                $equipeExtId = $match->equipe_id_ext;
                $resultat = $match['resultat'];
                $match = $match->infos();
            ?>
            <div class="col-12 row d-flex flex-nowrap py-2 px-0 border-bottom @if($i==0) border-top @endif">
                <div class="col-4 p-0 d-flex flex-wrap justify-content-start text-left align-items-center">
                    <div class="col-lg-4 d-lg-inline py-0 px-0 text-center logo-align-auto">
                        <img src="{{ $match['fanion_eq_dom'] }}" alt="{{ $match['nom_eq_dom'] }}" class="fanion-page-equipe">
                    </div>
                    <div class="equipe-domicile col-lg-8 d-lg-inline px-0 equipe-align-auto">
                        @if ($equipeDomId != $equipe->id)
                            <a class="text-dark" href="{{ $match['href_eq_dom'] }}">
                        @endif
                        {{ $match['nom_eq_dom'] }}
                        @if ($equipeDomId != $equipe->id)
                            </a>
                        @endif
                    </div>
                </div>
                <a href="{{ $match['url'] }}" class="col-4 d-flex text-body flex-wrap justify-content-center align-items-center p-0">
                    <?php
                        if(strlen($match['score_eq_dom']) > 0 && strlen($match['score_eq_ext']) > 0){
                            echo '<span class="font-weight-bold '.$resultat.'">' . $match['score'] . '</span>';
                            echo '<span class="col-12 text-center" style="font-size:0.6rem">' . date_format(new DateTime($match['date']), 'd/m/y') . '</span>';
                        }
                        else
                            echo '<span style="font-size: 1.5rem">' . date_format(new DateTime($match['date']), 'd/m') . '</span>';

                        echo '<span class="col-12 text-center font-weight-bold" style="font-size:0.7rem">' . $match['competition'] . '</span>';
                    ?>
                </a>
                <div class="col-4 p-0 d-flex flex-wrap justify-content-end align-items-center text-right">
                    <div class="equipe-exterieur col-lg-8 d-lg-inline order-2 order-lg-1 px-0 equipe-align-auto">
                        @if ($equipeExtId != $equipe->id)
                            <a class="text-dark" href="{{ $match['href_eq_ext'] }}">
                        @endif
                        {{ $match['nom_eq_ext'] }}
                        @if ($equipeExtId != $equipe->id)
                            </a>
                        @endif
                    </div>
                    <div class="col-lg-4 d-lg-inline order-1 order-lg-2 py-0 px-0 text-center logo-align-auto">
                        @if ($equipeExtId != $equipe->id)
                            <a class="text-dark" href="{{ $match['href_eq_ext'] }}">
                        @endif
                        <img src="{{ $match['fanion_eq_ext'] }}" alt="{{ $match['nom_eq_ext'] }}" class="fanion-page-equipe">
                        @if ($equipeExtId != $equipe->id)
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-lg-4 pt-3 pr-0 text-center">
        @if ($dernierMatch)
            <h3 class="alert h5 alert-danger text-center">Dernier match</h3>
            {!! $dernierMatch !!}
        @endif
        @if ($prochainMatch)
            <h3 class="alert mt-3 h5 alert-success text-center">Prochain match</h3>
            {!! $prochainMatch !!}
        @endif
    </div>
    <div class="col-lg-4 pt-3 pr-0 text-center">
        <div class="border h-100">
            PUB
        </div>
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
