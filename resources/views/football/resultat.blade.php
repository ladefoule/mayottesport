@extends('layouts.football')

@section('content')
<form action="" method="post" id="formulaire">
    @csrf
    <div class="row text-white bloc-match bloc-match-football py-3" style="height:350px;background-image: url('/storage/img/terrain-de-foot-6.jpg');background-size :cover;background-color:#fff">
        <div class="col-4 d-flex justify-content-between align-items-center bloc-equipe-dom">
            <div class="d-inline-flex align-items-center pr-2">
                <img src="{{ $match['fanionDom'] }}" alt="{{ $match['equipeDom'] }}" class="fanion-match">
            </div>
            <div class="d-inline">
                {{ $match['equipeDom'] }}
            </div>
        </div>
        <div class="col-4 bloc-score d-flex align-items-center justify-content-center">
            <input type="text" name="score_eq_dom" value="{{ $match['scoreEqDom'] }}" class="form-control @error('score_eq_dom') is-invalid @enderror w-25 px-2 text-center font-weight-bold" data-msg="Champ obligatoire et inférieur à 30." pattern="^(1|2)+\d{1}|\d{1}">
            <span class="p-2">-</span>
            <input type="text" name="score_eq_ext" value="{{ $match['scoreEqExt'] }}" class="form-control @error('score_eq_ext') is-invalid @enderror w-25 px-2 text-center font-weight-bold" data-msg="Champ obligatoire et inférieur à 30." pattern="^(1|2)+\d{1}|\d{1}">
        </div>
        <div class="col-4 d-flex justify-content-between align-items-center bloc-equipe-ext pl-2">
            <div class="d-inline">
                {{ $match['equipeExt'] }}
            </div>
            <div class="d-inline-flex align-items-center">
                <img src="{{ $match['fanionExt'] }}" alt="{{ $match['equipeExt'] }}" class="fanion-match">
            </div>
        </div>

        <div class="col-12 d-flex justify-content-center pb-3">
            <textarea name="note" class="form-control w-25 input-optionnel" placeholder="Laisser un commentaire" rows="1"></textarea>
        </div>

        <div class="col-12 text-center pb-3">
            <button class="btn btn-outline-danger">Valider</button>
        </div>

        <div class="col-12 d-flex justify-content-center align-items-center">
            <div class="col-lg-6 alert alert-danger text-dark p-3 d-none" id="messageErreur"></div>
        </div>

        <div class="col-12 text-center">
            <div class="col-12">
                Le {{ $match['dateFormat'] }}
            </div>
            <div class="col-12">
                {{ $match['championnat'] }} : {{ $match['journee'] }}
            </div>
        </div>
    </div>
</form>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // retour()
    verifierMonFormulaireEnJS('formulaire')
})
</script>
@endsection
