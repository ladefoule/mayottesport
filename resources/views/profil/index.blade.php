@extends('layouts.site')

@section('title', 'Mon compte')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex align-items-center px-2">
                <span class="d-inline mr-3 crud-titre">{!! config('constant.boutons.user') !!} Mon compte </span>
                <a href="{{ route('profil.update') }}" title="Modifier" class="text-decoration-none ml-2">
                    <button class="btn-sm btn-info text-white">
                        {!! config('constant.boutons.editer') !!}
                        <span class="d-none d-lg-inline ml-1">Modifier</span>
                    </button>
                </a>
                <a href="{{ route('profil.delete') }}" title="Supprimer" id="supprimer" class="text-decoration-none ml-2">
                    <button class="btn-sm btn-danger">
                        {!! config('constant.boutons.supprimer') !!}
                        <span class="d-none d-lg-inline ml-1">Supprimer mon compte</span>
                    </button>
                </a>
            </div>

            <div class="card-body">
                <div class="list-group mb-3">
                    <span class="font-weight-bold">NOM :</span>
                    {{ $user->name . ' ' . $user->first_name }}
                </div>

                <div class="list-group mb-3">
                    <span class="font-weight-bold">PSEUDO :</span>
                    {{ $user->pseudo }}
                </div>

                <div class="list-group mb-3">
                    <span class="font-weight-bold">EMAIL :</span>
                    {{ $user->email }}
                    {{-- <a href="{{ route('password.email') }}">Modifier mon email</a> --}}
                </div>

                <div class="list-group mb-3">
                    <span class="font-weight-bold">MOT DE PASSE :</span>
                    ********
                    <a href="{{ route('password.request') }}">Changer de mot de passe</a>
                </div>

                <div class="list-group mb-3">
                    <span class="font-weight-bold">Niveau d'accès :</span>
                    {{ $user->role->nom }}
                </div>

                <div class="list-group mb-3">
                    <span class="font-weight-bold">REGION :</span>
                    {{ $user->region->nom }}
                </div>

                <div class="list-group mb-3">
                    <span class="font-weight-bold">INSCRIS LE :</span>
                    {{ $user->created_at->format('d/m/Y') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#supprimer').on('click', function(e){
        let ok = confirm("Supprimer définitivement mon compte?")
        if(! ok){
            e.preventDefault()
        }
    })
})
</script>
@endsection
