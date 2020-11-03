@extends('layouts.gestion-site')

@section('title', $title)

@section('content')
<form action="" method="post" id="formulaire" class="px-3">
    @csrf
    <div class="row text-white bloc-match bloc-match-football py-3" style="height:auto;background-image: url('/storage/img/terrain-de-foot-6.jpg');background-size :cover;">
        <div class="col-12 text-center h4">
            {{ $champMatch->journee }}
        </div>
        <div class="col-4 d-flex justify-content-between align-items-center bloc-equipe-dom px-3">
            <div class="d-inline-flex align-items-center pr-2">
                <img src="{{ $champMatch->equipeDom->fanion() }}" alt="{{ $champMatch->equipeDom }}" class="fanion-match">
            </div>
            <div class="d-inline">
                {{ $champMatch->equipeDom }}
            </div>
        </div>
        <div class="col-4 d-flex bloc-score align-items-center justify-content-center">
            <input type="text" name="score_eq_dom" value="{{ $champMatch->score_eq_dom }}" class="px-2 text-center form-control input-optionnel" data-msg="Nombre de buts (domicile) obligatoire et inférieur à 30." pattern="^[1-2]\d{1}|\d{1}">
            <span class="p-2">-</span>
            <input type="text" name="score_eq_ext" value="{{ $champMatch->score_eq_ext }}" class="px-2 text-center form-control input-optionnel" data-msg="Nombre de buts (extérieur) obligatoire et inférieur à 30." pattern="^[1-2]\d{1}|\d{1}">
        </div>
        <div class="col-4 d-flex justify-content-between align-items-center bloc-equipe-ext px-3">
            <div class="d-inline">
                {{ $champMatch->equipeExt }}
            </div>
            <div class="d-inline-flex align-items-center">
                <img src="{{ $champMatch->equipeExt->fanion() }}" alt="{{ $champMatch->equipeExt }}" class="fanion-match">
            </div>
        </div>

        <div class="col-12 d-flex justify-content-center py-3">
            <div class="col-6 d-flex justify-content-end align-items-center">
                <label for="forfait_eq_dom" class="form-check-label pr-2">Forfait</label>
                <input class="" type="checkbox" value=""  name="forfait_eq_dom"
                    @if (old('forfait_eq_dom') OR $champMatch->forfait_eq_dom) checked @endif>
            </div>
            <div class="col-6 d-flex justify-content-start align-items-center pl-3">
                <input class="" type="checkbox" value=""  name="forfait_eq_ext"
                @if (old('forfait_eq_ext') OR $champMatch->forfait_eq_ext) checked @endif>
                <label for="forfait_eq_ext" class="form-check-label pl-2">Forfait</label>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-center pb-3">
            <div class="col-6 d-flex justify-content-end align-items-center">
                <label for="penalite_eq_dom" for="penalite_eq_dom" class="form-check-label pr-2">Pénalité</label>
                <input class="" type="checkbox" value=""  name="penalite_eq_dom"
                    @if (old('penalite_eq_dom') OR $champMatch->penalite_eq_dom) checked @endif>
            </div>
            <div class="col-6 d-flex justify-content-start align-items-center pl-3">
                <input class="" type="checkbox" value=""  name="penalite_eq_ext"
                @if (old('penalite_eq_ext') OR $champMatch->penalite_eq_ext) checked @endif>
                <label for="penalite_eq_ext" class="form-check-label pl-2">Pénalité</label>
            </div>
        </div>

        <div class="form-row col-12 justify-content-center">
            <div class="col-4 d-flex justify-content-center">
                <div class="form-group justify-content-center align-items-center">
                    <label for="date">Date</label>
                    <input class="form-control input-optionnel" type="date" name="date" data-msg="Merci de saisir une date valide." value="{{ $champMatch->date('Y-m-d') }}">
                </div>
            </div>
        </div>

        <div class="form-row col-12 justify-content-center pb-3">
            <div class="col-4 d-flex justify-content-center">
                <div class="form-group justify-content-center align-items-center">
                    <label for="heure">Heure</label>
                    <input class="form-control input-optionnel" type="time" name="heure" pattern="\d{2}:\d{2}" data-msg="Format attendu : hh:mm" value="{{ $champMatch->heure() }}">
                </div>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-center align-items-center">
            <div class="col-lg-6 alert alert-danger text-dark p-3 d-none" id="messageErreur"></div>
        </div>

        <div class="col-12 text-center pb-3">
            <button class="btn btn-outline-danger">Valider</button>
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
