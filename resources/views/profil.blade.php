@extends('layouts.site')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 p-3">
        <div class="card">
            <div class="card-header">Tableau de bord</div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                Vous êtes connecté !
            </div>
        </div>
    </div>
</div>
@endsection
