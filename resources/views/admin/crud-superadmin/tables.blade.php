@extends('layouts.crud-superadmin')

@section('title', 'Gestion des tables "crudables"')

@section('content')
<div class="row card">
    <div class="card-header d-flex align-items-center">
       <span class="d-inline mr-3 crud-titre">{!! config('constant.boutons.database') !!} CrudTables - Tables "crudables"</span>
       <a href="" class="back d-none d-sm-inline position-absolute text-decoration-none text-dark pr-3" style="right:0">{!! config('constant.boutons.retour') !!} retour</a>
    </div>

    <div class="card-body px-3">
        <input type="checkbox" data-action="cocher" id="tout" title="Tout cocher/décocher">{{-- Tout cocher/décocher --}}
       <form action="" method="POST" class="needs-validation" id="formulaire">
          @csrf
          <div class="form-row mb-3">
              @foreach ($crudTables as $crudTable)
                <div class="col-6 col-md-4 form-check px-5 py-2">
                    <input type="checkbox" @if ($crudTable->crudable) checked @endif name="{{ $crudTable->id }}" class="form-check-input" @if(in_array($crudTable->nom, config('constant.tables-non-crudables'))) disabled @endif>
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
    $('#formulaire select').select2();
    retour()
    toutCocherDecocher('formulaire')
})
</script>
@endsection
