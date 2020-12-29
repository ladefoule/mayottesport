@extends('layouts.competition')

@section('title', $competition . ' - ' . $sport)

@section('content')
<div class="row d-flex flex-wrap justify-content-between text-center">
    <div class="col-12">
        <h1 class="h4">{{ $sport . ' - ' . $competition }}</h1>
    </div>
    <div class="col-lg-9 d-flex flex-wrap mt-3">
        @if ($derniereJournee)
            <div class="col-12">
                <h3 class="h5 border-bottom-calendrier text-danger">Les derniers résultats</h3>
                <div>
                    {!! $derniereJournee !!}
                </div>
            </div>
        @endif

        @if ($prochaineJournee)
            <div class="col-12 mt-4">
                <h3 class="h5 border-bottom-calendrier text-success">La prochaine journée</h3>
                <div>
                    {!! $prochaineJournee !!}
                </div>
            </div>
        @endif

        @if (isset($classement))
        <div class="col-12 mt-4 px-0">
            {{-- <h1 class="h3 py-3">Le classement</h1> --}}
            <h3 class="h5 border-bottom-calendrier text-body">Le classement</h3>
            <table class="mt-3 w-100 table table-classement" id="classement">
                <thead class="thead-light">
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
                <tbody class="border-bottom">
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
                        <?php if($i == 5) break; ?>
                    @endforeach
                </tbody>
            </table>
            <a class="d-block" href="{{ $hrefClassement }}">Le classement complet</a>
        </div>
        @endif
    </div>
    <div class="d-flex col-lg-3 justify-content-center px-3">
        <div class="border h-100 w-100 p-3">
            PUB
        </div>
    </div>
</div>
@endsection
