@push('head')
<style>
    .panel-body {
        background-color: #fff;
    }
</style>
@endpush

@extends('crudbooster::admin_template')
@section('content')
<style>
        tr:nth-child(odd){
            background-color: #F9F9F9;
            border
        }

        tr td:first-child{
            width: 25%;
            font-weight: bold;
        }
</style>
<!-- Your html goes here -->
<p class="noprint"><a title="Main Module" href="{{ CRUDBooster::mainpath() }}"><i class="fa fa-chevron-circle-left "></i> &nbsp; Back To Capsule Merge</a></p> 
<div class="panel-default">
    <div class="panel-heading">
        <i class="fa fa-eye"></i><strong> {{CRUDBooster::getCurrentModule()->name}}</strong>
    </div>
    <div class="panel-body">
        <div class="table table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td>Reference #</td>
                        <td colspan="5">{{ $capsule_merge->reference_number }}</td>
                    </tr>
                    <tr>
                        <td>Created By</td>
                        <td colspan="5">{{ $capsule_merge->cms_name }}</td>
                    </tr>
                    <tr>
                        <td>Created Date</td>
                        <td colspan="5">{{ $capsule_merge->created_at }}</td>
                    </tr>
                </thead>
            </table>
            <table class="table table-striped">
                <thead>
                    <tr>
                        {{-- <th>JAN Number</th> --}}
                        <th>JAN Number</th>
                        <th>Item Description</th>
                        <th>Location</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($capsule_lines as $capsule_line)
                    <tr>
                        <td>{{ $capsule_line->item_code }}</td>
                        <td>{{ $capsule_line->from_item_description }}</td>
                        <td>{{ $capsule_line->location_name }}</td>
                        <td>{{ $capsule_line->from_machine_serial_number }}</td>
                        <td>{{ $capsule_line->to_machine_serial_number }}</td>
                        <td>{{ $capsule_line->qty }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Back</a>
    </div>
</div>
@endsection