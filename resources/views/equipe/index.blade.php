@extends('layouts.sport')

@section('title', $title)

@section('content')
<div class="p-lg-3 h-100">
    <div class="row d-flex flex-wrap m-0 bg-white shadow-div">
        <div class="col-12 d-flex px-0 pb-3">
            <img src="{{ asset('/storage/img/equipe/'. $equipe->uniqid .'.jpg') }}" alt="" class="img-fluid m-auto">
        </div>
        <h1 class="h3 text-center col-12 text-body">{{ $equipe->nom }}</h1>
        <div class="row col-12 d-flex flex-wrap justify-content-center p-3 m-auto">
            @if ($dernierMatch)
            <div class="col-12 pb-3 m-auto px-0">
                <h3 class="alert h5 alert-danger text-center">Dernier match</h3>
                {!! $dernierMatch !!}
            </div>

            @endif
            @if ($prochainMatch)
            <div class="col-12 pb-3 m-auto px-0">
                <h3 class="alert h5 alert-success text-center">Prochain match</h3>
                {!! $prochainMatch !!}
            </div>
            @endif

            <div class="col-12 d-flex flex-wrap justify-content-center m-auto px-0">
                <h3 class="col-12 alert h5 alert-primary text-center">Calendriers/résultats</h3>
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
                <?php $i = 0; ?>
                @foreach ($matches as $match)
                    @include('journee.modele-calendrier-main', ['match' => infos('matches', $match->id), 'equipeId' => $equipe->id, 'i' => $i])
                    <?php $i++; ?>
                @endforeach
            </div>
        </div>

        <div class="col-12 m-auto py-3 px-2">
            @include('pub.google-display-responsive')
        </div>
    </div>
    @csrf
</div>
@endsection

@include('onglet.section-droite-home-et-sport')

@section('script')
<script>
$(document).ready(function(){
    // Gestion des onglets du bloc de droite
    cibles = qsa('#prochains-section-droite,#resultats-section-droite,#fil-actu-section-droite')
    onglets = qsa('#section-droite .onglet') 
    ongletSwitch(cibles, onglets)
    
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
            data : {competition_id:competition_id.value, _token:inputToken.value, avec_journees:true}
        }

        ajaxSelect(donneesRequeteAjax) // On récupère la liste des saisons
    })

    $('#saison_id').change(function(){
        matches.innerHTML = ''
        if(! saisons.value)
            return false;

        $.ajax({
            type: 'POST',
            url: "<?php echo route('equipe.matches') ?>",
            data:{equipe_id:"<?php echo $equipe->id ?>", saison_id:saisons.value, _token:inputToken.value},
            success:function(data){
                matches.innerHTML = data
            }
        })
    })
})
</script>
@endsection