<?php $i = 0 ?>
@foreach ($matches as $match)
    <?php
        $equipeDomId = $match->equipe_id_dom;
        $equipeExtId = $match->equipe_id_ext;
        $resultat = $match->resultat_type;
        $match = $match->infos();
        $avecTab = $match->avec_tirs_au_but;
    ?>
    <div class="col-12 row d-flex flex-nowrap py-2 px-0 mx-0 border-bottom match-equipe @if($i==0) border-top @endif">
        <div class="col-4 p-0 d-flex flex-wrap justify-content-start text-left align-items-center">
            <div class="col-md-3 d-md-inline py-0 px-0 text-center logo-align-auto">
               <img src="{{ $match->fanion_equipe_dom }}" alt="{{ $match->equipe_dom->nom }}" class="fanion-page-equipe">
            </div>
            <div class="equipe-domicile col-md-9 d-md-inline px-0 equipe-align-auto">
               @if ($equipeDomId != $equipe->id)
                     <a class="text-dark" href="{{ $match->href_equipe_dom }}">
               @endif
               {{ $match->equipe_dom->nom }}
               @if ($equipeDomId != $equipe->id)
                     </a>
               @endif
            </div>
        </div>
        <a href="{{ $match->url }}" class="col-4 d-flex text-body flex-wrap justify-content-center align-items-center p-0">
        <?php
            if(strlen($match->score_eq_dom) > 0 && strlen($match->score_eq_ext) > 0){
                echo '<span class="col-12 text-center font-weight-bold text-body" style="font-size: 1.5rem">' . $match->score . '</span>';
                if($avecTab)
                    echo '<span class="col-12 text-center font-weight-bold text-body" style="font-size: 0.9rem"> tab. ' . $match->tab_eq_dom . '-' . $match->tab_eq_ext . '</span>';
                
                echo '<span class="col-12 text-center text-secondary" style="font-size:0.7rem">' . date_format(new DateTime($match->date), 'd/m') . '</span>';
            }
            else
                echo '<span class="text-secondary" style="font-size: 1.5rem">' . date_format(new DateTime($match->date), 'd/m') . '</span>';

            if($match->heure)
                echo '<span class="col-12 text-center text-secondary" style="font-size:0.6rem">' . $match->heure . '</span>';
        ?>
        </a>
        <div class="col-4 p-0 d-flex flex-wrap justify-content-end align-items-center text-right">
            <div class="equipe-exterieur col-md-9 d-md-inline order-2 order-md-1 px-0 equipe-align-auto">
               @if ($equipeExtId != $equipe->id)
                     <a class="text-dark" href="{{ $match->href_equipe_ext }}">
               @endif
               {{ $match->equipe_ext->nom }}
               @if ($equipeExtId != $equipe->id)
                     </a>
               @endif
            </div>
            <div class="col-md-3 d-md-inline order-1 order-md-2 py-0 px-0 text-center logo-align-auto">
               @if ($equipeExtId != $equipe->id)
                     <a class="text-dark" href="{{ $match->href_equipe_ext }}">
               @endif
               <img src="{{ $match->fanion_equipe_ext }}" alt="{{ $match->equipe_ext->nom }}" class="fanion-page-equipe">
               @if ($equipeExtId != $equipe->id)
                     </a>
               @endif
            </div>
        </div>
   </div>
   <?php $i++; ?>
@endforeach
