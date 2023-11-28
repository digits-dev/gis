

@push('head')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style type="text/css">   
    .select2-selection__choice{
            font-size:14px !important;
            color:black !important;
    }
    .select2-selection__rendered {
        line-height: 31px !important;
    }
    .select2-container .select2-selection--single {
        height: 35px !important;
    }
    .select2-selection__arrow {
        height: 34px !important;
    }

    th, td {
        border: 1px solid rgba(000, 0, 0, .5) !important;
        padding: 8px;
    }


    @keyframes infinite-spinning {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    @media (min-width:729px){
        .panel-default{
            width:60% !important; 
            margin:auto !important;
        }
    }

</style>
@endpush
@extends('crudbooster::admin_template')
@section('content')
@if(g('return_url'))
<p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@else
<p class="noprint"><a title='Main Module' href='{{CRUDBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
@endif

<div class='panel panel-default'>
    <div class='panel-heading' style="background-color:#3c8dbc; color:#fff">Capsule Inventory Detail</div>
    <div class='panel-body'>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">JAN #</label>
                <input type="text" class="form-control finput" value="{{ $item->digits_code }}" readonly>
            </div>
        
            <div class="form-group">
                <label class="control-label">Digits Code</label>
                <input type="text" class="form-control finput" value="{{ $item->digits_code2 }}" readonly>
            </div>

            <div class="form-group">
                <label class="require control-label">Item Description</label>
                <input type="text" class="form-control finput" value="{{ $item->item_description }}" readonly>
            </div>

            <div class="form-group">
                <label class="require control-label">Location</label>
                <input type="text" class="form-control finput" value="{{ $item->location_name }}" readonly>
            </div>
        </div>
        <div class="col-md-12">
            <table class="table" style="width: 100%">
                <thead>
                    <tr>
                        <th class="text-center">From</th>
                        <th class="text-center">Qty</th>
                    </tr>  
                </thead>
                            
                <tbody id="bodyTable">    
                    @foreach ($lines as $line)
                        <tr>
                            <td class="text-center">
                                {{$line->sub_location ?? $line->serial_number}}                               
                            </td>
                            <td class="text-center">
                                {{$line->qty}}                               
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-center text-bold">Total</td>
                        <td class="text-center text-bold">{{ $item->onhand_qty }}</td>
                    </tr>                                         
                </tbody>
            </table>
        </div>   
    </div>
    <div class='panel-footer'>
        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.back') }}</a>
    </div>
</div>

@endsection