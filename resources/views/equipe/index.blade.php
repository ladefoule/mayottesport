@extends('layouts.sport')

@section('title', $title)

@section('content')

<div class="row d-flex flex-wrap m-0 bg-white rounded p-3">
    <h1 class="h4 text-center p-2 col-12">{{ $equipe->nom_complet ?? $equipe->nom }}</h1>
    <div class="col-12 d-flex flex-nowrap justify-content-center align-items-center pb-3">
        <select class="form-control col-6 col-sm-4 col-md-3 px-2" name="journee" id="journees">
            <option value="">Sélectionner</option>
            @foreach ($saisons as $saison)
                <option value="{{ $saison->id }}">
                    {{ $saison->nom }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-9 d-flex flex-wrap justify-content-center pt-3 px-0">
        @foreach ($matches as $i => $match)
            <?php
                $equipeDomId = $match->equipe_id_dom;
                $equipeExtId = $match->equipe_id_ext;
                $resultat = $match['resultat'];
                $match = $match->infos();
            ?>
            <div class="col-12 row d-flex flex-nowrap py-2 px-0 border-bottom @if($i==0) border-top @endif">
                <div class="col-4 p-0 d-flex justify-content-start text-left align-items-center">
                    @if ($equipeDomId != $equipe->id)
                        <a class="text-dark" href="{{ $match['href_eq_dom'] }}">
                    @endif
                    <img src="{{ $match['fanion_eq_dom'] }}" alt="{{ $match['nom_eq_dom'] }}" class="fanion-calendrier pr-2">
                    {{ $match['nom_eq_dom'] }}
                    @if ($equipeDomId != $equipe->id)
                        <a class="text-dark" href="{{ $match['href_eq_dom'] }}">
                    @endif
                </div>
                <a href="{{ $match['url'] }}" class="col-4 d-flex text-body flex-wrap justify-content-center align-items-center p-0" style="font-size: 1.2rem">
                    <?php
                        if(strlen($match['score_eq_dom']) > 0 && strlen($match['score_eq_ext']) > 0){
                            echo '<span class="font-weight-bold '.$resultat.'">' . $match['score'] . '</span>';
                            echo '<span class="col-12 text-center" style="font-size:0.6rem">' . $match['date_format'] . '</span>';
                        }
                        else
                            echo date_format(new DateTime($match['date']), 'd/m');

                        echo '<span class="col-12 text-center font-weight-bold" style="font-size:0.7rem">' . $match['competition'] . '</span>';
                    ?>
                </a>
                <div class="col-4 p-0 d-flex justify-content-end text-right align-items-center">
                    @if ($equipeExtId != $equipe->id)
                        <a class="text-dark" href="{{ $match['href_eq_ext'] }}">
                    @endif
                    {{ $match['nom_eq_ext'] }}
                    <img src="{{ $match['fanion_eq_ext'] }}" alt="{{ $match['nom_eq_ext'] }}" class="fanion-calendrier pl-2">
                    @if ($equipeExtId != $equipe->id)
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-lg-3 pt-3 pr-0 text-center">
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
