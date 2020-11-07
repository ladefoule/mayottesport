@extends('layouts.crud')

@section('title', $title)

@section('content')
<div class="row card mx-1">
    <div class="card-header d-flex align-items-center">
        <span class="d-inline mr-3" style="font-size: 1.6em"><i class="fas fa-database"></i> {{ $h1 }}</span>
        <a href="{{ $hrefs['index'] }}" title="Liste" class="text-decoration-none">
            <button class="btn-sm btn-warning text-body">
                {!! \Config::get('constant.boutons.lister') !!}
                <span class="d-none d-lg-inline ml-1">Liste</span>
            </button>
        </a>
        <a href="{{ $hrefs['update'] }}" title="Editer" class="text-decoration-none ml-2">
            <button class="btn-sm btn-info text-white">
                {!! \Config::get('constant.boutons.editer') !!}
                <span class="d-none d-lg-inline ml-1">Éditer</span>
            </button>
        </a>
        <a href="{{ $hrefs['delete'] }}" title="Supprimer" class="text-decoration-none ml-2">
            <button class="btn-sm btn-danger">
                {!! \Config::get('constant.boutons.supprimer') !!}
                <span class="d-none d-lg-inline ml-1">Supprimer</span>
            </button>
        </a>
        <a href="" class="back d-none d-sm-inline position-absolute text-decoration-none text-dark pr-3" style="right:0"><i class="fas fa-long-arrow-alt-left"></i> retour</a>
    </div>
    <div class="card-body">
        @foreach ($donnees as $infos)
            <ul class="list-group mb-3">
                <li class="list-group-item disabled" aria-disabled="true">{{ $infos['label'] }}</li>
                <li class="list-group-item"><?= $infos['valeur'] ?></li>
            </ul>
        @endforeach
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    retour()
})
</script>
@endsection
