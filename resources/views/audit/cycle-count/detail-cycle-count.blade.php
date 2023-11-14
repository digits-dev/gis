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
                width:40% !important; 
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
<div class='panel-heading' style="background-color:#3c8dbc; color:#fff">
    Cycle Count(Capsules) Detail
</div>

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
                    <label class="require control-label"> Sub Location</label>
                    <input type="text" class="form-control finput" value="{{ $detail_header->description }}" readonly>
                </div>
            
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table" id="collected-token">
                    <tbody id="bodyTable">    
                            <tr>
                                <th width="10%" class="text-center">Machine</th> 
                                <th width="10%" class="text-center">Machine no of tokens</th> 
                                <th width="10%" class="text-center">Digits Code</th> 
                                <th width="10%" class="text-center">Qty</th>
 
                            </tr>      
                        @foreach($detail_body as $row)
                                <tr style="background-color: #f8d7da; color:#721c24">
                                    <td style="text-align:center" height="10">
                                        {{$row->serial_number}}                               
                                    </td>
                                    <td style="text-align:center" height="10">
                                        {{$row->no_of_token}}                               
                                    </td>
                                    <td style="text-align:center" height="10">
                                        {{$row->digits_code}}                               
                                    </td>
                                    <td style="text-align:center" height="10" class="qty">
                                        {{$row->qty}}                               
                                    </td>
                                </tr>
                        @endforeach                                            
                    </tbody>
                </table>
            </div>   
        </div>

        @if( $detail_header->received_by != null )
            <div class="row">
                <div class="col-md-12">
                    <table style="width:100%">
                        <tbody id="footer">
                            <tr>
                                <th class="control-label col-md-2">{{ trans('message.form-label.received_by') }}:</th>
                                <td class="col-md-4">{{$detail_header->receiver_name}} / {{$detail_header->received_at}}</td> 
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
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


    var tds = document.getElementById('collected-token').getElementsByTagName('td');
    var qty = 0;

    for (var i = 0; i < tds.length; i++) {
        if(tds[i].className == 'qty') {
            qty += isNaN(tds[i].innerHTML) ? 0 : parseFloat(tds[i].innerHTML);
        }
    }
    document.getElementById('collected-token').innerHTML +=
    '<tr>'+
            '<td colspan="3" style="text-align:center">'+
                '<strong>TOTAL</strong>'+
            '</td>'+
            
            '<td style="text-align:center">'+
                '<strong>' +
                    qty +
                '</strong>'+
            '</td>'+
    '</tr>';
</script>
@endpush