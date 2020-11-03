@extends('layouts.football')

@section('title', $match['nom'])

@section('content')
    <div class="row text-white bloc-match bloc-match-football my-3 mx-0 rounded py-4">
        <div class="row mx-0 col-5 d-flex justify-content-between align-items-center bloc-equipe-dom p-1">
            <div class="col-lg-4 d-lg-inline py-2 px-0">
                <img src="{{ $match['fanionDom'] }}" alt="{{ $match['equipeDom'] }}" class="fanion-match">
            </div>
            <div class="equipe col-lg-8 d-lg-inline py-2 px-0">
                {{ $match['equipeDom'] }}
            </div>
        </div>
        <div class="col-2 bloc-score d-flex align-items-center justify-content-around p-0">
            <span class="w-100 text-center">{!! $match['score'] !!}</span>
        </div>
        <div class="row mx-0 col-5 d-flex justify-content-between align-items-center bloc-equipe-ext p-1">
            <div class="equipe col-lg-8 d-lg-inline order-2 order-lg-1 py-2 px-0">
                {{ $match['equipeExt'] }}
            </div>
            <div class="col-lg-4 d-lg-inline order-1 order-lg-2 py-2 px-0">
                <img src="{{ $match['fanionExt'] }}" alt="{{ $match['equipeExt'] }}" class="fanion-match">
            </div>
        </div>

        @if (!$match['accesBloque'])
            <div class="col-12 d-flex align-items-center justify-content-center p-3">
                <a href="{{ $match['lienResultat'] }}"><button class="btn btn-success">Modifier le résultat</button></a>
            </div>
            <div class="col-12 text-center">
                <a href="{{ $match['lienHoraire'] }}"><button class="btn btn-primary">Modifier l'horaire</button></a>
            </div>
        @endif

        <div class="row col-12 d-flex justify-content-center align-items-center mx-0 p-3">
            <div class="col-12 text-center">
                Le {{ $match['dateFormat'] }}
            </div>
            <div class="col-12 text-center">
                {{ $match['competition'] . ' : ' . $match['journee'] }}
            </div>
        </div>
    </div>

    <div class="row m-0 mb-3">
        <div class="w-100 card">
            <div class="card-header">
                Les commentaires
            </div>
            <div class="card-body d-flex flex-wrap">
                <section class="comments w-100">
                    <article class="comment">
                        <a class="comment-img" href="#non">
                            <img src="http://cdn.onlinewebfonts.com/svg/img_266351.png" alt="" width="50" height="50">
                        </a>
                        <div class="comment-body">
                            <div class="text">
                                {{ $commentaire ?? 'Un commentaire' }}
                            </div>
                            <p class="attribution">Posté par <a href="#non">{{ $nom ?? 'TEST' }}</a> le
                                {{ $date ?? '12/12/2020 à 12:52' }}</p>
                        </div>
                    </article>
                    @auth
                       <form action="" id="commenter">
                           @csrf
                            <article class="comment">
                                <a class="comment-img" href="#non">
                                    <img src="https://pbs.twimg.com/profile_images/444197466133385216/UA08zh-B.jpeg" alt=""
                                        width="50" height="50">
                                </a>
                                <div class="comment-body">
                                    <textarea class="text form-control" name="comm" rows="2" id="commentaire"></textarea>
                                    <input type="hidden" name="match_id" id="match_id" value="{{ $match['id'] }}">
                                    <input type="hidden" name="user_id" id="user_id" value="{{ \Auth::id() }}">
                                    <button class="mt-2 btn-sm btn-success">Valider</button>
                                </div>
                            </article>
                        </form>
                    @endauth
                </section>
                @guest
                    <div class="pl-5 ml-5">
                        <a href="{{ route('login') }}">Connectez-vous</a> pour commenter
                    </div>
                @endguest
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
$(document).ready(function(){
    var form = qs('#commenter')
    var commentaire = qs('#commentaire')
    var match_id = qs('#match_id').value
    var user_id = qs('#user_id').value
    var _token = qs('[name=_token]').value
    $('#commenter').on('submit', function (e) {
        e.preventDefault()
        comm = commentaire.value
        if(commentaire.length < 5){
            cl('Trop court')
            return false
        }

        $.ajax({
            method:'POST',
            url: "<?php route('comment') ?>",
            data:{comm, match_id, user_id, _token},
            success:function(data){
                cl(data)
            }
        })
    })
})
</script>
@endsection
