@extends('layouts.competition')

@section('title', $competition . ' - ' . $sport)

@section('content')
<div class="row mt-lg-3">

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