<?php
use App\Championnat;
use App\Equipe;
use App\ChampJournee;
use App\ChampSaison;
?>

@extends('layouts.gestion-site')

@section('title', $h1)

@section('content')
<div class="row card">
    <div class="card-header d-flex align-items-center">
        <span class="d-inline mr-3" style="font-size: 1.3em"><i class="fas fa-database"></i> {{ $h1 }}</span>

        <a href="" class="back d-none d-sm-inline position-absolute text-decoration-none text-dark pr-3" style="right:0"><i class="fas fa-long-arrow-alt-left"></i> retour</a>
    </div>

    <div class="text-danger text-right pr-3 pt-2">* champs obligatoires</div>

    <div class="card-body">
        <form action="" method="POST" class="needs-validation" id="formulaire">
            @csrf
            <div class="form-row mb-3">
                <label for="championnat_id">Choix du championnat</label>
                <select name="championnat_id" id="championnat_id" class="form-control @error('championnat_id') is-invalid @enderror">
                    <option value=""></option>
                    @foreach ($championnats as $championnat)
                        <option
                            @if ((old('championnat_id') == $championnat->id) OR ($championnatId == $championnat->id)) selected @endif
                            value="{{ $championnat->id }}">{{ $championnat }}
                        </option>
                    @endforeach
                </select>
                @error('championnat_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row mb-3">
                <label for="champ_saison_id">Choix de la saison</label>
                <select name="champ_saison_id" id="champ_saison_id" class="form-control @error('champ_saison_id') is-invalid @enderror">
                    <option value=""></option>
                    @foreach ($saisons as $saison)
                        <option
                            @if ((old('champ_saison_id') == $saison->id) OR ($saisonId == $saison->id)) selected @endif
                            value="{{ $saison->id }}">{{ $saison }}
                        </option>
                    @endforeach
                </select>
                @error('champ_saison_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row justify-content-center">
                <div class="col-6 mb-3">
                    <label for="champ_journee_id">Journée</label>
                    <select name="champ_journee_id" id="champ_journee_id" class="form-control @error('champ_journee_id') is-invalid @enderror">
                        <option value=""></option>
                        @foreach ($journees as $journee)
                            <option
                                @if ((old('champ_journee_id') == $journee->id) OR ($champMatch->champ_journee_id == $journee->id)) selected @endif
                                value="{{ $journee->id }}">
                                {{ $journee->numero }}
                            </option>
                        @endforeach
                    </select>
                    @error('champ_journee_id')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6 mb-3">
                    <label for="date">Date</label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') ?? $champMatch->date }}">
                    @error('date')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row justify-content-center">
                <div class="col-6 mb-3">
                    <label for="nb_modifs">Nombre de modifs</label>
                    <input disabled type="text" name="nb_modifs" class="form-control @error('nb_modifs') is-invalid @enderror" value="{{ old('nb_modifs') ?? $champMatch->nb_modifs }}">
                    @error('nb_modifs')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6 mb-3">
                    <label for="heure">Heure</label>
                    <input type="text" name="heure" class="form-control @error('heure') is-invalid @enderror" value="{{ old('heure') ?? $champMatch->heure }}" placeholder="15:00">
                    @error('heure')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row mb-3">
                <label for="equipe_id_dom">Domicile</label>
                <select name="equipe_id_dom" id="equipe_id_dom" class="form-control @error('equipe_id_dom') is-invalid @enderror">
                    <option value=""></option>
                    @foreach ($equipes as $equipe)
                        <option
                            @if ((old('equipe_id_dom') == $equipe->id) OR ($champMatch->equipe_id_dom == $equipe->id)) selected @endif
                            value="{{ $equipe->id }}">{{ $equipe }}
                        </option>
                    @endforeach
                </select>
                @error('equipe_id_dom')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check d-flex justify-content-center mb-3">
                <div class="col-6">
                    <input class="form-check-input" type="checkbox" value=""  name="forfait_eq_dom" id="defaultCheck1"
                        @if ((old('forfait_eq_dom')) OR ($champMatch->forfait_eq_dom == 1)) checked @endif>
                    <label class="form-check-label" for="defaultCheck1">Forfait dom.?</label>
                </div>
                <div class="col-6">
                    <input class="form-check-input" type="checkbox" value=""  name="penalite_eq_dom" id="defaultCheck1"
                        @if ((old('penalite_eq_dom')) OR ($champMatch->penalite_eq_dom == 1)) checked @endif>
                    <label class="form-check-label" for="defaultCheck1">Pénalité dom.?</label>
                </div>
            </div>

            <div class="form-row justify-content-center">
                <div class="col-6 mb-3">
                    <label for="buts_equipe1">Buts équipe 1</label>
                    <input type="number" name="buts_equipe1" class="form-control input-optionnel @error('buts_equipe1') is-invalid @enderror" value="{{ old('buts_equipe1') ?? $champMatch->buts_equipe1 }}" min="0" max="255">
                    @error('buts_equipe1')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6 mb-3">
                    <label for="buts_equipe2">Buts équipe 2</label>
                    <input type="number" name="buts_equipe2" class="form-control input-optionnel @error('buts_equipe2') is-invalid @enderror" value="{{ old('buts_equipe2') ?? $champMatch->buts_equipe2 }}" min="0" max="255">
                    @error('buts_equipe2')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-row mb-3">
                <label for="equipe_id_ext">Extérieur</label>
                <select name="equipe_id_ext" id="equipe_id_ext" class="form-control @error('equipe_id_ext') is-invalid @enderror">
                    <option value=""></option>
                    @foreach ($equipes as $equipe)
                        <option
                            @if ((old('equipe_id_ext') == $equipe->id) OR ($champMatch->equipe_id_ext == $equipe->id)) selected @endif
                            value="{{ $equipe->id }}">{{ $equipe }}
                        </option>
                    @endforeach
                </select>
                @error('equipe_id_ext')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check d-flex justify-content-center mb-3">
                <div class="col-6">
                    <input class="form-check-input" type="checkbox" value=""  name="forfait_eq_ext" id="defaultCheck1"
                        @if ((old('forfait_eq_ext')) OR ($champMatch->forfait_eq_ext == 1)) checked @endif>
                    <label class="form-check-label" for="defaultCheck1">Forfait ext.?</label>
                </div>
                <div class="col-6">
                    <input class="form-check-input" type="checkbox" value=""  name="penalite_eq_ext" id="defaultCheck1"
                        @if ((old('penalite_eq_ext')) OR ($champMatch->penalite_eq_ext == 1)) checked @endif>
                    <label class="form-check-label" for="defaultCheck1">Pénalité ext.?</label>
                </div>
            </div>

            <div class="form-row mb-3">
                <label for="terrain_id">Terrain</label>
                <select name="terrain_id" id="terrain_id" class="form-control @error('terrain_id') is-invalid @enderror">
                    <option value=""></option>
                    @foreach ($terrains as $terrain)
                        <option
                            @if ((old('terrain_id') == $terrain->id) OR ($champMatch->terrain_id == $terrain->id)) selected @endif
                            value="{{ $terrain->id }}">{{ $terrain }}
                        </option>
                    @endforeach
                </select>
                @error('terrain_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 form-row mt-3">
                <div class="col-12 alert alert-danger text-dark px-3 d-none" id="messageErreur"></div>
             </div>

             <div class="form-row justify-content-center">
                <button class="btn btn-primary px-5">Valider</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#champ_journee_id, #terrain_id, #champ_saison_id, #equipe_id_dom, #equipe_id_ext, #championnat_id').select2();
    verifierMonFormulaireEnJS('formulaire')

    var selectChampionnats = qs('#championnat_id')
    var selectSaisons = qs('#champ_saison_id')
    var selectEquipes1 = qs('#equipe_id_dom')
    var selectEquipes2 = qs('#equipe_id_ext')
    var inputToken = qs('input[name=_token]')
    var method = 'POST'

    $('#champ_saison_id').change(function(){
        let donneesRequeteAjax = {
            url : "<?php echo route('ajax', ['table' => 'champ-journees']) ?>",
            texteOptionDefaut : 'Choix de la journée',
            method : method,
            idSelect : 'champ_journee_id',
            data : {champ_saison_id:selectSaisons.value, _token:inputToken.value}
        }
        ajaxSelect(donneesRequeteAjax)
    })
})
</script>
@endsection
