@extends('layouts.competition')

@section('title', $match->title)

@section('pub-top')
    {{-- PUB --}}
    <div class="d-none d-lg-block col-12 m-auto p-3">
        @include('pub.google-display-responsive')
    </div>
@endsection

@section('content')
<div class="p-lg-3 h-100">
    <form action="" method="post" id="formulaire">
        @csrf
        <div class="row m-0 text-white bloc-match bloc-match-{{ $sport->slug }} py-4" style="background-image: url('{{ asset('storage/img/sport/'.$sport->slug.'.jpg') }}')">
            <div class="row mx-0 col-5 d-flex justify-content-between align-items-center bloc-equipe-dom p-1 mb-5">
                <div class="col-md-4 col-lg-12 col-xl-4 py-2 px-0">
                    <a href="{{ $match->href_equipe_dom }}"><img src="{{ $match->fanion_equipe_dom }}" alt="{{ $match->equipe_dom->nom }}" class="fanion-match"></a>
                </div>
                <div class="equipe-domicile col-md-8 col-lg-12 col-xl-8 py-2 px-0">
                    <a href="{{ $match->href_equipe_dom }}" class="text-white">{{ $match->equipe_dom->nom }}</a>
                </div>
            </div>
            <div class="col-2 bloc-score d-flex align-items-center justify-content-around p-0 mb-5">
                <span class="w-100 text-center font-weight-bold">{!! $match->score !!}</span>
            </div>
            <div class="row mx-0 col-5 d-flex justify-content-between align-items-center bloc-equipe-ext p-1 mb-5">
                <div class="equipe-exterieur col-md-8 col-lg-12 col-xl-8 order-2 order-md-1 order-lg-2 order-xl-1 py-2 px-0">
                    <a href="{{ $match->href_equipe_ext }}" class="text-white">{{ $match->equipe_ext->nom }}</a>
                </div>
                <div class="col-md-4 col-lg-12 col-xl-4 order-1 order-md-2 order-lg-1 order-xl-2 py-2 px-0">
                    <a href="{{ $match->href_equipe_ext }}"><img src="{{ $match->fanion_equipe_ext }}" alt="{{ $match->equipe_ext->nom }}" class="fanion-match"></a>
                </div>
            </div>

            <div class="form-row col-12 p-3">
                <div class="col-12 d-flex justify-content-center pb-3">
                    <input class="text-center form-control col-6 col-md-4 col-lg-3" type="date" name="date" data-msg="Merci de saisir une date valide." value="{{ $match->date }}">
                </div>
                <div class="col-12 d-flex justify-content-center">
                    <input class="text-center form-control col-6 col-md-4 col-lg-3" type="time" name="heure" pattern="\d{2}:\d{2}" data-msg="Merci de saisir une heure." value="{{ $match->heure }}">
                </div>
            </div>     

            <div class="col-12 d-flex justify-content-center align-items-center">
                <div class="col-lg-6 alert alert-danger text-dark p-3 d-none" id="messageErreur"></div>
            </div>

            <div class="col-12 text-center p-3">
                <button class="btn btn-danger text-white px-5">Valider</button>
            </div>
        </div>

        <div class="col-12 m-auto py-3 px-2">
            @include('pub.google-display-responsive')
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    //
    verifierMonFormulaireEnJS('formulaire')
})
</script>
@endsection
