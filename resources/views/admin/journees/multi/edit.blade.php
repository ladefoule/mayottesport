@extends('layouts.admin')

@section('title', $h1)

@section('content')
<div class="row card mx-0">
    <div class="card-header d-flex align-items-center">
        <span class="d-inline mr-3 crud-titre">{!! config('constant.boutons.database') !!} {{ $h1 }}</span>
        <a href="{{ route('journees.multi.show', ['id' => $saisonId]) }}" title="Editer" class="text-decoration-none">
            <button class="btn-sm btn-success text-white">
                <?= \Config::get('constant.boutons.voir') ?>
                <span class="d-none d-lg-inline ml-1">Voir</span>
            </button>
        </a>
        <a href="" class="back d-none d-sm-inline position-absolute text-decoration-none text-dark pr-3" style="right:0">
            {!! config('constant.boutons.retour') !!} retour
        </a>
    </div>

    <div class="card-body">
        <div class="text-danger text-right pr-3">* champs obligatoires</div>
        <form action="" method="POST" class="needs-validation w-100 d-flex flex-wrap" id="formulaire">
            @csrf

            <div class="col-12 form-row justify-content-center mb-3">
                <div class="col-md-6 d-flex flex-wrap">
                    <label>Saison</label>
                    <input type="text" value="{{ $saison }}" class="form-control" disabled>
                </div>
            </div>

            <div class="col-12 form-row d-flex justify-content-center">
                <div class="col-1 offset-10 offset-md-10 offset-lg-8 text-center p-1">
                    <i class="far fa-trash-alt" title="Tout cocher/décocher"></i>
                    <input type="checkbox" data-action="cocher" id="tout">
                </div>
            </div>

            <div class="col-12 form-row d-flex justify-content-center">
                @for ($i = 1; $i <= $nbJournees; $i++)
                    <?php
                        $journee = $listeJournees[$i] ?? '';
                        $date = $journee->date ?? date('Y-m-d');

                        $nameJourneeNumero = 'numero'.$i;
                        $nameJourneeDate = 'date'.$i;
                        $nameJourneeId = 'id'.$i;
                        $nameJourneeDelete = 'delete'.$i;
                    ?>
                    <div class="col-4 col-md-4 col-lg-3 mb-3">
                        <label for="">Journée <span class="text-danger text-weight-bold">*</span></label>
                            <input type="number" min="1" max="100" data-msg="Tous les champs <span class='text-danger font-italic'>Journée</span> sont obligatoires et leur contenu doit être entre 1 et 100."
                            name="{{$nameJourneeNumero}}" class="form-control @error($nameJourneeNumero) is-invalid @enderror" value="{{ old($nameJourneeNumero) ?? $i }}">
                        @error($nameJourneeNumero)
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-6 col-md-6 col-lg-5 mb-3">
                        <label for="{{$nameJourneeDate}}">Date</label>
                        <input type="date"  data-msg="Tous les champs <span class='text-danger font-italic'>Date</span> sont obligatoires et doivent contenir une date valide."
                            name="{{$nameJourneeDate}}" class="form-control @error($nameJourneeDate) is-invalid @enderror" value="{{ old($nameJourneeDate) ?? $date }}">
                        @error($nameJourneeDate)
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-1 d-flex align-items-center justify-content-center ml-2">
                        <input type="checkbox" class="" name="{{ $nameJourneeDelete }}" @if(! $journee) disabled @endif>
                    </div>
                    <input type="hidden" name="{{ $nameJourneeId }}" value="{{ $journee->id ?? 0 }}">
                @endfor
            </div>

            <input type="hidden" name="saison_id" value="{{ $saisonId }}">

            <div class="col-12 form-row mt-3">
                <div class="mt-3 col-12 alert alert-danger text-dark px-3 d-none" id="messageErreur"></div>
            </div>

            <div class="col-12 form-row justify-content-center">
                <button class="btn btn-primary px-5">Valider</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
   verifierMonFormulaireEnJS('formulaire')
   toutCocherDecocher('formulaire')

})
</script>
@endsection
