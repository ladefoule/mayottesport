<?php 
    $hrefIndex = request()->hrefIndex;
    $hrefClassement = request()->hrefClassement ?? '';
?>

@extends('layouts.competition')

@section('title', $competition->nom_complet . ' - ' . $sport->nom)

@section('content')
<div class="p-lg-3 h-100">
    {{-- classique écran large --}}
    <div class="d-none d-lg-flex h-100 p-0">
        <div class="col-12 px-3 pt-2 bg-white shadow-div">
            <div class="col-12">
                <h1 class="h3 text-center m-auto p-3">{{ $competition->nom_complet }}</h1>
            </div>

            {{-- LES DERNIERS RESULTATS --}}
            @if($journeeAffichee)
                <div class="col-12">
                    <h3 class="col-12 h4 text-center mb-3 text-danger">Les derniers résultats</h3>
                    {!! $journeeAffichee !!}
                </div>
            @endif

            {{-- LE CLASSEMENT --}}
            @if($classement)
                <div class="col-12 p-0 mt-5">
                    <h3 class="col-12 h4 text-center mb-3 text-info">Le classement</h3>
                    <table class="table text-center classement w-100 border-bottom" id="classement">
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
                        <a class="" href="{{ $hrefClassement }}">Le classement complet</a>
                    </div>
                </div>
            @endif

            {{-- L'ACTU --}}
            <div class="col-12">
                {!! $articles !!}
            </div>
        </div>
    </div>

    {{-- avec onglets --}}
    <div class="col-12 d-lg-none d-flex text-center p-3 bg-white">
        <span data-cible="actualites-content"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if($articles) active @endif">Actualités</span>
        <span data-cible="resultats-content"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $articles && $resultats) active @endif">Résultats</span>
        <span data-cible="prochains-content"
            class="d-block col-4 p-3 border btn btn-secondary onglet @if(! $resultats && ! $articles) active @endif">À venir</span>
    </div>

    <div class="col-12 d-lg-none bg-white pt-0">
        <div id="actualites-content" class="@if(! $articles) d-none @endif">
            {!! $articles !!}
        </div>
        <div id="resultats-content" class="@if($articles || !$resultats) d-none @endif">
            @foreach ($resultats as $resultat)
                <div class="p-3">
                    {!! $resultat !!}
                </div>
            @endforeach
        </div>
        <div id="prochains-content" class="@if($articles || $resultats) d-none @endif">
            @foreach ($prochains as $prochain)
                <div class="p-3">
                    {!! $prochain !!}
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection