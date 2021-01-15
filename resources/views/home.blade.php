@extends('layouts.site')

@section('title', 'Accueil de notre site')
{{-- 
@section('content')
    <div class="container justify-content-center bg-white mt-4 border border-danger m-auto h-100 w-100">
        <div class="col-12 text-center p-3 d-none">
            <h1 class="h4">MayotteSport.com : l'actualité sportive de Mayotte</h1>
        </div>
    </div>
@endsection

@section('section-droite')
<div> --}}
    @include('modele-onglets')
{{-- </div>   
@endsection --}}

@section('script')
    <script>
        $(document).ready(function() {
            ongletSwitch()
        })
    </script>
@endsection
