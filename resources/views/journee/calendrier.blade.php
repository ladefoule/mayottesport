<div class="calendrier">
    <p class="text-center header-journee d-flex flex-wrap">
        <span class="col-12 journee">{{ $journee }}</span>
        <span class="col-12 date">{!! config('listes.boutons.calendrier') !!} {{ $date }}</span>
    </p>
    @foreach($matches as $i => $match)
        <a href="{{ $match->url }}" class="text-decoration-none text-body match-calendrier">
            <div class="row d-flex flex-nowrap py-2 border-bottom @if($i==0) border-top @endif">
                <div class="col-5 p-0 d-flex justify-content-between align-items-center @if($match->score_eq_dom > $match->score_eq_ext) font-weight-bold @endif">
                    <div class="fanion-calendrier pr-2">
                        <img src="{{ $match->fanion_equipe_dom }}" alt="{{ $match->equipe_dom->nom }}">
                    </div>
                    <div class="equipe-domicile">
                        {{ $match->equipe_dom->nom }}
                    </div>
                </div>
                <div class="col-2 d-flex justify-content-center align-items-center p-0">
                    {!! $match->score !!}
                </div>
                <div class="col-5 p-0 d-flex justify-content-between align-items-center @if($match->score_eq_dom < $match->score_eq_ext) font-weight-bold @endif">
                    <div class="equipe-exterieur">
                        {{ $match->equipe_ext->nom }}
                    </div>
                    <div class="fanion-calendrier pl-2">
                        <img src="{{ $match->fanion_equipe_ext }}" alt="{{ $match->equipe_ext->nom }}">
                    </div>
                </div>
            </div>
        </a>
    @endforeach
</div>

