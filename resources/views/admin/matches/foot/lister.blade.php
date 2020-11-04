<?php
use App\Equipe;
use App\FootScore;
use App\ChampJournee;
?>

@extends('layouts.gestion-site')

@section('title', $title)

@section('content')
<div class="row card">
    <div class="card-header d-flex align-items-center">
        <span class="d-inline mr-3" style="font-size: 1.6em"><i class="fas fa-database"></i> {{ $h1 }}</span><br>
        <a href="{{ route('matches.foot.ajouter') }}" class="text-decoration-none mr-1">
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
    <div class="col-12 card-body mt-2">
        <form action="" method="POST" class="needs-validation p-3 w-100" id="formAjax">
            @csrf
            <div class="form-row justify-content-center">
                <div class="col-md-6 col-lg-4 mb-3">
                    <label for="championnat_id">Championnat</label>
                    <select name="championnat_id" id="championnat_id" class="form-control">
                        <option value=""></option>
                        @foreach ($championnats as $championnat)
                            <option
                                value="{{ $championnat->id }}">{{ $championnat->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <label for="saison_id">Saison</label>
                    <select name="saison_id" id="saison_id" class="form-control">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-row justify-content-center">
                <div class="col-md-6 col-lg-4 mb-3">
                    <label for="journee_id">Journée</label>
                    <select name="journee_id" id="journee_id" class="form-control">
                        <option value=""></option>
                    </select>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <label for="equipe_id">Équipe</label>
                    <select name="equipe_id" id="equipe_id" class="form-control">
                        <option value=""></option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="col-12 center-children">
        <table id="tab_matches" class="table table-striped mt-3 text-center">
            <thead>
                <tr>
                    <th scope="col"><input type="checkbox" id="tout" data-action="cocher"></th>
                    <th scope="col">J.</th>
                    <th scope="col">Rencontre</th>
                    <th scope="col" class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script src="/js/champ-matches-liste.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var selects = '#saison_id, #championnat_id, #journee_id, #equipe_id'
    $(selects).select2();
    var idTable = 'tab_matches'
    var inputToken = qs('input[name=_token]')
    triDataTables(idTable)
    supprimerUnElement(idTable)
    toutCocherDecocher(idTable)

    urls = {
        'saisons' : "<?php echo route('ajax', ['table' => 'saisons']) ?>",
        'journees' : "<?php echo route('ajax', ['table' => 'journees']) ?>",
        'equipes' : "<?php echo route('ajax', ['table' => 'equipes']) ?>",
        'matches' : "<?php echo route('ajax', ['table' => 'matches']) ?>"
    }
    listeChampMatches(idTable, qsa(selects), urls)
})
</script>
@endsection
