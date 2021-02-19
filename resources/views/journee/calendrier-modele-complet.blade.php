<?php
    $equipeId = $equipeId ?? '';
    $equipeDomVainqueur = $equipeExtVainqueur = false;
    $avecTab = isset($match->avec_tirs_au_but) ? true : false;
    $avecProlongations = isset($match->avec_prolongations) ? true : false;
    $tab_eq_dom = isset($match->tab_eq_dom) ? $match->tab_eq_dom : '';
    $tab_eq_ext = isset($match->tab_eq_ext) ? $match->tab_eq_ext : '';

    $resultat = '';
    if($equipeId == $match->equipe_id_dom)
        $resultat = $match->resultat_eq_dom ? $match->resultat_eq_dom['type'] : '';

    if($equipeId == $match->equipe_id_ext)
        $resultat = $match->resultat_eq_ext ? $match->resultat_eq_ext['type'] : '';

    // L'équipe à domicile VAINQUEUR
    if($avecTab && $tab_eq_dom > $tab_eq_ext || $match->score_eq_dom > $match->score_eq_ext){
        $equipeDomVainqueur = true;
        if($equipeId)
            $resultat = ($equipeId == $match->equipe_id_dom) ? 'victoire' : 'defaite';
    }
    
    // L'équipe à l'exterieur VAINQUEUR
    if($avecTab && $tab_eq_ext > $tab_eq_dom || $match->score_eq_dom < $match->score_eq_ext){
        $equipeExtVainqueur = true;
        if($equipeId)
            $resultat = ($equipeId == $match->equipe_id_ext) ? 'victoire' : 'defaite';
    }
?>
<div class="col-12 row d-flex flex-nowrap py-2 px-0 mx-0 border-bottom match-equipe @if(isset($i) && $i==0) border-top @endif">
    <div class="col-4 p-0 d-flex flex-wrap justify-content-start text-left align-items-center">
        <div class="col-md-3 d-md-inline py-0 px-0 text-center logo-align-auto">
            <a class="text-dark" href="{{ $match->href_equipe_dom }}">
                <img src="{{ $match->fanion_equipe_dom }}" alt="{{ $match->equipe_dom->nom }}" class="fanion-page-equipe">
            </a>
        </div>
        <div class="equipe-domicile col-md-9 d-md-inline px-0 equipe-align-auto">
            <a class="text-dark @if($equipeDomVainqueur && !$equipeId || $equipeId == $match->equipe_id_dom) font-weight-bold @endif" href="{{ $match->href_equipe_dom }}">
                {{ $match->equipe_dom->nom }}
            </a>
        </div>
    </div>
    <a href="{{ $match->url }}" class="col-4 d-flex flex-wrap justify-content-center align-items-center p-0">
        @if(strlen($match->score_eq_dom) > 0 && strlen($match->score_eq_ext) > 0)
            <span class="col-12 text-center font-weight-bold @if($resultat) {{ $resultat }} @else text-body @endif" style="font-size: 1.5rem">{!! $match->score !!}</span>
            @if($avecTab)
                <span class="col-12 text-center font-weight-bold @if($resultat) {{ $resultat }} @else text-body @endif" style="font-size: 0.9rem">tab. {{ $match->tab_eq_dom . '-' . $match->tab_eq_ext }}</span>
            @endif
            <span class="col-12 text-center text-secondary" style="font-size:0.9rem">{{ date_format(new DateTime($match->date), 'd/m') }}</span>
        @else
            <span class="text-secondary" style="font-size: 1.5rem">{{ date_format(new DateTime($match->date), 'd/m') }}</span>

            {{-- Si on doit afficher la compétition, alors on affiche l'heure si le résultat du match n'est pas saisi --}}
            @if(isset($afficherCompetition) && $match->heure)
                <span class="col-12 text-center text-secondary" style="font-size:0.9rem"> {{ $match->heure }} </span>
            @endif
        @endif

        {{-- On affiche le nom de la compétition --}}
        @if(isset($afficherCompetition))
            <span class="col-12 text-center text-secondary" style="font-size:0.7rem">{{ $match->competition }}</span>
        @endif
    </a>
    <div class="col-4 p-0 d-flex flex-wrap justify-content-end align-items-center text-right">
        <div class="equipe-exterieur col-md-9 d-md-inline order-2 order-md-1 px-0 equipe-align-auto">
            <a class="text-dark @if($equipeExtVainqueur && !$equipeId || $equipeId == $match->equipe_id_ext) font-weight-bold @endif" href="{{ $match->href_equipe_ext }}">
                {{ $match->equipe_ext->nom }}
            </a>
        </div>
        <div class="col-md-3 d-md-inline order-1 order-md-2 py-0 px-0 text-center logo-align-auto">
            <a class="text-dark" href="{{ $match->href_equipe_ext }}">
                <img src="{{ $match->fanion_equipe_ext }}" alt="{{ $match->equipe_ext->nom }}" class="fanion-page-equipe">
            </a>
        </div>
    </div>
</div>