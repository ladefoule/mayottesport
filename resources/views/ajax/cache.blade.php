@extends('layouts.site')

<?php
    // $crudTableId = 1;
    // $instanceId = 1;
?>

@section('content')
<div>
    {{-- @csrf
    {{ $crudTableId ?? request()->crudTableId }}
    {{ $instanceId ?? request()->instanceId }} --}}
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // alert('OK')
    $.ajax({
        type: 'GET',
        url: "<?php echo route('caches.reload-crud') ?>",
        data:{crud_table_id:"<?php echo $crudTableId ?>", instance_id:"<?php echo $instanceId ?>"},
        success:function(data){
            cl('OK')
            // alert('OK')
        }
    })
})
</script>
@endsection
