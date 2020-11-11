@extends('layouts.competition')

@section('content')
<form action="" method="post" id="formulaire" class="my-3">
    @csrf
    <div class="row text-white bloc-match bloc-match-football rounded mx-0 py-4">
        <div class="row mx-0 col-4 d-flex justify-content-between align-items-center bloc-equipe-dom">
            <div class="col-lg-4 d-lg-inline py-2 px-0">
                <img src="{{ $match['fanionDom'] }}" alt="{{ $match['equipeDom'] }}" class="fanion-match">
            </div>
            <div class="equipe col-lg-8 d-lg-inline py-2 px-0">
                {{ $match['equipeDom'] }}
            </div>
        </div>
        <div class="col-4 bloc-score d-flex align-items-center justify-content-center">
            <input type="text" name="score_eq_dom" value="{{ $match['scoreEqDom'] }}" class="@error('score_eq_dom') is-invalid @enderror px-2 rounded text-center font-weight-bold" data-msg="Champ obligatoire et inférieur à 30." pattern="^(1|2)\d{1}|\d{1}">
            <span class="p-2">-</span>
            <input type="text" name="score_eq_ext" value="{{ $match['scoreEqExt'] }}" class="@error('score_eq_ext') is-invalid @enderror px-2 rounded text-center font-weight-bold" data-msg="Champ obligatoire et inférieur à 30." pattern="^(1|2)\d{1}|\d{1}">
        </div>
        <div class="row mx-0 col-4 d-flex justify-content-between align-items-center bloc-equipe-ext pl-2">
            <div class="equipe col-lg-8 d-lg-inline order-2 order-lg-1 py-2 px-0">
                {{ $match['equipeExt'] }}
            </div>
            <div class="col-lg-4 d-lg-inline order-1 order-lg-2 py-2 px-0">
                <img src="{{ $match['fanionExt'] }}" alt="{{ $match['equipeExt'] }}" class="fanion-match">
            </div>
        </div>

        <div class="col-12 d-flex justify-content-center p-3">
            <textarea name="note" class="note form-control input-optionnel" cols="2" placeholder="Laisser un commentaire."></textarea>
        </div>

        <div class="col-12 text-center p-3">
            <button class="btn btn-outline-danger">Valider</button>
        </div>

        <div class="col-12 d-flex justify-content-center align-items-center">
            <div class="col-lg-6 alert alert-danger text-dark p-3 d-none" id="messageErreur"></div>
        </div>

        <div class="col-12 text-center p-3">
            <div class="col-12">
                Le {{ $match['dateFormat'] }}
            </div>
            <div class="col-12">
                {{ $match['competition'] }} : {{ $match['journee'] }}
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
