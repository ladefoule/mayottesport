@if ($derniereJournee)
   <div class="col-12 px-0 pb-3">
         <h2 class="alert alert-danger h4 py-4 text-center">Les derniers résultats</h2>
         <div class="px-3">
            {!! $derniereJournee !!}
         </div>
   </div>
@endif

@if ($prochaineJournee)
   <div class="col-12 px-0 pb-3">
         <h2 class="alert alert-success h4 py-4 text-center">La prochaine journée</h2>
         <div class="px-3">
            {!! $prochaineJournee !!}
         </div>
   </div>
@endif