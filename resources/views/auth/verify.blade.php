@extends('layouts.site')

@section('title', "Vérifiez votre adresse email")

@section('content')
<div class="row justify-content-center min-height">
    <div class="col-md-8 p-3">
        <div class="card">
            <div class="card-header">Vérifiez votre adresse email</div>

            <div class="card-body">
                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        Un nouveau lien de vérification a été envoyé à votre adresse email.
                    </div>
                @endif

                Avant de poursuivre, veuillez vérifier votre email avec le lien de vérification.
                Si vous n'avez pas reçu le mail,
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">cliquez ici pour en recevoir un autre</button>.
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
