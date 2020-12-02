<?php
    $domicile = $match['equipe_id_dom'] == $equipe->id;
    $hrefEqDom = $match['href_equipe_dom'];
    $hrefEqExt = $match['href_equipe_ext'];
?>
<div class="col-12 row d-flex flex-nowrap py-0 px-0 mx-0">
   <div class="col-4 p-0 d-flex flex-wrap justify-content-center align-items-center">
       <div class="col-12 py-0 px-0 text-center mt-auto">
           @if (! $domicile)
               <a class="text-dark" href="{{ $hrefEqDom }}">
           @endif
           <img src="{{ $match['fanion_equipe_dom'] }}" alt="{{ $match['equipe_dom_nom'] }}" style="width:25px">
           @if (! $domicile)
               </a>
           @endif
       </div>
       <div class="col-12 px-0 mb-auto">
           @if (! $domicile)
               <a class="text-dark" href="{{ $hrefEqDom }}">
           @endif
           {{ $match['equipe_dom_nom'] }}
           @if (! $domicile)
               </a>
           @endif
       </div>
   </div>
   <a href="{{ $match['url'] }}" class="col-4 d-flex text-body flex-wrap justify-content-center align-items-center p-0">
       <?php
           if(strlen($match['score_eq_dom']) > 0 && strlen($match['score_eq_ext']) > 0){
               echo '<span class="font-weight-bold '.$resultat.'">' . $match['score'] . '</span>';
               echo '<span class="col-12 text-center" style="font-size:0.6rem">' . date_format(new DateTime($match['date']), 'd/m/y') . '</span>';
           }
           else
               echo '<span style="font-size: 1.5rem">' . date_format(new DateTime($match['date']), 'd/m') . '</span>';

           echo '<span class="col-12 text-center font-weight-bold" style="font-size:0.7rem">' . $match['competition'] . '</span>';
       ?>
   </a>
   <div class="col-4 p-0 d-flex flex-wrap justify-content-center align-items-center">
       <div class="col-12 py-0 px-0 mt-auto">
           @if ($domicile)
               <a class="text-dark" href="{{ $hrefEqExt }}">
           @endif
           <img src="{{ $match['fanion_equipe_ext'] }}" alt="{{ $match['equipe_ext_nom'] }}" style="width:25px">
           @if ($domicile)
               </a>
           @endif
       </div>
       <div class="col-12 px-0 mb-auto">
           @if ($domicile)
               <a class="text-dark" href="{{ $hrefEqExt }}">
           @endif
           {{ $match['equipe_ext_nom'] }}
           @if ($domicile)
               </a>
           @endif
       </div>
   </div>
</div>
