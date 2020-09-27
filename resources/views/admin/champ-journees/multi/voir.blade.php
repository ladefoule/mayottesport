@php
use App\ChampJournee;
@endphp

@extends('layouts.gestion-site')

@section('title', $h1)

@section('content')
<div class="row card mx-1">
    <div class="card-header d-flex align-items-center">
        <span class="d-inline mr-3" style="font-size: 1.6em">{!! \Config::get('constant.boutons.database') !!} {{ $h1 }}</span>
        <a href="{{ route('champ-journees.multi.editer', ['id' => $saisonId]) }}" title="Editer" class="text-decoration-none">
            <button class="btn-sm btn-info text-white">
                {!! \Config::get('constant.boutons.editer') !!}
                <span class="d-none d-lg-inline ml-1">Editer</span>
            </button>
        </a>
        <a href="" class="back d-none d-sm-inline position-absolute text-decoration-none text-dark pr-3" style="right:0">
            <i class="fas fa-long-arrow-alt-left"></i> retour
        </a>
    </div>
    <div class="card-body">
        <ul class="list-group mb-3">
            <li class="list-group-item disabled" aria-disabled="true">Sport</li>
            <li class="list-group-item"><?= $sport ?></li>
        </ul>
        <ul class="list-group mb-3">
            <li class="list-group-item disabled" aria-disabled="true">Championnat</li>
            <li class="list-group-item"><?= $championnat ?></li>
        </ul>
        <ul class="list-group mb-3">
            <li class="list-group-item disabled" aria-disabled="true">Saison</li>
            <li class="list-group-item"><?= $champSaison ?></li>
        </ul>

        <div class="form-row d-flex justify-content-center">
            @foreach ($champJournees as $champJournee)
                @php
                $numero = $champJournee->numero;
                $date = $champJournee->date;
                @endphp
                <div class="col-12 form-row justify-content-center">
                    <div class="col-4 mb-3">
                        <ul class="list-group">
                            <li class="list-group-item disabled" aria-disabled="true">Journée</li>
                            <li class="list-group-item"><?= $numero ?></li>
                        </ul>
                    </div>
                    <div class="col-8">
                        <ul class="list-group">
                            <li class="list-group-item disabled" aria-disabled="true">Date</li>
                            <li class="list-group-item"><?= date('d/m/Y', strtotime($date)); ?></li>
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
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
