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
        #collect-token th, td {
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
    Add Collect Token Form
</div>

<form action="{{ CRUDBooster::mainpath('add-save') }}" method="POST" id="collectToken" enctype="multipart/form-data">
    <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">
   
    <div class='panel-body'>
        <div class="col-md-12 col-sm-offset-1">
        
            <div class="col-md-5">
                <div class="form-group">
                    <label class="require control-label"> Date time</label>
                    <input type="text" class="form-control finput" value="{{ $dateTime }}" readonly>
                </div>
            </div>

            <div class="col-md-5">
                <div class="form-group">
                    <label class="control-label"> Location</label>
                    <input type="text" class="form-control finput" name="location_id" id="location_id" value="{{ $locations->location_name }}" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <table class="table" id="collect-token">
                    <tbody id="bodyTable">    
                        <tr>
                            <th width="10%" class="text-center">Machine</th> 
                            <th width="10%" class="text-center">Quanity</th>
                            <th width="3%" class="text-center"><i class="fa fa-trash"></i></th>
                        </tr>       
                 
                    </tbody>
                    <tfoot>
                        <tr id="tr-tableOption1" class="bottom">
                            <td style="text-align:left" colspan="5">
                                <button class="red-tooltip" data-toggle="tooltip" data-placement="right" id="add-Row" name="add-Row" title="Add Row"><div class="iconPlus" id="bigplus"></div></button>
                                <div id="display_error" style="text-align:left"></div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>   
        </div>
    </div>
    <div class='panel-footer'>
        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
        <button class="btn btn-danger pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save" ></i> {{ trans('message.form.new') }}</button>
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
   
    var tableRow = 1;
    var optionDataArray = [];
    //Add Row
    $("#add-Row").click(function() {
        event.preventDefault();
        var vendor_name = "";
        var price = "";
        var count_fail = 0;
        tableRow++;

        $('.gasha_machine').each(function() {
            vendor_name = $(this).val();
            if (vendor_name == null) {
                swal({  
                    type: 'error',
                    title: 'Please fill all Fields!',
                    icon: 'error',
                    confirmButtonColor: "#367fa9",
                });
                count_fail++;

            } else if (vendor_name == "") {
                swal({  
                    type: 'error',
                    title: 'Please fill all Fields!',
                    icon: 'error',
                    confirmButtonColor: "#367fa9",
                });
                count_fail++;

            }else{
                count_fail = 0;
            }
        });
        $('.qty').each(function() {
            price = $(this).val();
            if (price == null) {
                swal({  
                    type: 'error',
                    title: 'Please fill all Fields!',
                    icon: 'error',
                    confirmButtonColor: "#367fa9",
                });
                count_fail++;

            } else if (price == "") {
                swal({  
                    type: 'error',
                    title: 'Please fill all Fields!',
                    icon: 'error',
                    confirmButtonColor: "#367fa9",
                });
                count_fail++;

            }else{
                count_fail = 0;
            }
        });
        if(count_fail == 0){
            $('#add-Row').prop("disabled", false);
            $('#display_error').html("");
            var newrow =
            '<tr>' +
                '<td >' +
                        '<select selected data-placeholder="Select machine" class="form-control gasha_machine" name="gasha_machines_id[]" data-id="'+tableRow+'" id="gasha_machines_id'+tableRow+'" required style="width:100%">' +
                        // '  <option value="">Select machines</option>' +
                        // '        @foreach($gasha_machines as $data)'+
                        // '           <option value="{{$data->id}}">{{$data->description}} - {{$data->serial_number}}</option>'+
                        // '        @endforeach'+
                        '</select>'+
                '</td>' +  

                '<td>' +
                    '<input class="form-control text-center finput qty" type="text" onkeyup="this.value = this.value.toUpperCase();" placeholder="Qty..." name="qty[]" id="qty'+tableRow+'" data-id="'+tableRow+'" style="width:100%">' + 
                '</td>' +

                '<td class="text-center">' +
                    '<button id="deleteRow" name="removeRow" data-id="'+tableRow+'" class="btn btn-danger btn-sm removeRow"><i class="glyphicon glyphicon-trash"></i></button>' +
                '</td>' +

            '</tr>';
            $('#collect-token tbody').append(newrow);
            $('#gasha_machines_id'+tableRow).select2();

            
            $('#gasha_machines_id'+tableRow).change(function () {
                optionDataArray.push(parseInt(this.value));
            });

            $.ajax({ 
                type: 'POST',
                url: "{{ route('get-options-machines') }}",
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                success: function(result) {
                    var pushData = [];
                    $.each( result, function( index, value ){
                        if(jQuery.inArray(value.id, optionDataArray) === -1){
                            pushData.push(value);
                        }
                    });
                    var x;
                    var showData = [];
                    showData[0] = "<option value=''></option>";
                    for (x = 0; x < pushData.length; ++x) {               
                        var j = x + 1;
                        showData[j] = "<option value='"+pushData[x].id+"'>"+pushData[x].description+" | "+ pushData[x].serial_number +"</option>";
                    }
                    $('#gasha_machines_id'+tableRow).html(showData);        
                }
            });

            $('#gasha_machines_id'+tableRow).select2();
        }

        $(document).on('click', '.removeRow', function() {
            if ($('#asset-items tbody tr').length != 1) { //check if not the first row then delete the other rows
                tableRow--;
                var removeItem =  $(this).parents('tr').find('select').val();
                console.log(removeItem);
                optionDataArray = jQuery.grep(optionDataArray, function(value) {
                    return value != removeItem;
                });
                $(this).closest('tr').remove();
            
                return false;
            }
            
        });
    });

   

     

    $(document).ready(function() {
        $('#btnSubmit').click(function(event) {
            event.preventDefault();

            var gasha_machines_id = $('.gasha_machine').length;
            var gasha_machines_id_value = $('.gasha_machine').find(':selected');;
            for(i=0;i<gasha_machines_id;i++){
                if(gasha_machines_id_value.eq(i).val() == 0 || gasha_machines_id_value.eq(i).val() == null){
                    swal({  
                            type: 'error',
                            title: 'Machines cannot be empty!',
                            icon: 'error',
                            confirmButtonColor: "#367fa9",
                        });
                        event.preventDefault();
                        return false;
                } 
            } 

            var qty = $("input[name^='qty[]']").length;
            var qty_value = $("input[name^='qty[]']");
            for(i=0;i<qty;i++){
                if(qty_value.eq(i).val() == 0 || qty_value.eq(i).val() == null){
                    swal({  
                            type: 'error',
                            title: 'Qty cannot be empty!',
                            icon: 'error',
                            confirmButtonColor: "#367fa9",
                        });
                        event.preventDefault();
                        return false;
                } 
        
            } 

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
                    $('#collectToken').submit();
                }
            });
           
        });
    });

    
</script>
@endpush