@extends('layouts.site')

@section('title', 'Accueil de notre site')

@section('content')
    <div class="row justify-content-center bg-white">
        <div class="col-12 text-center p-3">
            <h1 class="h4">MayotteSport.com : l'actualité sportive de Mayotte</h1>
        </div>

        @include('modele-onglets')
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            ongletSwitch()
        })
    </script>
@endsection
