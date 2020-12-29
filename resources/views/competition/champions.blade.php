@extends('layouts.competition')

@section('title', $competition . ' - Le palmarès')

@section('content')

<div class="row">
    <div class="col-12 d-flex flex-wrap justify-content-center align-items-center p-3">
        <h1 class="h4 text-center col-12">{{ $competition . ' - Le palmarès'}}</h1>
    </div>
    <div class="col-lg-9 d-flex flex-wrap px-2">
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
    <div class="d-flex col-lg-3 justify-content-center px-2 pb-2">
        <div class="border h-100 w-100 p-3 text-center">
            PUB
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
