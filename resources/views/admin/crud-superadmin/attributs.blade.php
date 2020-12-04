@extends('layouts.crud-superadmin')

@section('title', 'Gestion des attributs du Crud')

@section('content')
<div class="row card mx-0">
    <div class="card-header d-flex align-items-center">
       <span class="d-inline mr-3 crud-titre">{!! config('constant.boutons.database') !!} CrudAttributs :
          Ajouter</span>
       <a href="" class="back d-none d-sm-inline position-absolute text-decoration-none text-dark pr-3" style="right:0">
          {!! config('constant.boutons.retour') !!} retour
       </a>
    </div>

    <div class="text-danger text-right pr-3 pt-2">* champs obligatoires</div>

    <div class="card-body px-3">
       <form action="" method="POST" class="needs-validation" id="formulaire">
          @csrf
          <div class="form-row mb-3">
             <label for="crud_table_id">Table <span class="text-danger text-weight-bold">*</span> </label>
             <select name="crud_table_id" id="crud_table_id" class="form-control "
                data-msg="Veuillez faire un choix de <span class='text-danger font-italic'>Table</span>">
                <option value="">&nbsp;</option>
                @foreach ($crudTables as $table)
                    <option value="{{ $table->id }}">
                        {{ $table }}
                    </option>
                @endforeach
             </select>
          </div>
          <div class="form-row mb-3">
             <label for="attribut">Attribut <span class="text-danger text-weight-bold">*</span> </label>
             <select type="text" name="attribut" id="attribut" class="form-control "
                data-msg="Veuillez sélectionner un <span class='text-danger font-italic'>Attribut</span>.">
                <option value=""></option>
             </select>
          </div>
          <div class="form-row mb-3">
            <label for="attribut_crud_table_id">Table de réference de l'attribut</label>
             <select name="attribut_crud_table_id" id="attribut_crud_table_id" class="form-control input-optionnel ">
                <option value="">&nbsp;</option>
                @foreach ($tables as $table)
                    <option value="{{ $table->id }}">
                        {{ $table }}
                    </option>
                @endforeach
             </select>
          </div>
          <div class="form-row mb-3">
             <label for="label">Label à afficher pour l'attribut <span class="text-danger text-weight-bold">*</span></label>
             <input type="text" name="label" class="form-control " value="" pattern=".{3,50}" data-msg="Champ obligatoire. Entre 3 et 50 caractères requis.">
          </div>

          <div class="form-row mb-3">
             <div class="form-check form-check-inline">
                <input type="checkbox" name="optionnel" class="form-check-input input-optionnel ">
                <label class="form-check-label">Champ optionnel ?</label>
             </div>
          </div>

          <div class="form-row mb-3">
             <label for="data_msg">Message d'erreur</label>
             <textarea name="data_msg" class="form-control input-optionnel "
                rows="3">Le champ <span class='text-danger font-italic'>XXX</span></textarea>
          </div>

         <div class="form-row mb-3">
            <label for="vue_pos">Position (vue)</label>
            <input type="number" min="0" name="vue_pos" class="form-control" value="" data-msg="Merci de saisir un nombre supérieur à 0.">
         </div>

         <div class="form-row mb-3">
            <label for="edit_pos">Position (ajout/édition)</label>
            <input type="number" min="0" name="edit_pos" class="form-control" value="" data-msg="Merci de saisir un nombre supérieur à 0.">
         </div>

         <div class="form-row mb-3">
            <label for="input_type">Input type</label>
            <select name="input_type" class="form-control" data-msg="">
                <option value=""></option>
                <option value="number">number</option>
                <option value="date">date</option>
                <option value="checkbox">checkbox</option>
                <option value="text">text</option>
                <option value="email">email</option>
                <option value="file">file</option>
                <option value="hidden">hidden</option>
                <option value="image">image</option>
                <option value="password">password</option>
                <option value="tel">tel</option>
                <option value="time">time</option>
            </select>
         </div>

         <div class="form-row mb-3">
            <label for="label">Liste liée (si type select)</label>
            <select name="input_type" class="form-control" data-msg="">
                @foreach ($selects as $nom => $liste)
                    <option value="{{ $nom }}">{{ $nom }}</option>
                @endforeach
            </select>
         </div>

         <div class="form-row mb-3">
            <label for="pattern">Pattern</label>
            <input type="text" name="pattern" class="form-control" value="" pattern="" data-msg="">
         </div>

         <div class="form-row mb-3">
            <label for="min">Minimum</label>
            <input type="number" min="0" name="min" class="form-control" value="" data-msg="Merci de saisir un nombre supérieur à 0.">
         </div>

         <div class="form-row mb-3">
            <label for="max">Maximum</label>
            <input type="number" min="0" name="max" class="form-control" value="" data-msg="Merci de saisir un nombre supérieur à 0.">
         </div>

         <div class="form-row mt-3">
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
    $('#formulaire select').select2();
    retour()
    verifierMonFormulaireEnJS('formulaire')

    $('#crud_table_id').change(function(){
        var selectTables = qs('#crud_table_id')
        var selectAttributs = qs('#attribut')
        var inputToken = qs('input[name=_token]')

        if(selectTables.value){
            selectAttributs.innerHTML = ''
            fetch("<?php echo route('crud-superadmin.attributs.ajax') ?>", {
                method:'POST',
                body:JSON.stringify({crud_table_id:selectTables.value, _token:inputToken.value}),
                headers: {"Content-type": "application/json; charset=UTF-8"}
            })
            .then(res => res.json())
            .then(res => {
                if(res.length > 0){
                    selectAttributs.innerHTML = ''
                    let optionDefaut = dce('option')
                    optionDefaut.innerHTML = 'Sélectionner'
                    optionDefaut.value = ''
                    selectAttributs.appendChild(optionDefaut)

                    for(let i in res){
                        let option = dce('option')
                        option.value = res[i]
                        option.innerHTML = res[i]
                        selectAttributs.appendChild(option)
                    }
                }
            })
            .catch(err => cl(err))
        }
    })
})
</script>
@endsection
