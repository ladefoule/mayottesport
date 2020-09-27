{{-- @extends('layouts.site')

@section('title', 'Non trouvé')
@section('code', '404')
@section('content')
<div class="row d-flex justify-content-center pt-4">
    <h1 class="h4 text-center">
        <span class="text-danger text-underline"><u>Erreur 404</u></span> : {{ $exception->getMessage() ?: 'Non trouvé' }}
    </h1>
</div>
@endsection --}}

@extends('errors::illustrated-layout')

@section('title', 'Non trouvé')
@section('code', '404')
@section('message', $exception->getMessage() ?: 'Non trouvé')
