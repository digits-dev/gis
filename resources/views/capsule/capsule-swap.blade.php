@extends('crudbooster::admin_template')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/capsule-swap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="{{ asset('js/code-scanner.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/select2-custom.css') }}">
    <style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
    }

    input[type=number] {
        appearance: textfield;
        -moz-appearance: textfield; /* Firefox */
    }
    .swal-machine-list table tr td, .swal-machine-list table tr th {
        border: 1px solid rgba(136, 149, 222, 0.621);
        padding: 10px;
    }

    #table-machine-one th, td {
        border: 1px solid rgba(000, 0, 0, .5);
        padding: 8px;
    }
    #table-machine-two th, td {
        border: 1px solid rgba(000, 0, 0, .5);
        padding: 8px;
    }
    </style>
@endpush
@section('content')
    <div class="panel-content">
        <div class='panel panel-default'>
            <div class='panel-header'>
                <label>CAPSULE SWAP</label>
            </div>
            <div class='panel-body'>
                <div id="reader-wrapper" style="display: none;">
                    <div class="machine-camera-label"><label for=""></label></div>
                    <div class="close-reader">×</div>
                    <div id="reader"></div>
                </div>
                <form method="POST" action="{{ CRUDBooster::mainpath('add-save') }}" autocomplete="off" id="capsuleSwapForm">
                    @csrf
                    <div class='form-group'>
                        <div class="machine-one-div">
                            <div class="machine-one-label">
                                <label>Machine One<span style="color: red"></span></label>
                            </div>
                            <div class="flex input-btn">
                                <input type='text' input-for="machine-one" machine-value="Machine One" id="machine_no_one" name="machine_no_one" required class='form-control text-center'/>
                                <input type="hidden" id="machine_one_no_of_token">
                                <button type="button" btn-for="machine-one" class="btn btn-primary open-camera"><i class="fa fa-camera"></i></button>
                            </div>
                            <div id="display_error_machine_one_not_found"></div>
                            <div class="table_summary styled-table-swap-one" style="display: none">
                                <table id="table-machine-one">
                                    <thead>
                                        <tr>
                                            <th class="text-center">JAN#</th>
                                            <th class="text-center">QTY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-center">Total</td>
                                            <td colspan="1">
                                                <input type="text" name="capsule_qty_one_total" class="form-control" id="capsule_qty_one_total" readonly>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        
                        <div class="machine-two-div">
                            <div class="machine-two-label">
                                <label>Machine Two <span style="color: red"></span></label>
                            </div>
                            <div class="flex input-btn">
                                <input  type="text" input-for="machine-two" machine-value="Machine Two" id="machine_no_two" name="machine_no_two"  required class='form-control text-center'/>
                                <input type="hidden" id="machine_two_no_of_token">
                                <button type="button" btn-for="machine-two" class="btn btn-primary open-camera"><i class="fa fa-camera"></i></button>
                            </div>
                            <div id="display_error_machine_two_not_found"></div>
                            <div class="table_summary styled-table-swap-two" style="display: none" >
                                <table id="table-machine-two">
                                    <thead>
                                        <tr>
                                            <th class="text-center">JAN#</th>
                                            <th class="text-center">QTY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-center">Total</td>
                                            <td colspan="1">
                                                <input type="text" name="capsule_qty_two_total" class="form-control" id="capsule_qty_two_total" readonly>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class='panel-img'>
                        <img src="{{ asset('img/capsule-swap.png') }}">
                    </div>
                        <button class="hide" type="submit" id="real-submit-btn"></button> 
                </form>
            </div>
            <div class='panel-footer'>
            <button class="btn btn-primary" id="save-btn" data-swal-toast-template="#my-template"> <i class="fa fa-refresh"></i> Swap</button>
            </div>
        </div>
    </div>
