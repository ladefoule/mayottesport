@foreach ($sports as $sport)
   <div class="col-12 text-center my-2 px-3">
         <span class="h2 font-italic">
            <a class="text-body" href="{{ route('sport.index', ['sport' => \Str::slug($sport->nom)]) }}">
               {{ $sport->nom }}
            </a>
         </span>
   </div>
   @foreach ($sport->journees as $journee)
   <div class="col-12 text-center pb-3 justify-content-between">
         <h3 class="col-12 h4 border-bottom-calendrier py-2">
            <a href="{{ route('competition.index', ['sport' => \Str::slug($sport->nom), 'competition' => \Str::slug($journee['competition_nom'])]) }}">
               {{ $journee['competition_nom'] }}
            </a>
         </h3>
         <div class="pl-0">
            {!! $journee['journee_render'] !!}
         </div>
   </div>
   @endforeach
@endforeach