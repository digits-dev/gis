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

        #other-detail th, td {
            border: 1px solid rgba(000, 0, 0, .5);
            padding: 8px;
        }
        #collected-token th, td {
            border: 1px solid rgba(000, 0, 0, .5);
            padding: 8px;
        }

        .plus{
                font-size:20px;
        }
        #add-Row{
            border:none;
            background-color: #fff;
        }
        
        .iconPlus{
            background-color: #3c8dbc: 
        }
        
        .iconPlus:before {
            content: '';
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            /* border: 1px solid rgb(194, 193, 193); */
            font-size: 35px;
            color: white;
            background-color: #dd4b39;
    
        }
        #bigplus{
            transition: transform 0.5s ease 0s;
        }
        #bigplus:before {
            content: '\FF0B';
            background-color: #dd4b39: 
            font-size: 50px;
        }
        #bigplus:hover{
            /* cursor: default;
            transform: rotate(180deg); */
            -webkit-animation: infinite-spinning 1s ease-out 0s infinite normal;
                animation: infinite-spinning 1s ease-out 0s infinite normal;
            
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
<div class='panel-heading' style="background-color:#3c8dbc; color:#fff">Capsule Swap Detail</div>

    <div class='panel-body'>
        <div class="col-md-12">
        
                <div class="form-group">
                    <label class="control-label"> Reference Number</label>
                    <input type="text" class="form-control finput" value="{{ $detail_header->reference_number }}" readonly>
                </div>
            
                <div class="form-group">
                    <label class="control-label"> Location</label>
                    <input type="text" class="form-control finput" value="{{ $detail_header->location_name }}" readonly>
                </div>
       
                <div class="form-group">
                    <label class="require control-label"> Date created</label>
                    <input type="text" class="form-control finput" value="{{ $detail_header->capsule_swap_headers_created }}" readonly>
                </div>
            
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table" id="collected-token" style="width: 100%">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">Jan#</th> 
                            <th width="25%" class="text-center">Item Description</th> 
                            <th width="5%" class="text-center">From Machine</th> 
                            <th width="5%" class="text-center">To Machine</th>
                            <th width="5%" class="text-center">Qty</th>
                        </tr>  
                    </thead>
                               
                    <tbody id="bodyTable">    
                        @foreach($detail_body as $row)
                            <tr>
                                <td style="text-align:center" height="10">
                                    {{$row->jan_no}}                               
                                </td>
                                <td style="text-align:center" height="10">
                                    {{$row->item_description}}                               
                                </td>
                                <td style="text-align:center" height="10">
                                    {{$row->from_machine_serial}}                               
                                </td>
                                <td style="text-align:center" height="10" class="qty">
                                    {{$row->to_machine_serial}}                               
                                </td>
                                <td style="text-align:center" height="10">
                                    {{abs($row->qty)}}                               
                                </td>
                            </tr>
                        @endforeach                                            
                    </tbody>
                </table>
            </div>   
        </div>

    </div>
    <div class='panel-footer'>
        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.back') }}</a>
    </div>

</div>

@endsection
@push('bottom')

<script type="text/javascript">

    function preventBack() {
        window.history.forward();
    }
     window.onunload = function() {
        null;
    };
    setTimeout("preventBack()", 0);
    $('#location_id').select2();

</script>
@endpush