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
        Received collected token form
    </div>

    <form action="{{ CRUDBooster::mainpath('edit-save/'.$detail_header->ct_id) }}" method="POST" id="receiveToken" enctype="multipart/form-data">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
        <input type="hidden" value="{{ $detail_header->ct_id }}" name="disburse_id" id="disburse_id">
        <input type="hidden" value="{{ $detail_header->collected_qty }}" name="collected_qty" id="collected_qty">
        <div class='panel-body'>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="require control-label"> Disburse Number:</label>
                            <input type="text" class="form-control finput" style="" value="{{ $detail_header->reference_number }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="require control-label"> From:</label>
                            <input type="text" class="form-control finput" style="" value="{{ $detail_header->location_name }}" readonly>
                        </div>
                    </div>
                </div>
                
                {{-- <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="require control-label"> To:</label>
                            <input type="text" class="form-control finput" style="" value="{{ $detail_header->to_location }}" readonly>
                        </div>
                    </div>
                </div> --}}

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="require control-label"><span style="color:red">*</span> Receive Token Qty:</label>
                            <input type="text" class="form-control finput" style="" placeholder="Receive token qty" name="received_qty" id="received_qty" onkeypress="inputIsNumber()" validation-name="No of tokens" autocomplete="off" oninput="event.target.value = event.target.value.replace(/[e\+\-\.]/gi, '');">
                        </div>
                    </div>
                </div>
                    
                    {{-- <div class="col-md-4">
                        <div class="form-group">
                            <label class="require control-label"><span style="color:red">*</span> Variance:</label>
                            <input type="text" class="form-control finput" name="variance_qty" id="variance_qty" readonly>
                        </div>
                    </div> --}}
            </div>
        </div>
        <div class='panel-footer'>
            <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
            <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.receive') }}</button>
        </div>
    </form>
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
    $('#location').select2();
    $(document).ready(function() {
        $('#btnSubmit').click(function(event) {
            event.preventDefault();
            if($('#received_qty').val() === ''){
                Swal.fire({
                    type: 'error',
                    title:'Receive token required!',
                    icon: 'error',
                    confirmButtonColor: "#3c8dbc",
                });
            }else if($('#received_qty').val().replace(/,/g, '') < $('#collected_qty').val().replace(/,/g, '')){
                Swal.fire({
                    type: 'info',
                    title: 'Token must be equal to collected token!',
                    icon: 'error',
                    confirmButtonColor: "#359D9D",
                }); 
                event.preventDefault();
    
            }else if($('#received_qty').val().replace(/,/g, '') > $('#collected_qty').val().replace(/,/g, '')){
                Swal.fire({
                    type: 'info',
                    title: 'Token must be equal to collected token!',
                    icon: 'error',
                    confirmButtonColor: "#3c8dbc",
                }); 
                event.preventDefault();
    
            }else{
                Swal.fire({
                    title: 'Are you sure ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3c8dbc',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Receive',
                    returnFocus: false,
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#receiveToken').submit();
                    }
                });

            }
        });

        //Variance
        $('#received_qty').on('keyup', function() {
            const received_qty = this.value.replace(/,/g, '');
            const released_qty = $('#released_qty').val();
            const total = Math.abs(received_qty - released_qty);
            $('#variance_qty').val(total);
        });
    });
</script>
@endpush