@extends('layouts.competition')

@section('title', $competition . ' - ' . $sport)

@section('content')

<div class="row d-flex flex-wrap m-0 my-3 bg-white rounded p-3">
    <div class="col-12">
        <h1 class="h4 text-center p-3">{{ $sport . ' - ' . $competition }}</h1>
    </div>
    <div class="col-lg-8 d-flex flex-wrap p-0">
        @if (isset($derniereJournee))
            <div class="col-12 pb-3 mb-3 px-0">
                <h3 class="alert alert-danger text-center">Les derniers résultats</h3>
                <div class="px-3">
                    {!! $derniereJournee !!}
                </div>
            </div>
        @endif
        @if (isset($classement))
        <div class="col-12 p-0 mb-3 pb-3">
            {{-- <h1 class="h3 py-3 text-center">Le classement</h1> --}}
            <h3 class="alert alert-info text-center">Le classement</h3>
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

        @if (isset($prochaineJournee))
            <div class="col-12 px-0">
                <h3 class="alert alert-success text-center">La prochaine journée</h3>
                <div class="px-3">
                    {!! $prochaineJournee !!}
                </div>
            </div>
        @endif
    </div>
    <div class="col-lg-4 pl-5 pr-0 text-center">
        PUB
    </div>
</div>

@endsection
