@extends('layouts.competition')

@section('title', $title)

@section('content')
<div class="col-12 mx-0 py-3 bg-white rounded">
    <h1 class="h5 pb-3 text-center">{{ $h1 }}</h1>
    <table class="w-100 table table-striped text-center table-classement" id="classement">
        <thead>
            <th>#</th>
            <th>{{Str::ucfirst('équipe')}}</th>
            <th>J</th>
            <th class="d-none d-sm-table-cell">G</th>
            <th class="d-none d-sm-table-cell">N</th>
            <th class="d-none d-sm-table-cell">P</th>
            <th class="d-none d-lg-table-cell">BP</th>
            <th class="d-none d-lg-table-cell">BC</th>
            <th class="d-none d-lg-table-cell">+/-</th>
            <th>Pts</th>
        </thead>
        <tbody>
            @php
            $i = 1;
            @endphp
            @foreach ($classement as $equipe)
                <tr>
                    <td class="font-weight-bold">{{ $i++ }}</td>
                    <td align="left">
                        <a href="{{ $equipe['hrefEquipe'] }}" class="text-dark">
                            <div class="p-0 d-flex justify-content-start align-items-center">
                                <div>
                                    <img src="{{ $equipe['fanion'] }}" alt="{{ $equipe['nom'] }}" class="fanion-calendrier pr-2">
                                </div>
                                <div class="text-left">
                                    {{ $equipe['nom'] }}
                                </div>
                            </div>
                        </a>
                    </td>
                    <td>{{ $equipe['joues'] }}</td>
                    <td class="d-none d-sm-table-cell">{{ $equipe['victoire'] }}</td>
                    <td class="d-none d-sm-table-cell">{{ $equipe['nul'] }}</td>
                    <td class="d-none d-sm-table-cell">{{ $equipe['defaite'] }}</td>
                    <td class="d-none d-lg-table-cell">{{ $equipe['marques'] }}</td>
                    <td class="d-none d-lg-table-cell">{{ $equipe['encaisses'] }}</td>
                    <td class="d-none d-lg-table-cell">{{ $equipe['diff'] }}</td>
                    <td class="font-weight-bold">{{ $equipe['points'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="col-12 text-center mt-3">
        <span>Classement sous réserve d'homologation</span>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#classement').DataTable( {
        destroy: true, // On "vide le cache" de l'objet DataTables
        paging: false, // Activation de la pagination
        searching:false,
        "info": false,
        language: {
            url : "/json/datatables.json" // Traduction en français
        },
        order : [[ 9, 'desc' ]], // Colonne et sens de tri
        columnDefs: [
            { targets: [1], orderable: false },
        ]
    } );
})
</script>
@endsection
