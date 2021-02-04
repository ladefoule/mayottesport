@extends('layouts.competition')

@section('title', $title)

@section('content')
<div class="p-lg-3 h-100">
    <div class="row bg-white shadow-div justify-content-center m-0 h-100">
        <div class="col-12 py-3 px-0">
            <h1 class="col-12 h5 p-2 text-center">{{ $h1 }}</h1>
            <div class="col-12 mt-3 px-2">
                <table class="table text-center classement w-100 border-bottom" id="classement">
                    <thead {{-- class="thead-light thead-fixed" --}}>
                        <th class="px-2">#</th>
                        <th>{{Str::ucfirst('équipe')}}</th>
                        <th class="px-2" title="Joués">J</th>
                        <th class="px-2" title="Gagnés">G</th>
                        <th class="px-2" title="Perdus">P</th>
                        <th class="d-none d-lg-table-cell px-2" title="Forfaits">F</th>
                        <th class="d-none d-md-table-cell px-2" title="Buts marqués">bp</th>
                        <th class="d-none d-md-table-cell px-2" title="Buts encaissés">bc</th>
                        <th class="px-2" title="Différence de buts">+/-</th>
                        <th class="px-2" title="Points">pts</th>
                    </thead>
                    <tbody>
                        @foreach ($classement as $i => $equipe)
                            <tr>
                                <td class="px-2 align-middle">{{ $i+1 }}</td>
                                <td align="left" class="px-2 align-middle py-2">
                                    <a href="{{ $equipe['hrefEquipe'] }}" class="text-dark">
                                        <div class="p-0 d-flex justify-content-start align-items-center">
                                            <div class="d-none d-md-block fanion-calendrier pr-2">
                                                <img src="{{ $equipe['fanion'] }}" alt="{{ $equipe['nom'] }}">
                                            </div>
                                            <div class="text-left equipe">
                                                {{ $equipe['nom'] }}
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td class="align-middle px-2">{{ $equipe['joues'] }}</td>
                                <td class="align-middle px-2">{{ $equipe['victoire'] }}</td>
                                <td class="align-middle px-2">{{ $equipe['defaite'] }}</td>
                                <td class="align-middle d-none d-lg-table-cell px-2">{{ $equipe['forfaits'] ?? 0 }}</td>
                                <td class="align-middle d-none d-md-table-cell px-2">{{ $equipe['marques'] }}</td>
                                <td class="align-middle d-none d-md-table-cell px-2">{{ $equipe['encaisses'] }}</td>
                                <td class="align-middle px-2">{{ $equipe['diff'] }}</td>
                                <td class="align-middle font-weight-bold h5">{{ $equipe['points'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-12 text-center pb-3">
                <span>Classement sous réserve d'homologation</span>
            </div>

            <div class="col-12 m-auto py-0 px-2">
                @include('pub.google-display-responsive')
            </div>
        </div>
    </div>
</div>
@endsection
