@extends('layouts.crud-superadmin')

@section('title', 'Gestion des tables "crudables"')

@section('content')
<div class="row card mx-0">
    <div class="card-header d-flex align-items-center">
       <span class="d-inline mr-3 crud-titre">{!! config('listes.boutons.database') !!} CrudTables - Tables "crudables"</span>
       <button class="btn-sm btn-danger" id="vider-cache">
        {!! \Config::get('listes.boutons.supprimer') !!}
        <span class="d-none d-md-inline">Vider le cache</span>
    </button>
    </div>

    <div class="card-body px-3">
        <input type="checkbox" data-action="cocher" id="tout" title="Tout cocher/décocher">{{-- Tout cocher/décocher --}}
       <form action="" method="POST" class="needs-validation" id="formulaire">
          @csrf
          <div class="form-row mb-3">
              @foreach ($crudTables as $crudTable)
                <div class="col-6 col-md-4 form-check px-5 py-2">
                    <input type="checkbox" @if ($crudTable->crudable) checked @endif name="{{ $crudTable->id }}" class="form-check-input" @if(in_array($crudTable->nom, config('listes.tables-non-crudables'))) disabled @endif>
                    <label class="form-check-label">{{ $crudTable->nom }}</label>
                </div>
              @endforeach
          </div>

          <div class="form-row mb-3 pl-5">
            <input type="checkbox" name="maj" class="form-check-input">
            <label class="form-check-label text-danger">Mettre à jour la liste des tables</label>
          </div>

          <div class="form-row justify-content-center">
             <button class="btn btn-primary px-5">Mettre à jour</button>
          </div>
       </form>
    </div>
 </div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    toutCocherDecocher('formulaire')

    $('#vider-cache').on('click', function () {
        let _token = qs('input[name=_token]').value
        $.ajax({
            type: 'POST',
            url: "<?php echo route('cache-flush') ?>",
            data: {_token}
        });
    })
})
</script>
@endsection
