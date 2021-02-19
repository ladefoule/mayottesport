<div class="row col-12 mt-3 mb-auto px-0 mx-auto">
    <p class="col-12 text-center header-journee font-italic">
        {{ $journee }} : {{ $date }}</span>
    </p>

    @foreach ($matches as $match)
        @include('journee.calendrier-modele-complet', ['match' => infos('matches', $match->id)])
    @endforeach
</div>
