@extends('layouts.gestion-site')

@section('title', $h1)

@section('content')
<div class="row card mx-1">
    <div class="card-header d-flex align-items-center">
        <span class="d-inline mr-3" style="font-size: 1.6em"><i class="fas fa-database"></i> {{ $h1 }}</span>
        <a href="" class="back d-none d-sm-inline position-absolute text-decoration-none text-dark pr-3" style="right:0">
            <i class="fas fa-long-arrow-alt-left"></i> retour
        </a>
    </div>

    <div class="text-danger text-right pr-3 pt-2">* champs obligatoires</div>

    <div class="card-body">
        <form action="" method="POST" class="needs-validation w-100 d-flex flex-wrap" id="formulaire">
            @csrf

            <div class="col-12 form-row justify-content-center mb-3">
                <div class="col-md-6 d-flex flex-wrap">
                    <label for="sport_id">Choix du sport</label>
                    <select name="sport_id" id="sport_id" class="form-control @error('sport_id') is-invalid @enderror">
                        <option value="">&nbsp;</option>
                        @foreach ($sports as $sport)
                            <option
                                @if (old('id') == $sport->id) selected @endif
                                value="{{ $sport->id }}">{{ $sport->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('sport_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-12 form-row justify-content-center mb-3">
                <div class="col-md-6 d-flex flex-wrap">
                    <label for="competition_id">Choix du championnat</label>
                    <select name="competition_id" id="competition_id" class="form-control @error('competition_id') is-invalid @enderror">
                        <option value="">&nbsp;</option>
                    </select>
                    @error('competition_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-12 form-row justify-content-center mb-3">
                <div class="col-md-6 d-flex flex-wrap">
                    <label for="saison_id">Choix de la saison</label>
                    <select name="saison_id" id="saison_id" class="form-control @error('saison_id') is-invalid @enderror">
                        <option value="">&nbsp;</option>
                    </select>
                    @error('saison_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-12 form-row mt-3">
                <div class="col-12 alert alert-danger text-dark px-3 d-none" id="messageErreur"></div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="/js/journees-multiples-ajout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var selects = '#sport_id, #competition_id, #saison_id'
    $(selects).select2();
    retour()
    verifierMonFormulaireEnJS('formulaire')

    let params = {
        selectSports: qs('#sport_id'),
        selectCompetitions: qs('#competition_id'),
        selectSaisons: qs('#saison_id'),
        inputToken: qs('input[name=_token]'),
        method: 'POST',
        urlAjaxCompetitions:"<?php echo route('ajax', ['table' => 'competitions']) ?>",
        urlAjaxSaisons:"<?php echo route('ajax', ['table' => 'saisons']) ?>",
        urlAjaxUrlEditMultiJournees:"<?php echo route('journees.ajax-url-editer') ?>"
    }
   journeesMultiples(params)
})
</script>
@endsection
