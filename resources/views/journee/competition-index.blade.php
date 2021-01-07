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