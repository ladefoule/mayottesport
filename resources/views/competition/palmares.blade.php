@extends('layouts.competition')

@section('title', $title)

@section('head')
<meta name="description" content="Retrouvez l'historique de la compétition : {{ $competition->nom }} de {{ $sport->nom }} à Mayotte.">
@endsection

@section('pub-top')
<div class="d-none d-lg-block col-12 mx-auto mt-3">
    @include('pub.google-display-responsive')
</div>
@endsection

@section('content')
<div class="p-lg-3">
    <div class="row m-0 bg-white shadow-div">
        <div class="col-12 p-0">
            <div class="col-12 p-4">
                <h1 class="h4 text-center col-12">{{ $competition->nom . ' - Le palmarès'}}</h1>
            </div>
            <div class="col-12 d-flex flex-wrap align-items-start font-size-1-rem">
                <table class="table text-center border-bottom">
                    <thead class="bg-light">
                        <tr>
                            <th class="col-4">Saison</th>
                            <th class="col-4">Vainqueur</th>
                            <th class="d-none d-md-table-cell">@if($competition->type == 1) Vice-champion @else Finaliste @endif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saisons as $saison)
                        <?php 
                            $journees = index('journees')->where('saison_id', $saison->id);
                            $derniereJournee = $journees->sortByDesc('numero')->first();
                            $nbJournees = count($journees);
                        ?>
                        <tr>
                            <td>
                                @if($nbJournees > 0)
                                    <a href="{{ route('competition.saison.calendrier-resultats', ['sport' => $sport->slug, 'competition' => $competition->slug_complet, 'annee' => annee($saison->annee_debut, $saison->annee_fin), 'journee' => $derniereJournee->numero]) }}">
                                @endif
                                    {{ $saison->nom}}
                                @if($nbJournees > 0)
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if($saison->annulee)
                                    X*
                                @else
                                    {{ $saison->equipe_id ? index('equipes')[$saison->equipe_id]->nom : '' }}
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($saison->annulee)
                                    X*
                                @else
                                    {{ $saison->second ? index('equipes')[$saison->second]->nom : '' }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <p class="text-left px-3">X => Saison annulée (pas de vainqueur)</p>
            </div>
        </div>

        <div class="col-12 m-auto py-3 px-2">
            @include('pub.google-display-responsive')
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function(){

})
</script>
@endsection
