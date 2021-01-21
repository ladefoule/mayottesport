@extends('layouts.competition')

@section('title', $competition->nom . ' - Le palmarès')

@section('content')
<div class="p-lg-3">
    <div class="row m-0 bg-white">
        <div class="col-12 p-3">
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
                        <td>{{ $saison->equipe ? $saison->equipe->nom : '' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
