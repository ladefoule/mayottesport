@extends('layouts.competition')

@section('title', request()->competition->nom . ' - Calendrier et résultats - '.niemeJournee($journee->numero).' - ' . request()->sport->nom)

@section('content')

<div class="row d-flex flex-wrap m-0 my-3 bg-white rounded p-3">
    <div class="col-12 d-flex flex-wrap justify-content-center align-items-center pb-3">
        <h1 class="h4 text-center p-2 col-12">{{ request()->competition->nom . ' - Le palmarès'}}</h1>
    </div>
    <div class="col-lg-8 d-flex flex-wrap p-0">
        <table>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
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
