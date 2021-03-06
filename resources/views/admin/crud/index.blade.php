@extends('layouts.' . request()->layout)

@section('title', $title)

@section('content')
<div class="row card mx-0 my-3">
    <div class="d-none">@csrf</div>
    <div class="card-header d-flex align-items-center px-2">
        <span class="d-inline mr-3 crud-titre">{!! \Config::get('listes.boutons.database') !!} {{ $h1 }}</span>
        <a href="{{ $hrefs['create'] }}" class="text-decoration-none mr-1">
            <button class="btn-sm btn-primary">
                {!! \Config::get('listes.boutons.ajouter_cercle') !!}
                <span class="d-none d-md-inline">Ajouter</span>
            </button>
        </a>
        <button class="btn-sm btn-danger" id="multi-suppressions">
            {!! \Config::get('listes.boutons.supprimer') !!}
            <span class="d-none d-md-inline">Supprimer la sélection</span>
        </button>
    </div>
    <div class="col-12 card-body mt-2 px-2">
        <table id="tab" class="table table-hover table-sm text-center w-100">
            <thead>
                <tr>
                    <th scope="col" class="px-2"><input type="checkbox" id="tout" data-action="cocher"></th>
                    {{-- <th scope="col" class="text-left px-2">{{ Str::singular($table) }}</th> --}}
                    @for ($i = 0; $i < count($listeAttributsVisibles); $i++)
                        <th scope="col" class="text-left px-2">{{ Str::lower($listeAttributsVisibles[$i]['label']) }}</th>
                    @endfor
                    <th scope="col" class="text-right px-3">actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($liste as $id => $ligne)
                    <tr>
                        <td class="px-2 align-middle"><input type="checkbox" id="check{{ $id }}" value="{{ $id }}"></td>
                        {{-- <td align="left" class="px-2 align-middle">{{ $ligne->crud_name }}</td> --}}
                        @for ($i = 0; $i < count($ligne->afficher); $i++)
                            <td align="left" class="px-2 align-middle">{{ $ligne->afficher[$i] }}</td>
                        @endfor
                        <td align="right" class="px-2 text-right align-middle">
                            <div class="d-inline-flex flex-shrink-0">
                                <a href="{{ $ligne->href_show }}" title="Voir" class="text-decoration-none flex-shrink-0">
                                    <button class="btn-sm btn-success mr-1">
                                        {!! \Config::get('listes.boutons.voir') !!}
                                        <span class="d-none d-lg-inline">Voir</span>
                                    </button>
                                </a>
                                <a href="{{ $ligne->href_update }}" title="Editer" class="text-decoration-none flex-shrink-0">
                                    <button class="btn-sm btn-info text-white mr-1">
                                        {!! \Config::get('listes.boutons.editer') !!}
                                        <span class="d-none d-lg-inline">Éditer</span>
                                    </button>
                                </a>
                                <a href="" title="Supprimer" class="text-decoration-none flex-shrink-0">
                                    <button class="btn-sm btn-danger">
                                        {!! \Config::get('listes.boutons.supprimer') !!}
                                        <span class="d-none d-lg-inline">Supprimer</span>
                                    </button>
                                </a>
                            </div>

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
    triDataTables({urlApp: "<?php echo route('home') ?>", idTable}) // Tri du tableau avec DataTables
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
