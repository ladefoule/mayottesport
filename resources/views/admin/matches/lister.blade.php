<?php
use App\Equipe;
use App\FootScore;
use App\ChampJournee;
?>

@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="row card mx-1">
    <div class="card-header d-flex align-items-center">
        <span class="d-inline mr-3" style="font-size: 1.6em"><i class="fas fa-database"></i> {{ $h1 }}</span><br>
        <a href="{{ route('crud.create', ['table' => 'matches']) }}" class="text-decoration-none mr-1">
            <button class="btn-sm btn-success">
                <i class="fas fa-plus-circle"></i>
                <span class="d-none d-xl-inline"> Ajouter un match</span>
            </button>
        </a>
        <button class="btn-sm btn-danger" id="supprimerSelection">
            <i class="fas fa-trash-alt"></i>
            <span class="d-none d-xl-inline"> Supprimer la sélection</span>
        </button>
    </div>
    <div class="col-12 card-body">
        <form action="" method="POST" class="needs-validation p-3 w-100" id="formAjax">
            @csrf
            <div class="form-row d-flex justify-content-center px-2">
                <div class="d-flex flex-wrap col-md-6 mb-3">
                    <label for="sport_id">Sport</label>
                    <select name="sport_id" id="sport_id" class="form-control">
                        <option value=""></option>
                        @foreach ($sports as $sport)
                            <option
                                value="{{ $sport->id }}">{{ $sport->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row d-flex justify-content-center px-2">
                <div class="d-flex flex-wrap col-md-6 mb-3">
                    <label for="competition_id">Compétition</label>
                    <select name="competition_id" id="competition_id" class="form-control">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-row justify-content-center px-2">
                <div class="d-flex flex-wrap col-md-6 mb-3">
                    <label for="saison_id">Saison</label>
                    <select name="saison_id" id="saison_id" class="form-control">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-row justify-content-center px-2">
                <div class="d-flex flex-wrap col-md-6 mb-3">
                    <label for="journee_id">Journée</label>
                    <select name="journee_id" id="journee_id" class="form-control">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-row justify-content-center px-2">
                <div class="d-flex flex-wrap col-md-6 mb-3">
                    <label for="equipe_id">Équipe</label>
                    <select name="equipe_id" id="equipe_id" class="form-control">
                        <option value=""></option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="col-12 center-children pb-3">
        <table id="tab_matches" class="table table-striped mt-3 text-center">
            <thead>
                <tr>
                    <th class="px-1" scope="col"><input type="checkbox" id="tout" data-action="cocher"></th>
                    <th class="px-1" scope="col">J.</th>
                    <th class="px-1" scope="col">Rencontre</th>
                    <th class="px-1 text-right" scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script src="/js/matches-liste.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var selects = '#sport_id, #saison_id, #competition_id, #journee_id, #equipe_id'
    $(selects).select2();
    var idTable = 'tab_matches'
    var token = qs('input[name=_token]').value
    var urlSupprimer = "<?php echo route('crud.delete-ajax', ['table' => 'matches']) ?>"
    var urlLister = ""
    triDataTables(idTable)
    toutCocherDecocher(idTable)

    let params = {urlSupprimer,idTable,urlLister,token}
    supprimerUnElement(params)
    supprimerSelection(params) // Suppression de tous les élements cochés

    urls = {
        'competitions' : "<?php echo route('ajax', ['table' => 'competitions']) ?>",
        'saisons' : "<?php echo route('ajax', ['table' => 'saisons']) ?>",
        'journees' : "<?php echo route('ajax', ['table' => 'journees']) ?>",
        'equipes' : "<?php echo route('ajax', ['table' => 'equipes']) ?>",
        'matches' : "<?php echo route('ajax', ['table' => 'matches']) ?>",
        // 'supprimer' : urlSupprimer
    }
    listeMatches(idTable, qsa(selects), urls)
})
</script>
@endsection
