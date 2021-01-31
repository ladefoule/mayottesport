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
                <div class="col-lg-4 d-lg-inline py-2 px-0">
                    <img src="{{ $match->fanion_equipe_dom }}" alt="{{ $match->equipe_dom->nom }}" class="fanion-match">
                </div>
                <div class="equipe-domicile col-lg-8 d-lg-inline py-2 px-0">
                    {{ $match->equipe_dom->nom }}
                </div>
            </div>
            <div class="col-2 bloc-score d-flex align-items-center justify-content-around p-0 mb-5">
                <span class="w-100 text-center font-weight-bold">{!! $match->score !!}</span>
            </div>
            <div class="row mx-0 col-5 d-flex justify-content-between align-items-center bloc-equipe-ext p-1 mb-5">
                <div class="equipe-exterieur col-lg-8 d-lg-inline order-2 order-lg-1 py-2 px-0">
                    {{ $match->equipe_ext->nom }}
                </div>
                <div class="col-lg-4 d-lg-inline order-1 order-lg-2 py-2 px-0">
                    <img src="{{ $match->fanion_equipe_ext }}" alt="{{ $match->equipe_ext->nom }}" class="fanion-match">
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

            <div class="col-12 text-center">
                <div class="col-12">
                    {{ $match->competition }} : {{ $match->journee }}
                </div>
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
