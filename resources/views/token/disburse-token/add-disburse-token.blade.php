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
    Disburse Token Form
</div>

<form action="{{ CRUDBooster::mainpath('add-save') }}" method="POST" id="disburseToken" enctype="multipart/form-data">
    <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
   
    <div class='panel-body'>
        <div class="col-md-12">
        
            <div class="form-group">
                <label class="require control-label"><span style="color:red">*</span> Token Qty:</label>
                <input type="text" class="form-control finput" style="" placeholder="Token Qty" name="release_qty" id="release_qty" onkeypress="inputIsNumber()" validation-name="No of tokens" oninput="event.target.value = event.target.value.replace(/[e\+\-\.]/gi, '');">
            </div>
    
            <div class="form-group">
                <label class="control-label"><span style="color:red">*</span>To location</label>
                <select selected data-placeholder="Choose location" validation-name="Location" id="location" name="location" class="form-select select2" style="width:100%;">
                @foreach($locations as $location)
                <option value=""></option>
                    <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                @endforeach
                </select>
            </div>
          
        </div>
    </div>
    <div class='panel-footer'>
        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
        <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.new') }}</button>
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
        $('#release_qty').keyup(function(){
            var value = $(this).val();
            value = value.replace(/^(0*)/,"");
            $(this).val(value);
        });
        $('#btnSubmit').click(function(event) {
            event.preventDefault();
            if($('#release_qty').val() === ''){
                Swal.fire({
                    type: 'error',
                    title:'Token required!',
                    icon: 'error',
                    confirmButtonColor: '#3c8dbc',
                });
            }else if($('#location').val() === ''){
                Swal.fire({
                        type: 'error',
                        title: 'Please choose location!',
                        icon: 'error',
                        confirmButtonColor: '#3c8dbc',
                    });
            }else{
                 $.ajax({
                    url: '{{ route("disburse.get.token.inventory") }}',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function (data) {
                        const qty = data != null ? data.qty : null;
                        if($('#release_qty').val().replace(/,/g, '') > qty){
                            Swal.fire({
                                type: 'info',
                                title: 'Token Qty Exceed in Head Office Token Inventory!',
                                icon: 'error',
                                confirmButtonColor: '#359D9D',
                            }); 
                            event.preventDefault();
                
                        } else{
                            Swal.fire({
                                title: 'Are you sure ?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Save',
                                returnFocus: false,
                                reverseButtons: true,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('#disburseToken').submit();
                                }
                            });

                        }
                    }
                });  
            }
        });
    });

    
</script>
@endpush