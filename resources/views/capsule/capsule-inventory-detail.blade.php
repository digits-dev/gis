@push('head')
<style>
    .panel-body {
        background-color: #fff;
    }
</style>
@endpush

@extends('crudbooster::admin_template')
@section('content')
<p class="noprint">
    <a title='Return' href="{{ CRUDBooster::mainPath() }}">
        <i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}
    </a>
</p>
<div class="panel-default">
    <div class="panel-heading">
        <i class="fa fa-eye"></i><strong> {{CRUDBooster::getCurrentModule()->name}}</strong>
    </div>
    <div class="panel-body">
        <div class="table table-responsive">
            <table class=" table table-striped">
                <thead>
                    <tr>
                        <th>JAN Number</th>
                        <th>Digits Code</th>
                        <th>Item Description</th>
                        <th>Location</th>
                        <th>From</th>
                        <th>Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lines as $line)
                    <tr>
                        <td>{{ $item->digits_code }}</td>
                        <td>{{ $item->digits_code2 }}</td>
                        <td>{{ $item->item_description }}</td>
                        <td>{{ $item->location_name }}</td>
                        <td>{{ $line->sub_location ?? $line->serial_number }}</td>
                        <td>{{ $line->qty }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="4"></td>
                        <td class="text-bold">Total</td>
                        <td class="text-bold">{{ $item->onhand_qty }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel-footer">
        <a href='{{ CRUDBooster::mainpath() }}' class='btn btn-default'>Back</a>
    </div>
</div>

@endsection