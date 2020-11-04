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

    {{-- Modèle de bloc de commentaire --}}
    <article class="comment d-none" id="model-bloc-comm">
        <span class="comment-img">
            <img src="http://cdn.onlinewebfonts.com/svg/img_266351.png" alt="" width="50" height="50">
        </span>
        <div class="comment-body">
            <div class="text"></div>
            <p class="attribution">Posté par <span class="nom text-danger"></span> le <span class="date"></span></p>
        </div>
    </article>

    <div class="row m-0 mb-3">
        <div class="w-100 card">
            <div class="card-header">
                Les commentaires
            </div>
            @guest
                <div class="pl-5 ml-5 py-3">
                    <a href="{{ route('login') }}">Connectez-vous</a> ou <a href="{{ route('register') }}">Inscrivez-vous</a> pour commenter
                </div>

                {{-- La ligne en dessous permettra à l'utilisateur de revenir sur cette page après la connexion --}}
                <?php Session::put('url.intended', request()->url()); ?>
            @endguest
            <div class="card-body d-flex flex-wrap">
                @auth
                    <form action="" id="commenter" class="w-100">
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
                                <button class="mt-2 btn-sm btn-success">Envoyer</button>
                            </div>
                        </article>
                    </form>
                @endauth
                <section class="comments w-100">
                    @foreach ($match['commentaires'] as $commentaire)
                    <article class="comment">
                        <span class="comment-img">
                            <img src="http://cdn.onlinewebfonts.com/svg/img_266351.png" alt="" width="50" height="50">
                        </span>
                        <div class="comment-body">
                            <div class="text">
                                {{ $commentaire->comm }}
                            </div>
                            <p class="attribution">Posté par <span class="nom text-danger">{{ $commentaire->pseudo }}</span> le <span class="date">{{ $commentaire->created_at->format('d/m/Y à H:i:s') }}</span></p>
                        </div>
                    </article>
                    @endforeach

                </section>
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
    var comments = qs('.comments')
    var model = qs('#model-bloc-comm')
    $('#commenter').on('submit', function (e) {
        e.preventDefault()
        comm = commentaire.value
        if(commentaire.length < 2)
            return false

        $.ajax({
            method:'POST',
            url: "<?php echo route('comment') ?>",
            data:{comm, match_id, user_id, _token},
            success:function(data){
                commentaire.value = ''
                let bloc = model.cloneNode(true)
                bloc.classList.remove('d-none')
                bloc.removeAttribute('id')
                qs('.text', bloc).innerHTML = data.comm
                qs('.nom', bloc).innerHTML = data.nom
                qs('.date', bloc).innerHTML = data.date
                comments.prepend(bloc)
            }
        })
    })
})
</script>
@endsection
