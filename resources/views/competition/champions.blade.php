@extends('layouts.competition')

@section('title', $competition . ' - Le palmarès')

@section('content')

<div class="row d-flex flex-wrap bg-white rounded py-3">
    <div class="col-12 d-flex flex-wrap justify-content-center align-items-center pb-3">
        <h1 class="h4 text-center col-12">{{ $competition . ' - Le palmarès'}}</h1>
    </div>
    <div class="col-lg-8 d-flex flex-wrap px-2">
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
    <div class="col-lg-4 pl-5 pr-0 text-center">
        PUB
    </div>
</div>

@endsection

@section('script')
<script>
$(document).ready(function(){

})
</script>
@endsection
