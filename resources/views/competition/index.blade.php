<?php 
    $hrefIndex = request()->hrefIndex;
    $hrefClassement = request()->hrefClassement ?? '';
    $hrefActualite = request()->hrefActualite ?? '';
?>

@extends('layouts.competition')

@section('title', $sport->nom . ' - ' . $competition->nom_complet)

@section('content')
<div class="p-lg-3">
    <div class="col-12 px-2 pt-2 pb-3 bg-white shadow-div">
        <div class="col-12">
            <h1 class="h4 text-center m-auto p-3">{{ $sport->nom }} - {{ $competition->nom_complet }}</h1>
        </div>

        {{-- LES DERNIERS RESULTATS --}}
        @if($derniereJourneeRender)
            <div class="col-12 p-0 mt-0 d-flex justify-content-center flex-wrap mb-3">
                <span class="border-bottom border-danger h4 text-center text-danger">Les derniers résultats</span>
                <div class="col-12 p-0">
                    {!! $derniereJourneeRender !!}
                </div>
            </div>
        @endif

        {{-- LE CLASSEMENT --}}
        @if(count($classement) > 0)
        <div class="col-12 p-0 mt-0 d-flex justify-content-center flex-wrap mb-3">
            <span class="border-bottom border-info h4 text-center text-info mt-3">Le classement</span>
                <table class="table text-center classement w-100 border-bottom mt-3" id="classement">
                    <thead {{-- class="thead-light thead-fixed" --}}>
                        <th class="px-2">#</th>
                        <th>{{Str::ucfirst('équipe')}}</th>
                        <th class="px-2" title="Joués">J</th>
                        <th class="px-2" title="Gagnés">G</th>
                        @if($sport->slug != 'volleyball' && $sport->slug != 'basketball')
                            <th class="px-2" title="Nuls">N</th>
                        @endif
                        <th class="px-2" title="Perdus">P</th>
                        <th class="d-none d-lg-table-cell px-2" title="Forfaits">F</th>
                        @if($sport->slug != 'volleyball')
                            <th class="d-none d-md-table-cell px-2" title="Buts marqués">bp</th>
                            <th class="d-none d-md-table-cell px-2" title="Buts encaissés">bc</th>
                            <th class="px-2" title="Différence de buts">+/-</th>
                        @else
                            <th class="d-none d-md-table-cell px-2" title="Sets marqués">sp</th>
                            <th class="d-none d-md-table-cell px-2" title="Sets encaissés">sc</th>
                            <th class="px-2" title="Coefficient">coeff.</th>
                        @endif
                        <th class="px-2" title="Points">pts</th>
                    </thead>
                    <tbody>
                        @foreach ($classement->splice(0,3) as $i => $equipe)
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
                                @if($sport->slug != 'volleyball' && $sport->slug != 'basketball')
                                    <td class="align-middle px-2">{{ $equipe['nul'] }}</td>
                                @endif
                                <td class="align-middle px-2">{{ $equipe['defaite'] }}</td>
                                <td class="align-middle d-none d-lg-table-cell px-2">{{ $equipe['forfaits'] ?? 0 }}</td>
                                <td class="align-middle d-none d-md-table-cell px-2">{{ $equipe['marques'] }}</td>
                                <td class="align-middle d-none d-md-table-cell px-2">{{ $equipe['encaisses'] }}</td>
                                <td class="align-middle px-2">{{ ($sport->slug != 'volleyball') ? $equipe['diff'] : $equipe['coefficient'] }}</td>
                                <td class="align-middle font-weight-bold h5">{{ $equipe['points'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-12 text-center pb-3">
                    <a class="font-size-1-rem" href="{{ $hrefClassement }}">Le classement complet</a>
                </div>
            </div>
        @endif

        @if($prochaineJourneeRender)
            <div class="col-12 p-0 mt-0 d-flex justify-content-center flex-wrap mb-3">
                <span class="border-bottom border-success h4 text-center text-success">À venir</span>
                <div class="col-12 p-0">
                    {!! $prochaineJourneeRender !!}
                </div>
            </div>
        @endif

        {{-- L'ACTU --}}
        @if($articles)
            <div class="row col-12 px-2 p-0 mt-0 d-flex justify-content-center flex-wrap">
                <span class="border-bottom border-secondary h4 text-center text-secondary my-3">L'actualité</span>
                <div class="col-12 p-0 d-flex flex-wrap justify-content-start align-items-stretch">
                    {!! $articles !!}
                </div>
                <div class="col-12 text-center pb-3">
                    <a class="font-size-1-rem" href="{{ $hrefActualite }}">Toute l'actualité</a>
                </div>
            </div>
        @endif

        {{-- PUB --}}
        <div class="col-12 m-auto p-0">
            @include('pub.google-display-responsive')
        </div>
    </div>
</div>
@endsection