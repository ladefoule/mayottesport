@extends('layouts.competition')

@section('title', $match['title'])

@section('content')
    <div class="row text-white bloc-match bloc-match-football mx-0 rounded py-4">
        <div class="row mx-0 col-5 d-flex justify-content-between align-items-center bloc-equipe-dom p-1">
            <div class="col-lg-4 d-lg-inline py-2 px-0">
                <a href="{{ $match['href_eq_dom'] }}"><img src="{{ $match['fanion_eq_dom'] }}" alt="{{ $match['nom_eq_dom'] }}" class="fanion-match"></a>
            </div>
            <div class="equipe-domicile col-lg-8 d-lg-inline py-2 px-0">
                <a href="{{ $match['href_eq_dom'] }}" class="text-white">{{ $match['nom_eq_dom'] }}</a>
            </div>
        </div>
        <div class="col-2 bloc-score d-flex align-items-center justify-content-around p-0">
            <span class="w-100 text-center font-weight-bold">{!! $match['score'] !!}</span>
        </div>
        <div class="row mx-0 col-5 d-flex justify-content-between align-items-center bloc-equipe-ext p-1">
            <div class="equipe-exterieur col-lg-8 d-lg-inline order-2 order-lg-1 py-2 px-0">
                <a href="{{ $match['href_eq_ext'] }}" class="text-white">{{ $match['nom_eq_ext'] }}</a>
            </div>
            <div class="col-lg-4 d-lg-inline order-1 order-lg-2 py-2 px-0">
                <a href="{{ $match['href_eq_ext'] }}"><img src="{{ $match['fanion_eq_ext'] }}" alt="{{ $match['nom_eq_ext'] }}" class="fanion-match"></a>
            </div>
        </div>

        @if (! $match['acces_bloque'])
            <div class="col-12 d-flex align-items-center justify-content-center p-3">
                <a href="{{ $match['href_resultat'] }}"><button class="btn btn-success">Modifier le résultat</button></a>
            </div>
            <div class="col-12 text-center">
                <a href="{{ $match['href_horaire'] }}"><button class="btn btn-primary">Modifier l'horaire</button></a>
            </div>
        @endif

        <div class="row col-12 d-flex justify-content-center align-items-center mx-0 p-3">
            <div class="col-12 text-center">
                Le {{ $match['date_format'] }}
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
            <p class="attribution">Posté par <span class="nom text-danger"></span> le <span class="date"></span> (<a href="" class="supprimer">Supprimer</a>)</p>
        </div>
    </article>
    {{-- Fin Modèle de bloc de commentaire --}}

    <div class="row m-0 mt-3">
        <div class="w-100 card">
            <div class="card-header">
                Les commentaires
            </div>
            @guest
                <div class="pl-3 py-3">
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
                            <p class="attribution">Posté par <span class="nom text-danger">{{ $commentaire->pseudo }}</span> le <span class="date">{{ $commentaire->created_at->format('d/m/Y à H:i:s') }}</span>@if($commentaire->user_id == \Auth::id()) (<a href="" class="supprimer" data-id="{{ $commentaire->id }}">Supprimer</a>)@endif</p>
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

    // Ajout de commentaire
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
                qs('.supprimer', bloc).dataset.id = data.id
                comments.prepend(bloc)
            }
        })
    })

    // Suppression de commentaire
    $('.comments').on('click', '.supprimer', function (e) {
        e.preventDefault()
        var id = this.dataset.id
        var href = this

        $.ajax({
            method:'POST',
            url: "<?php echo route('comment.delete') ?>",
            data:{id, _token},
            success:function(data){
                href.closest('article').remove() // Suppression du bloc de commentaire
            }
        })
    })
})
</script>
@endsection