@endsection
@push('bottom')
<script>

    $('.content-header').hide();
    let selectedCameraId = null;
    let selectedInput = null;
    let html5QrCode = null;
    let timeout;
    async function showCameraOptions(cameras) {
        let cameraOptions = {}
        cameras.forEach(camera => cameraOptions[camera.id] = camera.label);
        const { value: camera } = await Swal.fire({
            title: 'Select a camera',
            input: 'select',
            inputOptions: cameraOptions,
            inputPlaceholder: 'Select a camera',
            showCancelButton: true,
            returnFocus: false,
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

    function displaySelectedInputLabel(selectedInput) {
        const labelContainer = $('.machine-camera-label label');
        labelContainer.text(selectedInput);
        labelContainer.parent().show(); // Display the div containing the label
    }

    function populateInput(text) {
        $(`input[input-for="${selectedInput}"]`).val(text);
        console.log(selectedInput, text)
        if (selectedInput == 'machine-one'){
            checkMachineOne(text);
        } else{
            checkMachineTwo(text);
        }
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
                displaySelectedInputLabel(selectedInput);
            }
        }).catch(err => {
            alert('no cameras detected');
        });
    });

    $('.close-reader').on('click', function() {
        if (html5QrCode) html5QrCode.stop();
        $('#reader-wrapper').hide();
    });

    $('#machine_no_one, #machine_no_two').keyup(function() {
        this.value = this.value.toLocaleUpperCase();
	});

    //GET THE MACHINE ONE
    $('#machine_no_one').on('input', function() {
        clearTimeout(timeout); 
        timeout = setTimeout(() => {
            checkMachineOne($('#machine_no_one').val());
        }, 500);
    })

    function checkMachineOne(machine_code) {
        $.ajax({
            type: 'POST',
            url: "{{ route('check-machine') }}",
            data: {
                _token: "{{ csrf_token() }}",
                machine_code: machine_code,
            },
            success: function(res) {
                const data = JSON.parse(res);
                if(data.status == 'error'){
                    $('#save-btn').attr('disabled',true);
                    $('#display_error_machine_one_not_found').html(`<span id="notif" class="label label-danger">Machine not found</span>`);
                    return false;
                }else{
                    $('#save-btn').attr('disabled',false);
                    $('#machine_one_no_of_token').val(data.machine_data.no_of_token);
                    $('#display_error_machine_one_not_found').html('');
                    displayMAchineOneJanData(data);
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

    function displayMAchineOneJanData(data){
        console.log(data);
        if(data.jan_data.length != 0){
            $('.styled-table-swap-one').removeAttr('style');
            data.jan_data.forEach(item => {
                const populate_row = `
                    <tr id="${item.digits_code}">
                        <td><input class="form-control" type="text" name="jan_no_one[]" value="${item.digits_code}" readonly></td>
                        <td><input class="form-control capsule_qty_one" type="text" name="capsule_qty_one[]"></td>
                    </tr>
                `;

                $('#table-machine-one tbody').append(populate_row);
            })
            $('#machine_no_one').attr('readonly','readonly');
        }else{
            Swal.fire({
                title: "Oops.",
                html:  'No inventory!',
                icon: 'info',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
            });
        }
        
    }

    //GET THE MACHINE ONE
    $('#machine_no_two').on('input', function() {
        clearTimeout(timeout); 
        timeout = setTimeout(() => {
            checkMachineTwo($('#machine_no_two').val());
        }, 500);
    })

    function checkMachineTwo(machine_code) {
        $.ajax({
            type: 'POST',
            url: "{{ route('check-machine') }}",
            data: {
                _token: "{{ csrf_token() }}",
                machine_code: machine_code,
            },
            success: function(res) {
                const data = JSON.parse(res);
                if(data.status == 'error'){
                    $('#save-btn').attr('disabled',true);
                    $('#display_error_machine_two_not_found').html(`<span id="notif" class="label label-danger">Machine not found</span>`);
                    return false;
                }else{
                    $('#save-btn').attr('disabled',false);
                    $('#machine_two_no_of_token').val(data.machine_data.no_of_token);
                    $('#display_error_machine_two_not_found').html('');
                    displayMAchineTwoJanData(data);
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

    function displayMAchineTwoJanData(data){
        console.log(data);
        if(data.jan_data.length != 0){
            $('.styled-table-swap-two').removeAttr('style');
            data.jan_data.forEach(item => {
                const populate_row = `
                    <tr id="${item.digits_code}">
                        <td><input class="form-control" type="text" name="jan_no_two[]" value="${item.digits_code}" readonly></td>
                        <td><input class="form-control capsule_qty_two" type="text" name="capsule_qty_two[]"></td>
                    </tr>
                `;

                $('#table-machine-two tbody').append(populate_row);
            })
            $('#machine_no_two').attr('readonly','readonly');
        }else{
            Swal.fire({
                title: "Oops.",
                html:  'No inventory!',
                icon: 'info',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
            });
        }
       
    }

    $(document).on('keyup', '.capsule_qty_one, .capsule_qty_two', function(ev) {
        $('#capsule_qty_one_total').val(calculateTotalQtyOne());
        $('#capsule_qty_two_total').val(calculateTotalQtyTwo());
    });

    function calculateTotalQtyOne() {
        var totalQuantity = 0;
        $('.capsule_qty_one').each(function() {
            if($(this).val() === ''){
                var qty = 0;
            }else{
                var qty = parseInt($(this).val().replace(/,/g, ''));
            }
  
            totalQuantity += qty;
        });
        return totalQuantity.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    }

    function calculateTotalQtyTwo() {
        var totalQuantity = 0;
        $('.capsule_qty_two').each(function() {
            if($(this).val() === ''){
                var qty = 0;
            }else{
                var qty = parseInt($(this).val().replace(/,/g, ''));
            }
  
            totalQuantity += qty;
        });
        return totalQuantity.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    }

    $('#save-btn').click(function(event) {
       
        if($('#machine_no_one').val()  === '' || $('#machine_no_two').val() === ''){
            Swal.fire({
                title: 'Oops.',
                html:  'Machine required!',
                icon: 'info',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
            })
            return false;
       }

       if($('#machine_one_no_of_token').val() != $('#machine_two_no_of_token').val()){
            Swal.fire({
                title: 'Oops.',
                html:  'Machine token not match!',
                icon: 'info',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
            })
            return false;
       }
       if($('#machine_no_one').val() === $('#machine_no_two').val()){
            Swal.fire({
                title: 'Oops.',
                html:  'Machine cannot be the same!',
                icon: 'info',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
            })
            return false;
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
                $('#capsuleSwapForm').submit();
            }
        });

      
    });

</script>
@endpush