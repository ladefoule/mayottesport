@extends('layouts.competition')

@section('title', $match->title)

@section('pub-top')
    {{-- PUB --}}
    <div class="d-none d-lg-block col-12 m-auto px-3 pt-3">
        @include('pub.google-display-responsive')
    </div>
@endsection

@section('content')
<div class="p-lg-3 h-100">
    <form action="" method="post" id="formulaire">
        @csrf
        <div class="row m-0 text-white bloc-match bloc-match-{{ $sport->slug }} py-4" style="background-image: url('{{ asset('storage/img/sport/'.$sport->slug.'.jpg') }}')">
            <div class="row mx-0 col-4 d-flex justify-content-between align-items-center bloc-equipe-dom mb-5">
                <div class="col-md-4 col-lg-12 col-xl-4 py-2 px-0">
                    <a href="{{ $match->href_equipe_dom }}"><img src="{{ $match->fanion_equipe_dom }}" alt="{{ $match->equipe_dom->nom }}" class="fanion-match"></a>
                </div>
                <div class="equipe-domicile col-md-8 col-lg-12 col-xl-8 py-2 px-0">
                    <a href="{{ $match->href_equipe_dom }}" class="text-white">{{ $match->equipe_dom->nom }}</a>
                </div>
            </div>
            <div class="col-4 bloc-score d-flex align-items-center justify-content-center mb-5">
                <input type="text" name="score_eq_dom" value="{{ $match->score_eq_dom }}" class="@error('score_eq_dom') is-invalid @enderror px-2 rounded text-center font-weight-bold" data-msg="Merci de saisir un score valide." pattern="\d+">
                <span class="p-2">-</span>
                <input type="text" name="score_eq_ext" value="{{ $match->score_eq_ext }}" class="@error('score_eq_ext') is-invalid @enderror px-2 rounded text-center font-weight-bold" data-msg="Merci de saisir un score valide." pattern="\d+">
            </div>
            <div class="row mx-0 col-4 d-flex justify-content-between align-items-center bloc-equipe-ext pl-2 mb-5">
                <div class="equipe-exterieur col-md-8 col-lg-12 col-xl-8 order-2 order-md-1 order-lg-2 order-xl-1 py-2 px-0">
                    <a href="{{ $match->href_equipe_ext }}" class="text-white">{{ $match->equipe_ext->nom }}</a>
                </div>
                <div class="col-md-4 col-lg-12 col-xl-4 order-1 order-md-2 order-lg-1 order-xl-2 py-2 px-0">
                    <a href="{{ $match->href_equipe_ext }}"><img src="{{ $match->fanion_equipe_ext }}" alt="{{ $match->equipe_ext->nom }}" class="fanion-match"></a>
                </div>
            </div>

            @if (count($errors) > 0)
                <div class="col-12 p-3">
                    <div class="col-12 p-3 alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">×</button>  
                        <strong>Erreur !</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="col-12 d-flex justify-content-center align-items-center">
                <div class="col-lg-6 alert alert-danger text-dark p-3 d-none" id="messageErreur"></div>
            </div>

            <div class="col-12 text-center p-3">
                <button class="btn btn-danger px-5">Valider</button>
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
$(document).ready(function(){
    //
    verifierMonFormulaireEnJS('formulaire')
})
</script>
@endsection
