@push('head')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/code-scanner.js') }}"></script>
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

        .modal-content  {
            -webkit-border-radius: 10px !important;
            -moz-border-radius: 10px !important;
            border-radius: 10px !important; 
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
            background-color: #3c8dbc;
    
        }
        #bigplus{
            transition: transform 0.5s ease 0s;
        }
        #bigplus:before {
            content: '\FF0B';
            background-color: #3c8dbc: 
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
    Add Collect Token Form
</div>

<form action="{{ CRUDBooster::mainpath('add-save') }}" method="POST" id="collectToken" enctype="multipart/form-data">
    <input type="hidden" value="{{csrf_token()}}" name="_token" id="token">

    <div class='panel-body'>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label"><span style="color:red">*</span> Location</label>
                       <select selected data-placeholder="Choose location" id="location_id" name="location_id" class="form-select select2" style="width:100%;">
                            @foreach($locations as $location)
                            <option value=""></option>
                                <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                            @endforeach
                        </select>
                        <div id="location_error"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table" id="collect-token">
                    <tbody id="bodyTable">    
                        <tr>
                            <th width="10%" class="text-center">Machine</th> 
                            <th width="10%" class="text-center">Quantity</th>
                            <th width="3%" class="text-center"><i class="fa fa-trash"></i></th>
                        </tr>       
                 
                    </tbody>
                    <tfoot>
                        <tr id="tr-table1" class="bottom">
                            <td style="text-align:left" >
                                <button class="red-tooltip" id="add-Row" name="add-Row" title="Add Row"><div class="iconPlus" id="bigplus"></div></button>
                            </td>
                            <td colspan="1">
                                <input type="text" name="quantity_total" class="form-control text-center" id="quantity_total" readonly>
                            </td>
                            <td>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>   
        </div>
    </div>

    {{-- Collect Token Modal --}}
    <div id="addRowModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center"><strong>Collect Token</strong></h4>
                </div>
                <div class="row">
                    <div class="modal-body">
                        <div class="col-md-12" style="margin-bottom: 10px">
                            <div id="reader-wrapper" style="display: none;">
                                <div class="close-reader">X</div>
                                <div id="reader"></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" >
                                <div style="display: flex">
                                    <input input-for="machine" class="form-control text-center finput" type="text" placeholder="Machine" name="gasha_machines_id_inputed" id="gasha_machines_id_inputed" autocomplete="off">  
                                    <input class="form-control" type="hidden" name="machine_serial" id="machine_serial">  
                                    <button btn-for="machine" type="button" class="btn btn-primary open-camera"><i class="fa fa-camera"></i></button>
                                </div>
                                <div id="display_error_machine_not_found"></div>
                            </div>
                         
                        </div>
    
                        <div class="col-md-12">
                            <div class="form-group">
                                <input class="form-control" type="hidden" name="machine_token_qty" id="machine_token_qty">  
                                <input class="form-control text-center finput" type="text" placeholder="Collected Token" name="qty_inputed" id="qty_inputed" style="width:100%" oninput="calculate(this)" autocomplete="off">  
                                <div id="display_error"></div>
                            </div>
                        </div>
                    </div>
                </div>
               
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type='button' id="copy-row" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class='panel-footer'>
        <a href="{{ CRUDBooster::mainpath() }}" id="btn-cancel" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
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
    $('#location_id').select2();

    var tableRow = 1;
    var machineArray = [];
    $('#addRowModal').modal('hide');

    let selectedCameraId = null;
    let selectedInput = null;
    let html5QrCode = null;
    let timeout;

    $("#qty_inputed").keyup(function(){
        var value = $(this).val();
        value = value.replace(/^(0*)/,"");
        $(this).val(value); 
    });

    $('#gasha_machines_id_inputed, #qty_inputed').keydown(function(event) { 
        if (event.keyCode == 13) {
            event.preventDefault();
        }
    });

    $(function(){
    $('#gasha_machines_id_inputed, #qty_inputed').bind('input', function(){
        $(this).val(function(_, v){
            return v.replace(/\s+/g, '');
            });
        });
    });

    $('#add-Row').attr('disabled',true);

    $('#location_id').change(function(){
        $('#location_id').attr('disabled',true);
        var id =  this.value;
       
        $.ajax({ 
            type: 'POST',
            url: "{{ route('check-inventory-qty-collect') }}",
            data: {
                "id": id
            },
            success: function(res) {
                const data = JSON.parse(res);
                if(data.tokenInventory == null){
                    $('#add-Row').attr('disabled',true);
                    $('#location_error').html('<span class="label label-danger">No available inventory</span>');
                }else{
                    $('#location_error').html('');
                    $('#add-Row').attr('disabled',false);
                }
                console.log(res);
            }
        });
    });

    async function showCameraOptions(cameras) {
        let cameraOptions = {}
        cameras.forEach(camera => cameraOptions[camera.id] = camera.label);
        const { value: camera } = await Swal.fire({
            title: 'Select a camera',
            input: 'select',
            inputOptions: cameraOptions,
            inputPlaceholder: 'Select a camera',
            showCancelButton: true,
            confirmButtonColor: '#3c8dbc',
            returnFocus: false,
            reverseButtons: true,
            inputValidator: (value) => {
                selectedCameraId = value;
                openVideo(selectedCameraId);
            }
        });

    }

    function openVideo(cameraId) {
        if (html5QrCode && html5QrCode.getState() == 2) {
            html5QrCode.stop();
        }
        $('#reader-wrapper').show();
        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
        cameraId, 
        {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        },
        (decodedText, decodedResult) => {
            html5QrCode.stop();
            populateInput(decodedText);
            $('#reader-wrapper').hide();
            
        },
        (errorMessage) => {
            console.log(errorMessage);
        })
        .catch((err) => {
            console.log(err);
        });

    }

    function populateInput(text) {
        $(`input[input-for="${selectedInput}"]`).val(text);
        console.log(selectedInput, text)
        if (selectedInput == 'machine') checkMachinePartner(text);
    }

    $('#gasha_machines_id_inputed').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    })
    
    function checkMachinePartner(item_code) {
        $.ajax({
            type: 'POST',
            url: "{{ route('get_machine-collect') }}",
            data: {
                _token: "{{ csrf_token() }}",
                item_code: item_code,
                location_id: $('#location_id').val(),
            },
            success: function(res) {
                const data = JSON.parse(res);
                console.log(data);
                if(data.machines == null){
                    // Swal.fire({
                    //     title: "Oops.",
                    //     html:  'Machine not found!',
                    //     icon: 'error',
                    //     confirmButtonColor: '#3c8dbc',
                    //     confirmButtonText: 'Ok',
                    //     returnFocus: false,
                    // });
                    $('#copy-row').attr('disabled',true);
                    $('#display_error_machine_not_found').html(`<span id="notif" class="label label-danger">Machine not found</span>`);
                    return false;
                }else{
                    $('#display_error_machine_not_found').html('');
                    $('#machine_token_qty').val(data.machines.no_of_token);
                    $('#machine_serial').val(data.machines.serial_number);
                    $('#copy-row').attr('disabled',false);
                }
            },
            error: function(err) {
                Swal.fire({
                    title: "Oops.",
                    html:  'Something went wrong!',
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok',
                    returnFocus: false,
                });
            }
        });
    }

    $('.open-camera').on('click', function() {
        selectedInput = $(this).attr('btn-for');
        if (selectedCameraId) {
            openVideo(selectedCameraId);
            return;
        } 
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                showCameraOptions(devices);
            }
        }).catch(err => {
            alert('no cameras detected');
        });
    });

    $('.close-reader').on('click', function() {
        if (html5QrCode) html5QrCode.stop();
        $('#reader-wrapper').hide();
    });

    $('#gasha_machines_id_inputed').on('input', function() {
        clearTimeout(timeout); 

        timeout = setTimeout(() => {
            checkMachinePartner($('#gasha_machines_id_inputed').val());
        }, 500);
    })
    
    
    //Add Row
    $('#add-Row').click(function(e) {
        e.preventDefault();
        if($('#location_id').val() === ''){
            swal({  
                type: 'error',
                title: 'Please select location first!',
                icon: 'error',
                confirmButtonColor: '#3c8dbc',
            });
        }else{
            $('#addRowModal').modal('show');
        }
    });

    //Add Row old
    $('#copy-row').click(function() {
        event.preventDefault();
        var gasha_machine = "";
        var qty = "";
        var count_fail = 0;
        tableRow++;

            if ($('#gasha_machines_id_inputed').val() == null || $('#gasha_machines_id_inputed').val() == '') {
                swal({  
                    type: 'error',
                    title: 'Please fill all Fields!',
                    icon: 'error',
                    confirmButtonColor: "#3c8dbc",
                });
                count_fail++;
                return false;
            }else{
                count_fail = 0;
            }

            if ($('#qty_inputed').val() == null || $('#qty_inputed').val() == '') {
                swal({  
                    type: 'error',
                    title: 'Please fill all Fields!',
                    icon: 'error',
                    confirmButtonColor: "#dd4b39",
                });
                count_fail++;
                return false;
            }else{
                count_fail = 0;
            }

            if($('#gasha_machines_id_inputed').val() != $('#machine_serial').val()){
                Swal.fire({ 
                    type: 'error',
                    title: 'Oops.',
                    html: `Machine ${$('#gasha_machines_id_inputed').val()} not match to ${$('#machine_serial').val()}`,
                    icon: 'error',
                    confirmButtonColor: "#dd4b39",
                });
                count_fail++;
                return false;
            }else{
                count_fail = 0;
            }

            // if(!$.isNumeric($('#qty_inputed').val().replace(/\D/g, ''))){
            //     Swal.fire({ 
            //         type: 'error',
            //         title: 'Number Only!',
            //         icon: 'error',
            //         confirmButtonColor: "#dd4b39",
            //     });
            //     count_fail++;
            //     return false;
            // }else{
            //     count_fail = 0;
            // }

            if($('#qty_inputed').val() == 0){
                Swal.fire({ 
                    type: 'error',
                    title: 'Not allowed zero!',
                    icon: 'error',
                    confirmButtonColor: "#dd4b39",
                });
                count_fail++;
                return false;
            }else{
                count_fail = 0;
            }
        
        $('#addRowModal').modal('hide');
        tableRow++;
        if (!machineArray.includes($('#gasha_machines_id_inputed').val())) {
            if(count_fail == 0){
                machineArray.push($('#gasha_machines_id_inputed').val());
                // $('#add-Row').prop("disabled", false);
                // $('#display_error').html("");
                
                    var newrow =
                    '<tr id="tr-style'+tableRow+'" style="background-color: #d4edda; color:#155724">' +
                        '<td>' +
                            '<input class="form-control text-center finput gasha_machines_id" type="text" placeholder="Machine" name="gasha_machines_id[]" id="gasha_machines_id'+tableRow+'" data-id="'+tableRow+'" value="'+$('#gasha_machines_id_inputed').val()+'" autocomplete="off" readonly>' + 

                        '</td>' +  

                        '<td>' +
                            '<input class="form-control text-center finput qty" type="text" onkeypress="inputIsNumber()" placeholder="Qty..." name="qty[]" id="qty'+tableRow+'" data-id="'+tableRow+'" style="width:100%" value="'+$('#qty_inputed').val()+'" autocomplete="off" readonly>' + 
                        '</td>' +

                        '<td class="text-center">' +
                            '<button id="deleteRow" name="removeRow" data-id="'+tableRow+'" value="'+$('#gasha_machines_id_inputed').val()+'" class="btn btn-danger btn-sm removeRow"><i class="glyphicon glyphicon-trash"></i></button>' +
                        '</td>' +

                    '</tr>';
        
                $('#collect-token tbody').append(newrow);
                //$(newrow).insertBefore($('table tr#tr-table1:last'));
                //$('#gasha_machines_id'+tableRow).select2();

                
                // $.ajax({ 
                //     type: 'POST',
                //     url: "{{ route('get-options-machines') }}",
                //     data: {
                //         '_token': '{{ csrf_token() }}'
                //     },
                //     success: function(result) {
                //         var pushData = [];
                //         $.each( result, function( index, value ){
                //             if(jQuery.inArray(value.id, optionDataArray) === -1){
                //                 pushData.push(value);
                //             }
                //         });
                //         var x;
                //         var showData = [];
                //         showData[0] = "<option value=''></option>";
                //         for (x = 0; x < pushData.length; ++x) {               
                //             var j = x + 1;
                //             showData[j] = "<option value='"+pushData[x].id+"'>"+ pushData[x].serial_number +"</option>";
                //         }
                //         $('#gasha_machines_id'+tableRow).html(showData);        
                //     }
                // });
            }
        }else{
            swal({  
                type: 'error',
                title: 'Machine already added!',
                icon: 'error',
                confirmButtonColor: '#3c8dbc',
            });
      
        }
        $('#quantity_total').val(calculateTotalQuantity());

        $(document).on('click', '.removeRow', function(e) {
            e.preventDefault();
            if ($('#collect-token tbody tr').length != 1) { //check if not the first row then delete the other rows
                tableRow--;
                var removeItem =  $(this).val();
                console.log(removeItem);
                machineArray = jQuery.grep(machineArray, function(value) {
                    return value != removeItem;
                });
                $(this).closest('tr').remove();
                $('#quantity_total').val(calculateTotalQuantity());
                return false;
            }
            
        });

        if($('#qty'+tableRow).val().split(",").join("")  % $('#machine_token_qty').val() === 0){
            $("#tr-style"+tableRow).removeAttr("style", false);
        }else{
            $("#tr-style"+tableRow).attr("style", "background-color: #f8d7da; color:#721c24");
            $("#qty"+tableRow).attr("style", "background-color: #f8d7da; color:#721c24");
            $("#gasha_machines_id"+tableRow).attr("style", "background-color: #f8d7da; color:#721c24");
        }
        
    });

    $('#addRowModal').on('shown.bs.modal', function () {
        $('#gasha_machines_id_inputed').focus();
    });

    $('#addRowModal').on('hidden.bs.modal', function (e) {
        $(this)
        .find("input,textarea")
        .val('')
        .end();
        $('#display_error').html('');
    });
    
    function calculate(input){
        if(parseInt(input.value)  % $('#machine_token_qty').val() === 0){
            $('#display_error').html('');
        }else{
            $('#display_error').html(`<span id="notif" class="label label-danger">Collect token not divisible by machine token capacity ${$('#machine_token_qty').val()}</span>`);
        }
    }
    // $(document).on('keyup', '#qty_inputed', function(ev) {
    //     if(Number($(this).val())  % $('#machine_token_qty').val() === 0){
    //         $('#display_error').html('');
    //     }else{
    //         $('#display_error').html(`<span id="notif" class="label label-danger">Collect token not divisible by machine token capacity ${$('#machine_token_qty').val()}</span>`);
    //     }
    // });
   
    $(document).on('keyup', '.qty', function(ev) {
        $('#quantity_total').val(calculateTotalQuantity());
    });
   
    // $(document).on('keyup','#qty_inputed', function (e) {
    //     if(event.which >= 37 && event.which <= 40) return;

    //     if(this.value.charAt(0) == '.'){
    //         this.value = this.value.replace(/\.(.*?)(\.+)/, function(match, g1, g2){
    //             return '.' + g1;
    //         })
    //     }

    //     // if(event.key == '.' && this.value.split('.').length > 2){
    //     if(this.value.split('.').length > 2){
    //         this.value = this.value.replace(/([\d,]+)([\.]+.+)/, '$1') 
    //             + '.' + this.value.replace(/([\d,]+)([\.]+.+)/, '$2').replace(/\./g,'')
    //         return;
    //     }

    //     $(this).val( function(index, value) {
    //         value = value.replace(/[^0-9.]+/g,'')
    //         let parts = value.toString().split(".");
    //         parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //         return parts.join(".");
    //     });

    //     if(event.which >= 37 && event.which <= 40) return;
    //     $(this).val(function(index, value) {
    //         return value
    //         .replace(/\D/g, "")
    //         .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    //         ;
    //       });

    // });  
    $('#qty_inputed').on('input',function(){
        const currentVal = $(this).val();
        const val = Number(currentVal.replace(/\D/g, ''));
        $(this).val(currentVal ? val.toLocaleString() : '');
        if($('#request_type_id').val() == 9){
            validateQty();
        }
    })
   
     
    $(document).ready(function() {
        $('#btnSubmit').click(function(event) {
            event.preventDefault();
            var rowCount = $('#collect-token tr').length-2;
            console.log(rowCount);
            if (rowCount === 0) {
                    swal({
                        type: 'error',
                        title: 'Please add an item!',
                        icon: 'error',
                        confirmButtonColor: "#3c8dbc",
                    }); 
                    event.preventDefault(); 
                    return false;
            }else if($('#location_id').val() === ''){
                swal({  
                    type: 'error',
                    title: 'Location cannot be empty!',
                    icon: 'error',
                    confirmButtonColor: '#3c8dbc',
                });
                event.preventDefault();
                return false;
            }
            var gasha_machines_id = $('.gasha_machine').length;
            var gasha_machines_id_value = $('.gasha_machine').find(':selected');;
            for(i=0;i<gasha_machines_id;i++){
                if(gasha_machines_id_value.eq(i).val() == 0 || gasha_machines_id_value.eq(i).val() == null){
                    swal({  
                            type: 'error',
                            title: 'Machines cannot be empty!',
                            icon: 'error',
                            confirmButtonColor: '#3c8dbc',
                        });
                        event.preventDefault();
                        return false;
                } 
            } 

            var qty = $('input[name^="qty[]"]').length;
            var qty_value = $('input[name^="qty[]"]');
            for(i=0;i<qty;i++){
                if(qty_value.eq(i).val() == 0 || qty_value.eq(i).val() == null){
                    swal({  
                            type: 'error',
                            title: 'Qty cannot be empty!',
                            icon: 'error',
                            confirmButtonColor: '#3c8dbc',
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
                showLoaderOnConfirm: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#location_id').attr('disabled',false);
                    $('#collectToken').submit();
                    $('#btnSubmit').attr('disabled', true);
                    $('#add-Row').attr('disabled', true);
                    Swal.fire({
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        title: "Please wait while saving...",
                        didOpen: () => Swal.showLoading()
                    });
                }
            });
           
        });
    });

    function calculateTotalQuantity() {
        var totalQuantity = 0;
        $('.qty').each(function() {
            if($(this).val() === ''){
                var qty = 0;
            }else{
                var qty = parseInt($(this).val().replace(/,/g, ''));
            }
  
            totalQuantity += qty;
        });
        return totalQuantity.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    }

    $("#btn-cancel").on('click', function(event) {
       event.preventDefault();
       Swal.fire({
            title: 'Are you sure you want to cancel?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Cancel',
            cancelButtonText: "No",
            returnFocus: false,
            reverseButtons: true,
       }).then((result) => {
            if (result.isConfirmed) {
                window.history.back();    
            }
       });
    });
    

    
</script>
@endpush