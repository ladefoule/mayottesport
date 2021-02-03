<div class="calendrier">
    <p class="text-center header-journee font-italic">
        {{ $journee }} : {{ $date }}</span>
    </p>
    @foreach($matches as $i => $match)
        <?php 
            $equipeDomVainqueur = $equipeExtVainqueur = false;
            $avecTab = isset($match->avec_tirs_au_but) ? true : false;
            $avecProlongations = isset($match->avec_prolongations) ? true : false;
            $tab_eq_dom = isset($match->tab_eq_dom) ? $match->tab_eq_dom : '';
            $tab_eq_ext = isset($match->tab_eq_ext) ? $match->tab_eq_ext : '';

            if($match->score_eq_dom > $match->score_eq_ext || $tab_eq_dom > $tab_eq_ext)
                $equipeDomVainqueur = true;
            
            if($match->score_eq_ext > $match->score_eq_dom || $tab_eq_ext > $tab_eq_dom)
                $equipeExtVainqueur = true;
        ?>
        <a href="{{ $match->url }}" class="text-decoration-none text-body match-calendrier">
            <div class="row d-flex flex-nowrap py-2 border-bottom @if($i==0) border-top @endif">
                <div class="bloc-dom p-0 d-flex justify-content-between align-items-center @if($equipeDomVainqueur) font-weight-bold @endif">
                    <div class="fanion-calendrier pr-xl-1">
                        <img src="{{ $match->fanion_equipe_dom }}" alt="{{ $match->equipe_dom->nom }}">
                    </div>
                    <div class="equipe-domicile">
                        {{ $match->equipe_dom->nom }}
                    </div>
                </div>
                <div class="bloc-sc d-flex flex-wrap justify-content-center align-items-center p-0">
                    <span class="col-12 p-0 text-center score">{!! $match->score !!} @if($avecProlongations) ap. @endif</span>
                    @if ($avecTab)
                        <span class="col-12 p-0 text-center tirs-au-but">tab. {{ $match->tab_eq_dom . '-' . $match->tab_eq_ext }}</span>
                    @endif
                </div>
                <div class="bloc-ext p-0 d-flex justify-content-between align-items-center @if($equipeExtVainqueur) font-weight-bold @endif">
                    <div class="equipe-exterieur">
                        {{ $match->equipe_ext->nom }}
                    </div>
                    <div class="fanion-calendrier  pl-xl-1">
                        <img src="{{ $match->fanion_equipe_ext }}" alt="{{ $match->equipe_ext->nom }}">
                    </div>
                </div>
            </div>
        </a>
    @endforeach
</div>

