@extends('layouts.admin')

@section('title', $h1)

@section('content')
<div class="row card mx-0">
    <div class="card-header d-flex align-items-center">
        <span class="d-inline mr-3 crud-titre">{!! \Config::get('constant.boutons.database') !!} {{ $h1 }}</span>
        <a href="{{ route('journees.multi.edit', ['id' => $saisonId]) }}" title="Editer" class="text-decoration-none">
            <button class="btn-sm btn-info text-white">
                {!! \Config::get('constant.boutons.editer') !!}
                <span class="d-none d-lg-inline ml-1">Editer</span>
            </button>
        </a>
        <a href="" class="back d-none d-sm-inline position-absolute text-decoration-none text-dark pr-3" style="right:0">
            {!! config('constant.boutons.retour') !!} retour
        </a>
    </div>
    <div class="card-body">
        <ul class="list-group mb-3">
            <li class="list-group-item disabled" aria-disabled="true">Saison</li>
            <li class="list-group-item"><?= $saison ?></li>
        </ul>

        <div class="form-row d-flex justify-content-center">
            @foreach ($journees as $journee)
                <div class="col-12 form-row justify-content-center mb-3">
                    <div class="col-4">
                        <ul class="list-group">
                            <li class="list-group-item disabled" aria-disabled="true">Journée</li>
                            <li class="list-group-item">{{ $journee->numero }}</li>
                        </ul>
                    </div>
                    <div class="col-8">
                        <ul class="list-group">
                            <li class="list-group-item disabled" aria-disabled="true">Date</li>
                            <li class="list-group-item">{{ date('d/m/Y', strtotime($journee->date)) }}</li>
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

})
</script>
@endsection
