@extends('layouts.competition')

@section('title', $competition . ' - ' . $sport)

@section('content')
<div class="row d-flex flex-wrap justify-content-between bg-white rounded py-3">
    <div class="col-12 mb-3">
        <h1 class="h4 text-center">{{ $sport . ' - ' . $competition }}</h1>
    </div>
    <div class="col-lg-9 d-flex flex-wrap px-2">
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
                    <th class="px-2">#</th>
                    <th>{{Str::ucfirst('équipe')}}</th>
                    <th class="px-2">J</th>
                    <th class="px-2">G</th>
                    <th class="px-2">N</th>
                    <th class="px-2">P</th>
                    <th class="d-none d-md-table-cell px-2">bp</th>
                    <th class="d-none d-md-table-cell px-2">bc</th>
                    <th class="px-2">+/-</th>
                    <th class="px-2">pts</th>
                </thead>
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($classement as $equipe)
                        <tr>
                            <td class="px-2 font-weight-bold">{{ $i++ }}</td>
                            <td align="left" class="px-2">
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
                            <td class="d-none d-md-table-cell px-2">{{ $equipe['marques'] }}</td>
                            <td class="d-none d-md-table-cell px-2">{{ $equipe['encaisses'] }}</td>
                            <td class="px-2">{{ $equipe['diff'] }}</td>
                            <td class="font-weight-bold h5">{{ $equipe['points'] }}</td>
                        </tr>
                        <?php if($i == 6) break; ?>
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
