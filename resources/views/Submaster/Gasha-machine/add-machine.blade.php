
    @push('head')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
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

            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
                /* display: none; <- Crashes Chrome on hover */
                -webkit-appearance: none;
                margin: 0; 
            }

            input[type=number] {
                appearance: textfield;
                -moz-appearance: textfield; /* Firefox */
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
    <div class='panel-heading' style="background-color:#dd4b39; color:#fff">
        Add Machine Form
    </div>

    <form action="{{ CRUDBooster::mainpath('add-save') }}" method="POST" id="gashaMachineForm" enctype="multipart/form-data">
        <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
       
        <div class='panel-body'>
            <div class="col-md-12">

                {{-- <div class="form-group">
                    <label class="require control-label"><span style="color:red">*</span> Description</label>
                    <input type="text" class="form-control finput" style="" placeholder="Description" name="description" id="description">
                </div> --}}

                <div class="form-group">
                    <label class="require control-label"><span style="color:red">*</span> No of tokens</label>
                    <input type="text" class="form-control finput" style="" placeholder="No of tokens" name="no_of_tokens" id="no_of_tokens" oninput="event.target.value = event.target.value.replace(/[e\+\-\.]/gi, '');" autocomplete="off">
                </div>
                <div class="form-group">
                    <label class="require control-label"><span style="color:red">*</span> Bay</label>
                    <select selected data-placeholder="Choose Bay" validation-name="Bay" id="bay" name="bay" class="form-select select2" style="width:100%;">
                        @foreach($gasha_machine_bay as $bay)
                        <option value=""></option>
                            <option value="{{ $bay->id }}">{{ $bay->name }}</option>
                        @endforeach
                    </select>
                    {{-- <input type="text" class="form-control finput" style="" placeholder="Bay" name="bay" id="bay" autocomplete="off"> --}}
                </div>
                <div class="form-group">
                    <label class="require control-label"><span style="color:red">*</span> Layer</label>
                    <select selected data-placeholder="Choose Layer" validation-name="Layer" id="layer" name="layer" class="form-select select2" style="width:100%;">
                        @foreach($gasha_machine_layer as $layer)
                        <option value=""></option>
                            <option value="{{ $layer->id }}">{{ $layer->name }}</option>
                        @endforeach
                    </select>
                    {{-- <input type="text" class="form-control finput" style="" placeholder="layer" name="layer" id="layer" autocomplete="off"> --}}
                </div>

                <div class="form-group">
                    <label class="control-label"><span style="color:red">*</span> Location</label>
                    @if(CRUDBooster::isSuperAdmin())
                        <select selected data-placeholder="Choose location" validation-name="Location" id="location" name="location" class="form-select select2" style="width:100%;">
                        @foreach($locations as $location)
                        <option value=""></option>
                            <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                        @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control" value="{{ $locations->location_name }}" readonly>
                        <input type="hidden" class="form-control" value="{{ $locations->id }}" name="location">
                    @endif
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        function preventBack() {
            window.history.forward();
        }
         window.onunload = function() {
            null;
        };
        setTimeout("preventBack()", 0);
        $('.select2').select2();

        $('#no_of_tokens').on('paste', function(e) {
            e.preventDefault();
        });

        $("#no_of_tokens").keyup(function(){
            var value = $(this).val();
            value = value.replace(/^(0*)/,"");
            $(this).val(value);
        });

        $(document).on('keyup','#no_of_tokens', function (e) {
            if(event.which >= 37 && event.which <= 40) return;

            if(this.value.charAt(0) == '.'){
                this.value = this.value.replace(/\.(.*?)(\.+)/, function(match, g1, g2){
                    return '.' + g1;
                })
            }

            // if(event.key == '.' && this.value.split('.').length > 2){
            if(this.value.split('.').length > 2){
                this.value = this.value.replace(/([\d,]+)([\.]+.+)/, '$1') 
                    + '.' + this.value.replace(/([\d,]+)([\.]+.+)/, '$2').replace(/\./g,'')
                return;
            }

            $(this).val( function(index, value) {
                value = value.replace(/[^0-9.]+/g,'')
                let parts = value.toString().split(".");
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return parts.join(".");
            });

            if(event.which >= 37 && event.which <= 40) return;
            $(this).val(function(index, value) {
                return value
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                ;
            });

        });  
        
        $(document).ready(function() {
            $('#btnSubmit').click(function(event) {
                event.preventDefault();
                if($('#no_of_tokens').val() === ''){
                    Swal.fire({
                        type: 'error',
                        title:'Token required!',
                        icon: 'error',
                        confirmButtonColor: "#367fa9",
                    });
                }
                // else if($('#no_of_tokens').val() >= 10){
                //     Swal.fire({
                //             type: 'error',
                //             title: 'Token must be equal or less than 9!',
                //             icon: 'error',
                //             confirmButtonColor: "#367fa9",
                //         });
                // }
                else if($('#location').val() === ''){
                    Swal.fire({
                            type: 'error',
                            title: 'Please choose location!',
                            icon: 'error',
                            confirmButtonColor: "#367fa9",
                        });
                }else{
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
                            $('#gashaMachineForm').submit();
                        }
                    });
                }
            });
        });
    </script>
@endpush