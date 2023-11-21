<!-- First, extends to the CRUDBooster Layout -->
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
    <div class='panel panel-default'>
        <div class='panel-heading'><i class="fa fa-circle-o"></i> Detail Capsule Merge</div>
        <div class='panel-body'>      
            <div class="table-responsive">
                <table class="table table-strip">
                    <tbody>
                        <tr>
                            <td>Reference #</td>
                            <td>{{ $capsule_merge->reference_number }}</td>
                        </tr>
                        <tr>
                            <td>JAN #</td>
                            <td>{{ $capsule_merge->item_code }}</td>
                        </tr>
                        <tr>
                            <td>Qty</td>
                            <td>{{ $capsule_merge->qty }}</td>
                        </tr>
                        <tr>
                            <td>Location</td>
                            <td>{{ $capsule_merge->location_name }}</td>
                        </tr>
                        <tr>
                            <td>From</td>
                            <td>{{ $capsule_merge->from_machine_serial_number }}</td>
                        </tr>
                        <tr>
                            <td>To</td>
                            <td>{{ $capsule_merge->to_machine_serial_number }}</td>
                        </tr>
                        <tr>
                            <td>Created By</td>
                            <td>{{ $capsule_merge->cms_name }}</td>
                        </tr>
                        <tr>
                            <td>Created Date</td>
                            <td>{{ $capsule_merge->created_at }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
@endsection