@extends('layouts.football')

@section('title', $match['nom'])

@section('content')
<div class="row text-white bloc-match bloc-match-football my-3 mx-0 rounded">
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
            {{ $match['championnat'] . ' : ' . $match['journee'] }}
        </div>
    </div>
</div>

<?php
dd(uniqid('sports', true));
?>

<div class="row m-0 mb-3">
    <div class="w-100 card">
        <div class="card-header">
            Les commentaires
        </div>
        <div class="card-body d-flex">
            <section class="comments">
                <article class="comment">
                  <a class="comment-img" href="#non">
                    <img src="http://cdn.onlinewebfonts.com/svg/img_266351.png" alt="" width="50" height="50">
                  </a>
                  <div class="comment-body">
                    <div class="text">
                      {{ $commentaire ?? 'Un commentaire' }}
                    </div>
                    <p class="attribution">Posté par <a href="#non">{{ $nom ?? 'TEST' }}</a> le {{ $date ?? '12/12/2020 à 12:52' }}</p>
                  </div>
                </article>
                {{-- <article class="comment">
                  <a class="comment-img" href="#non">
                    <img src="https://pbs.twimg.com/profile_images/444197466133385216/UA08zh-B.jpeg" alt="" width="50" height="50">
                  </a>
                  <div class="comment-body">
                    <div class="text">
                      <p>if you are interested for  more about me visited my profile on social page</p>
                      <p>To visit me you can click my name  <a target="_blank" href="http://www.facebook.com/besnik.hetemii">Besnik Hetemi</a> and send me frends request or send me a message in inbox</p>
                    </div>
                    <p class="attribution">by <a href="#non">Besnik Hetemi</a> at 14:23pm, 4 Dec 2015</p>
                  </div>
                </article> --}}
            </section>
        </div>
      </div>
</div>
@endsection
