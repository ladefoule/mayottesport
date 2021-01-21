@extends('layouts.competition')

@section('title', $competition->nom . ' - Le palmarès')

@section('content')
<div class="p-lg-3">
    <div class="row m-0 bg-white">
        <div class="col-12 p-3">
            <h1 class="h4 text-center col-12">{{ $competition->nom . ' - Le palmarès'}}</h1>
        </div>
        <div class="col-12 d-flex flex-wrap px-2 align-items-start">
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th>Saison</th>
                        <th>Champion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($saisons as $saison)
                    <tr>
                        <td>{{ $competition->nom . ' ' . $saison->nom }}</td>
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
