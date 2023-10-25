{{-- Please always check the current plugins, css, script in content.blade.php--}}

{{-- Extend the dashboard layout --}}
@extends('pos-frontend.components.content')

{{-- Your Plugins --}}
@section('plugins')
<!--DATATABLE-->
<link rel="stylesheet" href="{{ asset('datatable/dataTables.responsive.min.css') }}">
<link rel="stylesheet" href="{{ asset('datatable/jquery.dataTables.min.css') }}">
<script src="{{ asset('datatable/jquery.dataTables.min.js') }}"></script>
@endsection

{{-- Your CSS --}}
@section('css')
<style>
    .btn {
        margin: 5px
    }
</style>
@endsection

{{-- Define the content to be included in the 'content' section --}}
@section('content')
<div class="responsive_table">
    <table id="myTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th style="text-align: center">Action</th>
                <th>Ref#</th>
                <th>Value</th>
                <th>Token</th>
                <th>Type</th>
                <th>Mode of Payments</th>
                <th>Location</th>
                <th>Created by</th>
                <th>Created Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($swap_histories as $swap_history)
                <tr>
                    <th><a class="btn btn-details" href="/pos_swap_history/{{ $swap_history->id }}""><i class="fa-solid fa-eye"></i></a>
                        <a class="btn btn-void ajax-request-icon" data-id="{{ $swap_history}}" href="">
                            @if ($swap_history->status != "VOID")
                            <i class="fa-solid fa-x"></i>
                        @endif
                        </a>
                    </th>
                   <th>{{ $swap_history->reference_number }}</th>
                   <th>{{ $swap_history->total_value }}</th>
                   <th>{{ $swap_history->token_value }}</th>
                   <th>{{ $swap_history->type_id }}</th>
                   <th>{{ $swap_history->mode_of_payments }}</th>
                   <th>{{ $swap_history->location_name}}</th>
                   <th>{{ $swap_history->created_by }}</th>
                   <th>{{ $swap_history->created_at }}</th>
                   <th>{{ $swap_history->status }}</th>
                </tr>
                @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="text-align: center">Action</th>
                <th>Ref#</th>
                <th>Value</th>
                <th>Token</th>
                <th>Type</th>
                <th>Mode of Payments</th>
                <th>Location</th>
                <th>Created by</th>
                <th>Created Date</th>
                <th>Status</th>
            </tr>
        </tfoot>
    </table>
</div>
@endsection

{{-- Your Script --}}
@section('script-js')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(function () {
        $('#myTable').DataTable({
            "order": [[1, 'asc']]
        });
    })

    $(document).ready(function () {
    $('.ajax-request-icon').on('click', function (e) {
        e.preventDefault(); // Prevent the default behavior of the link
        let current_id = $(this).attr('data-id');
        let swap_history_object = $.parseJSON(current_id);
        Swal.fire({
                title: "Are you sure you want to void this transaction? ",
                icon: 'warning',
                html: 'Reference #:' + ' ' + swap_history_object.reference_number + '<br>' + 'Value:' + ' ' + swap_history_object.total_value + '<br>' + 'Token:' + ' ' + swap_history_object.token_value,
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                denyButtonText: `Don't save`,
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    type: 'GET', 
                    url: 'pos_swap_history/edit/'+swap_history_object.id,
                    data: { },
                success: function (res) {
                    const data = JSON.parse(res);
                    console.log(data)
                    if(data.message == 'success'){
                        Swal.fire(
                            'Void Successfuly',
                            '',
                            'success'
                        )
                    }
                    else{
                        Swal.fire(
                            'Already void!',
                            '',
                            'warning'
                        )
                    }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                    });
                }
                })
        
    });
});
</script>
@endsection