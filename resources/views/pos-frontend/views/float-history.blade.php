{{-- Please always check the current plugins, css, script in content.blade.php--}}

{{-- Extend the dashboard layout --}}
@extends('pos-frontend.components.content')

{{-- Your Plugins --}}
@section('plugins')
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
            @foreach ($entries as $entry)
                <tr>
                    <td></td>
                    <td></td>
                    <td>{{ $entry->token_value }}</td>
                    <td>{{ $entry->cash_value }}</td>
                    <td>{{ $entry->description }}</td>
                    <td>{{ $entry->created_by }}</td>
                    <td>{{ $entry->created_date }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Action</th>
                <th>Ref#</th>
                <th>Token Value</th>
                <th>Peso</th>
                <th>Float Type</th>
                <th>Created by</th>
                <th>Created Date</th>
            </tr>
        </tfoot>
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