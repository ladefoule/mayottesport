@if ($derniereJournee)
   <div class="col-12 px-0">
         <h2 class="alert alert-danger h4 text-center">Les derniers résultats</h2>
         <div class="p-3">
            {!! $derniereJournee !!}
         </div>
   </div>
@endif

@if ($prochaineJournee)
   <div class="col-12 px-0 mt-4">
         <h2 class="alert alert-success h4 text-center">Prochaine journée</h2>
         <div class="p-3">
            {!! $prochaineJournee !!}
         </div>
   </div>
@endif