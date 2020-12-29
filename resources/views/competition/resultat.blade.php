@extends('layouts.competition')

@section('title', $match['title'])

@section('content')
<form action="" method="post" id="formulaire">
    @csrf
    <div class="row text-white bloc-match bloc-match-football py-4 rounded mx-0">
        <div class="row mx-0 col-4 d-flex justify-content-between align-items-center bloc-equipe-dom">
            <div class="col-lg-4 d-lg-inline py-2 px-0">
                <img src="{{ $match['fanion_equipe_dom'] }}" alt="{{ $match['equipe_dom']->nom }}" class="fanion-match">
            </div>
            <div class="equipe-domicile col-lg-8 d-lg-inline py-2 px-0">
                {{ $match['equipe_dom']->nom }}
            </div>
        </div>
        <div class="col-4 bloc-score d-flex align-items-center justify-content-center">
            <input type="text" name="score_eq_dom" value="{{ $match['score_eq_dom'] }}" class="@error('score_eq_dom') is-invalid @enderror px-2 rounded text-center font-weight-bold" data-msg="Merci de saisir un score valide." pattern="^(1|2)\d{1}|\d{1}">
            <span class="p-2">-</span>
            <input type="text" name="score_eq_ext" value="{{ $match['score_eq_ext'] }}" class="@error('score_eq_ext') is-invalid @enderror px-2 rounded text-center font-weight-bold" data-msg="Merci de saisir un score valide." pattern="^(1|2)\d{1}|\d{1}">
        </div>
        <div class="row mx-0 col-4 d-flex justify-content-between align-items-center bloc-equipe-ext pl-2">
            <div class="equipe-exterieur col-lg-8 d-lg-inline order-2 order-lg-1 py-2 px-0">
                {{ $match['equipe_ext']->nom }}
            </div>
            <div class="col-lg-4 d-lg-inline order-1 order-lg-2 py-2 px-0">
                <img src="{{ $match['fanion_equipe_ext'] }}" alt="{{ $match['equipe_ext']->nom }}" class="fanion-match">
            </div>
        </div>

        {{-- <div class="col-12 d-flex justify-content-center p-3">
            <textarea name="note" class="note form-control input-optionnel" cols="2" placeholder="Laisser un commentaire."></textarea>
        </div> --}}

        <div class="col-12 text-center p-3">
            <button class="btn btn-outline-danger">Valider</button>
        </div>

        <div class="col-12 d-flex justify-content-center align-items-center">
            <div class="col-lg-6 alert alert-danger text-dark p-3 d-none" id="messageErreur"></div>
        </div>

        <div class="col-12 text-center p-3">
            <div class="col-12">
                Le {{ $match['date_format'] }}
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
$(document).ready(function(){
    //
    verifierMonFormulaireEnJS('formulaire')
})
</script>
@endsection
