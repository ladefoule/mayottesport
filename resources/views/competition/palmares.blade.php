@extends('layouts.competition')

@section('title', $competition . ' - Le palmarès')

@section('content')
<div class="p-lg-3 h-100">
    <div class="row m-0 bg-white h-100">
        <div class="col-12 p-3">
            <h1 class="h4 text-center col-12">{{ $competition . ' - Le palmarès'}}</h1>
        </div>
        <div class="col-12 d-flex flex-wrap px-2">
            <table class="table table-striped text-center font-weight-bold">
                <tbody>
                    @foreach ($champions as $champion)
                    <tr>
                        <td>{{ $champion->saison }}</td>
                        <td>{{ $champion->equipe->nom }}</td>
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
