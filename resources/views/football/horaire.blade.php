@extends('layouts.football')

@section('content')
<div class="my-3 mx-3">
    <form action="" method="post" id="formulaire">
        @csrf
        <div class="row text-white bloc-match bloc-match-football py-3" style="height:350px;background-image: url('/storage/img/terrain-de-foot-6.jpg');background-size :cover;background-color:#fff">
            <div class="col-5 d-flex justify-content-between align-items-center bloc-equipe-dom">
                <div class="d-inline-flex align-items-center pr-2">
                    <img src="{{ $match['fanionDom'] }}" alt="{{ $match['equipeDom'] }}" class="fanion-match">
                </div>
                <div class="d-inline">
                    {{ $match['equipeDom'] }}
                </div>
            </div>
            <div class="col-2 bloc-score d-flex align-items-center justify-content-around">
                <span class="w-100 text-center">{!! $match['score'] !!}</span>
            </div>
            <div class="col-5 d-flex justify-content-between align-items-center bloc-equipe-ext pl-2">
                <div class="d-inline">
                    {{ $match['equipeExt'] }}
                </div>
                <div class="d-inline-flex align-items-center">
                    <img src="{{ $match['fanionExt'] }}" alt="{{ $match['equipeExt'] }}" class="fanion-match">
                </div>
            </div>

            <div class="form-row col-12 pb-3">
                <div class="col-12 d-flex justify-content-center">
                    <input class="text-center form-control col-6 col-md-4 col-lg-3" type="date" name="date" data-msg="Merci de saisir une date valide." value="{{ $match['date'] }}">
                </div>
                <div class="col-12 d-flex justify-content-center">
                    <input class="text-center form-control col-6 col-md-4 col-lg-3 input-optionnel" type="time" name="heure" pattern="\d{2}:\d{2}" data-msg="Format possible : hh:mm" value="{{ $match['heure'] }}">
                </div>
            </div>

            <div class="col-12 text-center pb-3">
                <button class="btn btn-info text-white">Valider</button>
            </div>

            <div class="col-12 d-flex justify-content-center align-items-center">
                <div class="col-lg-6 alert alert-danger text-dark p-3 d-none" id="messageErreur"></div>
            </div>

            <div class="col-12 text-center">
                <div class="col-12">
                    {{ $match['championnat'] }} : {{ $match['journee'] }}
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // retour()
    verifierMonFormulaireEnJS('formulaire')
})
</script>
@endsection
