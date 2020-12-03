@extends('layouts.sport')

@section('title', $title)

@section('content')

<div class="row d-flex flex-wrap bg-white rounded p-2">
    <h1 class="h3 text-center pt-3 col-12 text-body">{{ $equipe->nom_complet ?? $equipe->nom }}</h1>
    <div class="row order-2 order-lg-1 col-lg-8 d-flex flex-wrap justify-content-center my-3 px-0 mx-0">
        <div class="col-12 d-flex flex-wrap justify-content-center mb-auto">
            <div class="col-12 mb-3 d-flex justify-content-center">
                <select class="form-control col-10 col-sm-6" name="journee" id="competition_id">
                    <option value="">Compétitions</option>
                    @foreach ($competitions as $competition)
                        <option value="{{ $competition->id }}" @if($competition->id == $derniereCompetition->id) selected @endif>
                            {{ $competition->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <select class="form-control col-10 col-sm-6" name="journee" id="saison_id">
                    <option value="">Saisons</option>
                    @foreach ($saisons as $saison)
                        <option value="{{ $saison->id }}" @if($saison->id == $derniereSaison->id) selected @endif>
                            {{ $saison->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <form action="">@csrf</form>
        </div>
        <div class="row col-12 mt-3 mb-auto px-0" id="matches">
            <?php $i = 0 ?>
            @foreach ($matches as $match)
                <?php
                    $domicile = $match['equipe_id_dom'] == $equipe->id;
                    $hrefEqDom = $match['href_equipe_dom'];
                    $hrefEqExt = $match['href_equipe_ext'];
                    $resultat = $domicile ? $match['resultat_eq_dom'] : $match['resultat_eq_ext'];
                    $resultat = $resultat['resultat'] ?? '';
                ?>
                <div class="col-12 row d-flex flex-nowrap py-2 px-0 mx-0 border-bottom @if($i==0) border-top @endif">
                    <div class="col-4 p-0 d-flex flex-wrap justify-content-start text-left align-items-center">
                        <div class="col-lg-4 d-lg-inline py-0 px-0 text-center logo-align-auto">
                            @if (! $domicile)
                                <a class="text-dark" href="{{ $hrefEqDom }}">
                            @endif
                            <img src="{{ $match['fanion_equipe_dom'] }}" alt="{{ $match['equipe_dom_nom'] }}" class="fanion-page-equipe">
                            @if (! $domicile)
                                </a>
                            @endif
                        </div>
                        <div class="equipe-domicile col-lg-8 d-lg-inline px-0 equipe-align-auto">
                            @if (! $domicile)
                                <a class="text-dark" href="{{ $hrefEqDom }}">
                            @endif
                            {{ $match['equipe_dom_nom'] }}
                            @if (! $domicile)
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
                            @if ($domicile)
                                <a class="text-dark" href="{{ $hrefEqExt }}">
                            @endif
                            {{ $match['equipe_ext_nom'] }}
                            @if ($domicile)
                                </a>
                            @endif
                        </div>
                        <div class="col-lg-4 d-lg-inline order-1 order-lg-2 py-0 px-0 text-center logo-align-auto">
                            @if ($domicile)
                                <a class="text-dark" href="{{ $hrefEqExt }}">
                            @endif
                            <img src="{{ $match['fanion_equipe_ext'] }}" alt="{{ $match['equipe_ext_nom'] }}" class="fanion-page-equipe">
                            @if ($domicile)
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <?php $i++; ?>
            @endforeach
        </div>
    </div>
    <div class="dernier-et-prochain-match d-flex justify-content-center flex-wrap order-1 order-lg-2 col-lg-4 mt-3 px-0 text-center mb-auto">
        @if ($dernierMatch)
        <div class="col-md-6 col-12 col-lg-12 p-2 mb-auto">
            <h3 class="alert h5 alert-danger text-center text-body">Dernier match</h3>
            {!! $dernierMatch !!}
        </div>

        @endif
        @if ($prochainMatch)
        <div class="col-md-6 col-12 col-lg-12 p-2 mb-auto">
            <h3 class="alert {{-- mt-3 --}} h5 alert-success text-center text-body">Prochain match</h3>
            {!! $prochainMatch !!}
        </div>
        @endif
    </div>
    <div class="order-3 col-lg-4 pt-3 px-0 text-center">
        <div class="border h-100">
            PUB
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
$(document).ready(function(){
    var competitions = qs('#competition_id')
    var saisons = qs('#saison_id')
    var matches = qs('#matches')
    var inputToken = qs('input[name=_token]')

    $('#competition_id').change(function(){
        matches.innerHTML = ''
        saisons.innerHTML = ''
        if(! competitions.value)
            return false;
        let donneesRequeteAjax = {
            url : "<?php echo route('ajax', ['table' => 'saisons']) ?>",
            method : 'POST',
            idSelect : 'saison_id',
            data : {competition_id:competition_id.value, _token:inputToken.value}
        }

        ajaxSelect(donneesRequeteAjax) // On récupère la liste des saisons
    })

    $('#saison_id').change(function(){
        matches.innerHTML = ''
        if(! saisons.value)
            return false;

        $.ajax({
            type: 'GET',
            url: "<?php echo route('equipe.matches') ?>",
            data:{equipe_id:"<?php echo $equipe->id ?>", saison_id:saisons.value},
            success:function(data){
                matches.innerHTML = data
            }
        })
    })
})
</script>
@endsection
