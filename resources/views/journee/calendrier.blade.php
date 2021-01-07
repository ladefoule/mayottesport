<h4 class="h5 py-2 text-center">{{ $journee }}</h4>
{{-- <h4 class="h5 py-2 text-center">{{ niemeJournee($journee->numero) . ' : ' . date('d/m/Y', strtotime($journee->date)) }}</h4> --}}
@foreach($matches as $i => $match)
    <a href="{{ $match->url }}" class="text-decoration-none text-body match-calendrier">
        <div class="row d-flex flex-nowrap py-2 border-bottom @if($i==0) border-top @endif">
            <div class="col-5 p-0 d-flex justify-content-between align-items-center @if($match->score_eq_dom > $match->score_eq_ext) font-weight-bold @endif">
                <div>
                    <img src="{{ $match->fanion_equipe_dom }}" alt="{{ $match->equipe_dom->nom }}" class="fanion-calendrier pr-2">
                </div>
                <div class="text-right">
                    {{ $match->equipe_dom->nom }}
                </div>
            </div>
            <div class="col-2 d-flex justify-content-center align-items-center p-0">
                {!! $match->score !!}
            </div>
            <div class="col-5 p-0 d-flex justify-content-between align-items-center @if($match->score_eq_dom < $match->score_eq_ext) font-weight-bold @endif">
                <div class="text-left">
                    {{ $match->equipe_ext->nom }}
                </div>
                <div>
                    <img src="{{ $match->fanion_equipe_ext }}" alt="{{ $match->equipe_ext->nom }}" class="fanion-calendrier pl-2">
                </div>
            </div>
        </div>
    </a>
@endforeach

