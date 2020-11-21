@extends('layouts.competition')

@section('title', $competition . ' - ' . $sport)

@section('content')

<div class="row d-flex flex-wrap justify-content-between mx-0 bg-white rounded">
    <div class="col-12 py-3">
        <h1 class="h4 text-center">{{ $sport . ' - ' . $competition }}</h1>
    </div>
    <div class="col-lg-9 d-flex flex-wrap">
        @if ($derniereJournee)
            <div class="col-12 p-0 pb-3 mb-3">
                <h3 class="alert h5 alert-danger text-center">Les derniers résultats</h3>
                <div class="px-3">
                    {!! $derniereJournee !!}
                </div>
            </div>
        @endif
        @if (isset($classement))
        <div class="col-12 p-0 mb-3 pb-3">
            {{-- <h1 class="h3 py-3 text-center">Le classement</h1> --}}
            <h3 class="alert h5 alert-info text-center">Le classement</h3>
            <table class="w-100 table table-striped text-center table-classement" id="classement">
                <thead>
                    <th>#</th>
                    <th>{{Str::ucfirst('équipe')}}</th>
                    <th>J</th>
                    <th class="d-none d-sm-table-cell">G</th>
                    <th class="d-none d-sm-table-cell">N</th>
                    <th class="d-none d-sm-table-cell">P</th>
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
                                <a href="" class="text-dark">
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
                            <td class="font-weight-bold">{{ $equipe['points'] }}</td>
                        </tr>
                        @php
                            if($i == 6) break;
                        @endphp
                    @endforeach
                </tbody>
            </table>
            <a class="d-block text-center" href="{{ $hrefClassement }}">Classement complet</a>
        </div>
        @endif

        @if ($prochaineJournee)
            <div class="col-12 px-0">
                <h3 class="alert h5 alert-success text-center">La prochaine journée</h3>
                <div class="px-3">
                    {!! $prochaineJournee !!}
                </div>
            </div>
        @endif
    </div>
    <div class="d-flex col-lg-3 justify-content-center border p-3">
        <div class="border h-100 p-3" style="width:100%">
            PUB
        </div>
    </div>
</div>

@endsection
