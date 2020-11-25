@extends('layouts.' . request()->layout)

@section('title', $title)

@section('content')
<div class="row card mx-1">
    <div class="d-none">@csrf</div>
    <div class="card-header d-flex align-items-center">
        <span class="d-inline mr-3" style="font-size: 1.6em">{!! \Config::get('constant.boutons.database') !!} {{ $h1 }}</span>
        <a href="{{ $hrefs['create'] }}" class="text-decoration-none mr-1">
            <button class="btn-sm btn-primary">
                {!! \Config::get('constant.boutons.ajouter_cercle') !!}
                <span class="d-none d-md-inline">Ajouter</span>
            </button>
        </a>
        <button class="btn-sm btn-danger" id="multi-suppressions">
            {!! \Config::get('constant.boutons.supprimer') !!}
            <span class="d-none d-md-inline">Supprimer la sélection</span>
        </button>
    </div>
    <div class="col-12 card-body mt-2">
        <table id="tab" class="table table-hover table-sm text-center w-100">
            <thead>
                <tr>
                    <th scope="col" class="px-2"><input type="checkbox" id="tout" data-action="cocher"></th>
                    <th scope="col" class="text-left px-2">{{ Str::singular($table) }}</th>
                    <th scope="col" class="text-right px-3">actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($liste as $id => $ligne)
                    <tr>
                        <td class="px-2 align-middle"><input type="checkbox" id="check{{ $id }}" value="{{ $id }}"></td>
                        <td align="left" class="px-2 align-middle">{{ $ligne->crud_name }}</td>
                        <td class="text-right d-block">
                            <a href="{{ $ligne->href_show }}" title="Voir" class="text-decoration-none">
                                <button class="btn-sm btn-success">
                                    {!! \Config::get('constant.boutons.voir') !!}
                                    <span class="d-none d-lg-inline">Voir</span>
                                </button>
                            </a>
                            <a href="{{ $ligne->href_update }}" title="Editer" class="text-decoration-none">
                                <button class="btn-sm btn-info text-white">
                                    {!! \Config::get('constant.boutons.editer') !!}
                                    <span class="d-none d-lg-inline">Éditer</span>
                                </button>
                            </a>
                            <a href="" title="Supprimer" class="text-decoration-none">
                                <button class="btn-sm btn-danger">
                                    {!! \Config::get('constant.boutons.supprimer') !!}
                                    <span class="d-none d-lg-inline">Supprimer</span>
                                </button>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var idTable = 'tab'
    triDataTables(idTable) // Tri du tableau avec DataTables
    toutCocherDecocher(idTable) // Checkbox de suppressions multiples

    let urlSupprimer = "<?php echo $hrefs['delete-ajax'] ?>"
    let urlLister = "<?php echo $hrefs['index-ajax'] ?>"
    let token = qs('input[name=_token]').value
    let params = {
        urlSupprimer:urlSupprimer,
        idTable:idTable,
        urlLister:urlLister,
        token:token
    }
    supprimerUnElement(params)
    supprimerSelection(params) // Suppression de tous les élements cochés
})
</script>
@endsection
