<h4 class="h5 py-2 text-center">{{ $journee }}</h4>
@foreach($calendrier as $i => $match)
    <a href="{{ $match['url'] }}" class="text-decoration-none text-body match-calendrier">
        <div class="row d-flex flex-nowrap py-2 border-bottom-dashed @if($i==0) border-top-dashed @endif">
            <div class="col-5 p-0 d-flex justify-content-between align-items-center @if($match['score_eq_dom'] > $match['score_eq_ext']) font-weight-bold @endif">
                <div>
                    <img src="{{ $match['fanion_eq_dom'] }}" alt="{{ $match['nom_eq_dom'] }}" class="fanion-calendrier pr-2">
                </div>
                <div class="text-right">
                    {{ $match['nom_eq_dom'] }}
                </div>
            </div>
            <div class="col-2 d-flex justify-content-center align-items-center">
                {!! $match['score'] !!}
            </div>
            <div class="col-5 p-0 d-flex justify-content-between align-items-center @if($match['score_eq_dom'] < $match['score_eq_ext']) font-weight-bold @endif">
                <div class="text-left">
                    {{ $match['nom_eq_ext'] }}
                </div>
                <div>
                    <img src="{{ $match['fanion_eq_ext'] }}" alt="{{ $match['nom_eq_ext'] }}" class="fanion-calendrier pl-2">
                </div>
            </div>
        </div>
    </a>
@endforeach

