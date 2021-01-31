@extends('layouts.competition')

@section('title', $title)

@section('content')
<div class="p-lg-3 {{-- h-100 --}}">
    <div class="row m-0 bg-white shadow-div {{-- h-100 --}}">
        <div class="col-12 p-0">
            <div class="col-12 p-4">
                <h1 class="h4 text-center col-12">{{ $competition->nom . ' - Le palmarès'}}</h1>
            </div>
            <div class="col-12 d-flex flex-wrap align-items-start">
                <table class="table text-center">
                    <thead class="bg-light">
                        <tr>
                            <th>Saison</th>
                            <th>Vainqueur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($saisons as $saison)
                        <tr>
                            <td>{{ $saison->nom }}</td>
                            <td>{{ $saison->equipe_id ? index('equipes')[$saison->equipe_id]->nom : '' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-12 m-auto py-3 px-2">
            @include('pub.google-display-responsive')
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function(){

})
</script>
@endsection
