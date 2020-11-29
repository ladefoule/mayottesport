@extends('layouts.' . request()->layout)

@section('title', $title)

@section('content')
<div class="row card">
    <div class="d-none">@csrf</div>
    <div class="card-header d-flex align-items-center px-2">
        <span class="d-inline mr-3 crud-titre">{!! \Config::get('constant.boutons.database') !!} {{ $h1 }}</span>
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
    <div class="col-12 card-body mt-2 px-2">
        
        <div >

            <table >
                <tr>
                    <th>Id</th>
                    <th>Uniqid</th>
                </tr>
                @foreach($matches as $match)
                <tr>
                    <td>{{ $match->id }}</td>
                    <td>{{ $match->uniqid }}</td>
                </tr>
                @endforeach
            </table>
        </div>

        {{ $matches->links() }}
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
