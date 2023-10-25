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
</style>
@endsection

{{-- Define the content to be included in the 'content' section --}}
@section('content')
<div class="responsive_table">
    <table id="myTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Action</th>
                <th>Ref#</th>
                <th>Token Value</th>
                <th>Peso</th>
                <th>Float Type</th>
                <th>Created by</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entry_token as $entry_data)
                <tr>
                    <td>FH-{{ str_pad($entry_data->id, 8, '0', STR_PAD_LEFT) }}</td>
                    <td>FH-{{ str_pad($entry_data->id, 8, '0', STR_PAD_LEFT) }}</td>
                    <td>FH-{{ str_pad($entry_data->id, 8, '0', STR_PAD_LEFT) }}</td>
                    <td>FH-{{ str_pad($entry_data->id, 8, '0', STR_PAD_LEFT) }}</td>
                    <td>FH-{{ str_pad($entry_data->id, 8, '0', STR_PAD_LEFT) }}</td>
                    <td>FH-{{ str_pad($entry_data->id, 8, '0', STR_PAD_LEFT) }}</td>
                    <td>FH-{{ str_pad($entry_data->id, 8, '0', STR_PAD_LEFT) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

{{-- Your Script --}}
@section('script-js')
<script>
    
    $(document).ready(function() {
        $('#myTable').DataTable({
            "searching": true
        });
    });
</script>
@endsection