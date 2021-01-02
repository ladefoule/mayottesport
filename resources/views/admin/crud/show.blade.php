@extends('layouts.' . request()->layout)

@section('title', $title)

@section('content')
<div class="row card mx-0 my-3">
    <div class="card-header d-flex align-items-center px-2">
        <span class="d-inline mr-3 crud-titre">{!! \Config::get('listes.boutons.database') !!} {{ $h1 }}</span>
        <a href="{{ $hrefs['index'] }}" title="Liste" class="text-decoration-none">
            <button class="btn-sm btn-warning text-body">
                {!! \Config::get('listes.boutons.lister') !!}
                <span class="d-none d-lg-inline ml-1">Liste</span>
            </button>
        </a>
        <a href="{{ $hrefs['update'] }}" title="Editer" class="text-decoration-none ml-2">
            <button class="btn-sm btn-info text-white">
                {!! \Config::get('listes.boutons.editer') !!}
                <span class="d-none d-lg-inline ml-1">Éditer</span>
            </button>
        </a>
        <a href="{{ $hrefs['delete'] }}" title="Supprimer" class="text-decoration-none ml-2">
            <button class="btn-sm btn-danger">
                {!! \Config::get('listes.boutons.supprimer') !!}
                <span class="d-none d-lg-inline ml-1">Supprimer</span>
            </button>
        </a>
    </div>
    <div class="card-body pb-0 px-3 {{-- col-md-8 offset-md-2 --}}">
        @foreach ($donnees as $infos)
            <ul class="list-group mb-3">
                <li class="list-group-item disabled" aria-disabled="true">{{ $infos['label'] }}</li>
                <li class="list-group-item"><?= $infos['valeur'] ?></li>
            </ul>
        @endforeach
    </div>
</div>
@endsection
