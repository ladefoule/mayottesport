@extends('layouts.competition')

@section('title', $title)

@section('content')
<div class="row border bg-white rounded d-flex justify-content-center p-2">
    <h1 class="col-12 h5 pb-3 pt-3 text-center">{{ $h1 }}</h1>
    <table class="col-12 pb-3 table table-striped text-center classement w-100">
        <thead>
            <th>#</th>
            <th>{{Str::ucfirst('équipe')}}</th>
            <th class="px-2">J</th>
            <th class="px-2">G</th>
            <th class="px-2">N</th>
            <th class="px-2">P</th>
            <th class="d-none d-lg-table-cell">BP</th>
            <th class="d-none d-lg-table-cell">BC</th>
            <th class="px-2">+/-</th>
            <th class="px-2">Pts</th>
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
                                <div class="d-none d-lg-block">
                                    <img src="{{ $equipe['fanion'] }}" alt="{{ $equipe['nom'] }}" class="fanion-calendrier pr-2">
                                </div>
                                <div class="text-left">
                                    {{ $equipe['nom'] }}
                                </div>
                            </div>
                        </a>
                    </td>
                    <td class="px-2">{{ $equipe['joues'] }}</td>
                    <td class="px-2">{{ $equipe['victoire'] }}</td>
                    <td class="px-2">{{ $equipe['nul'] }}</td>
                    <td class="px-2">{{ $equipe['defaite'] }}</td>
                    <td class="d-none d-lg-table-cell">{{ $equipe['marques'] }}</td>
                    <td class="d-none d-lg-table-cell">{{ $equipe['encaisses'] }}</td>
                    <td class="px-2">{{ $equipe['diff'] }}</td>
                    <td class="font-weight-bold h5">{{ $equipe['points'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="col-12 text-center">
        <span>Classement sous réserve d'homologation</span>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // $('#classement').DataTable( {
    //     destroy: true, // On "vide le cache" de l'objet DataTables
    //     paging: false, // Activation de la pagination
    //     searching:false,
    //     "info": false,
    //     language: {
    //         url : "/json/datatables.json" // Traduction en français
    //     },
    //     order : [[ 9, 'desc' ]], // Colonne et sens de tri
    //     columnDefs: [
    //         { targets: [1], orderable: false },
    //     ]
    // } );
})
</script>
@endsection
