@extends('crudbooster::admin_template')
@push('head')
   <link rel="stylesheet" href="{{ asset('css/capsule-refill.css') }}">
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
    .card-big-shadow {
        max-width: 320px;
        position: relative;
    }

    .coloured-cards .card {
        margin-top: 30px;
    }

    .card[data-radius="none"] {
        border-radius: 0px;
    }
    .card {
        border-radius: 8px;
        box-shadow: 0 2px 2px rgba(204, 197, 185, 0.5);
        background-color: #FFFFFF;
        color: #252422;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }


    .card[data-background="image"] .title, .card[data-background="image"] .stats, .card[data-background="image"] .category, .card[data-background="image"] .description, .card[data-background="image"] .content, .card[data-background="image"] .card-footer, .card[data-background="image"] small, .card[data-background="image"] .content a, .card[data-background="color"] .title, .card[data-background="color"] .stats, .card[data-background="color"] .category, .card[data-background="color"] .description, .card[data-background="color"] .content, .card[data-background="color"] .card-footer, .card[data-background="color"] small, .card[data-background="color"] .content a {
        color: #FFFFFF;
    }
    .card.card-just-text .content {
        padding: 30px 25px;
        text-align: center;
        font-style: italic
    }
    .card .content {
        padding: 20px 20px 10px 20px;
    }

    .card[data-color="yellow"] .category {
        color: #d88715;
        font-size: 20px;
    }
    .card .card .label {
        font-size: 10px;
        margin-bottom: 0px;
    }
    .card-big-shadow:before {
        background-image: url("http://static.tumblr.com/i21wc39/coTmrkw40/shadow.png");
        background-position: center bottom;
        background-repeat: no-repeat;
        background-size: 100% 100%;
        bottom: -12%;
        content: "";
        display: block;
        left: -12%;
        position: absolute;
        right: 0;
        top: 0;
        z-index: 0;
    }
    h4, .h4 {
        font-size: 1.5em;
        font-weight: 600;
        line-height: 1.2em;
    }
    h6, .h6 {
        font-size: 0.9em;
        font-weight: 600;
        text-transform: uppercase;
    }
    .card .description {
        font-size: 14px;
        color: #66615b;
        font-style: italic
    }
    .content-card{
        margin-top:30px;   
        float: right; 
        padding: 10px
    }
    @media (max-width:729px){
        .content-card{
            margin-top:0px;   
            margin-right:50px;
        }
        .coloured-cards .card {
            margin-top: 0px;
        }
    }
    a:hover, a:focus {
        text-decoration: none;
    }

    /*======== COLORS ===========*/

    .card[data-color="yellow"] {
        background: #ffe28c;
    }
    .card[data-color="yellow"] .description {
        color: #b25825;
    }
   </style>
@endpush
@section('content')
    <div class="swal-machine-list" style="display: none;">
        <table style="width: 100%; font-size: 16px">
            <tbody>
                <tr>
                    <th class="text-center">JAN #</th>
                    <td class="swal-item-code"></td>
                </tr>
                <tr>
                    <th class="text-center">Item Description</th>
                    <td class="swal-item-description"></td>
                </tr>
                <tr>
                    <th class="text-center">No. of Tokens</th>
                    <td class="swal-no-of-tokens"></td>
                </tr>
                <tr class="machine-tr" style="display: none;">
                    <th class="text-center" style="vertical-align: middle">Machines</th>
                    <td class="swal-machines"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="swal-token-mismatch" style="display: none;">
        <table class="table table-striped table-bordered" style="width: 100%">
            <thead>
                <tr>
                    <th class="text-center">Item Code / Serial No.</th>
                    <th class="text-center">No. of Tokens</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="panel-content">
        <div class='panel panel-default'>
            <div class='panel-header'>
                <label>CAPSULE REFILL</label>
            </div>
            <div class='panel-body'>
                <div id="reader-wrapper" style="display: none;">
                    <div class="close-reader">×</div>
                    <div id="reader"></div>
                </div>
                <form method="POST" autocomplete="off">
                    @csrf
                    <div class='form-group'>
                        <p>Capsule Barcode <span style="color: red">*</span></p>
                        <div class="flex input-btn">
                            <input input-for="capsule" type='number' id="item_code" name='item_code' required class='form-control text-center'/>
                            <button btn-for="capsule" type="button" class="btn btn-primary open-camera" tabindex="-1"><i class="fa fa-camera"></i></button>
                        </div>
                        <p>To Gasha Machine <span style="color: red">*</span> </p>
                        <div class="flex input-btn">
                            <input input-for="machine" type='text' id="machine_code" name='machine_code' oninput="this.value = this.value.toUpperCase()" required class='form-control text-center'/>
                            <button btn-for="machine" type="button" class="btn btn-primary open-camera" tabindex="-1"><i class="fa fa-camera"></i></button>
                        </div>
                        <p>Quantity <span style="color: red">*</span></p>
                        <div class="flex input-btn">
                        <input type='text' name='qty' required class='form-control text-center' oninput="validateInput(this)" id="quantity" min="1" style="width: 100%; max-width: 450px;"/>
                        </div>
                    </div>
                    <div class='panel-img'>
                        <img src="{{ asset('img/capsule-refill.png') }}">
                    </div>
                     <button class="hide" type="submit" id="real-submit-btn"></button> 
                </form>
            </div>
            <div class='panel-footer'>
            <button class="btn btn-primary btn-submit-size" id="save-btn" data-swal-toast-template="#my-template">Save</button>
            </div>
        </div>
        <div class="content-card">
            <div class="card-big-shadow">
                <div class="card card-just-text" data-background="color" data-color="yellow" data-radius="none">
                    <div class="content">
                        <h6 class="category"> <i class="fa fa-sticky-note"></i> Notes </h6>
                        <hr>
                        <h4 class="title"><a href="#">Capsule refill</a></h4>
                        <p class="description">Capsule refills now cannot have duplicate JAN codes in the machine</p>
                        
                    </div>
                </div> <!-- end card -->
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
                if (value) {
                    selectedCameraId = value;
                    openVideo(selectedCameraId);
                }
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
        // console.log(selectedInput, text)
        if (selectedInput == 'capsule') checkMachinePartner(text);
    }

    async function showMachines(data) {
        const item = data.item;
        const machines = data.machines;

        if (!item) return;

        $('input[name="machine_code"]').val('');
        const clonedDiv = $('.swal-machine-list').clone().show();
        clonedDiv.find('.swal-item-code').text(`${item.digits_code}`);
        clonedDiv.find('.swal-item-description').text(`${item.item_description}`);
        clonedDiv.find('.swal-no-of-tokens').text(`${item.no_of_tokens}`);
        clonedDiv.find('.swal-machines').html(machines.map(machine => `${machine.serial_number} - ${machine.no_of_token} TOKENS`).join('<br>'));
        clonedDiv.find('.machine-tr').css('display', machines.length ? '' : 'none');
        const outerHTML = clonedDiv.prop('outerHTML');
        if (!machines.length) {
            Swal.fire({
                title: `Item Found!`,
                html: outerHTML,
                icon: 'info',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
                reverseButtons: true,
            }).then(() => {
                $('#machine_code').focus();
            });
        }
        else if (machines.length) {
            Swal.fire({
                title: `Machine${machines.length > 1 ? 's' : ''} Found!`,
                html: outerHTML,
                icon: 'info',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
                reverseButtons: true,
            }).then(() => {
                $('#machine_code').focus();
            });
        }
    }

    function processResult(data) {
        // console.log(data);
        if (data.is_missing) {
            Swal.fire({
                title: `${data.missing} code not found.`,
                icon: 'error',
                returnFocus: false,
                reverseButtons: true,
            });
        } else if (data.is_tally == false) {
            const item = data.item;
            const machine = data.machine;
            const clonedDiv = $('.swal-token-mismatch').clone().show();
            const itemTR = $('<tr>');
            const machineTR = $('<tr>');
            const itemTD = $('<td>').text(item.digits_code);
            const itemQtyTD = $('<td>').text(item.no_of_tokens);
            const machineTD = $('<td>').text(machine.serial_number);
            const machineQtyTD = $('<td>').text(machine.no_of_token);
            itemTR.append(itemTD, itemQtyTD);
            machineTR.append(machineTD, machineQtyTD);
            clonedDiv.find('tbody').append(itemTR, machineTR);
            const outerHTML = clonedDiv.prop('outerHTML');
            
            Swal.fire({
                title: `No. of tokens mismatched.`,
                html: outerHTML,
                icon: 'error',
                returnFocus: false,
            });
        } else if (data.is_empty) {
            Swal.fire({
                title: `Item Inventory not found.`,
                icon: 'error',
                returnFocus: false,
            });
        } else if (data.is_sufficient == false) {
            Swal.fire({
                title: `Insufficient capsule qty in stock.`,
                icon: 'error',
                returnFocus: false,
            });
        } else if (data.is_not_exist) {
            Swal.fire({
                title: `Duplicate JAN codes are not allowed.`,
                icon: 'error',
                returnFocus: false,
            });
        } else {
            Swal.fire({
                title: 'Machine successfully refilled.',
                html: `<strong>Ref #: ${data.reference_number} </strong>`,
                icon: 'success',
                returnFocus: false,
            }).then(() => {
                $('input').val('');
            });

        }
    }

    function checkMachinePartner(item_code) {
        $.ajax({
            type: 'POST',
            url: "{{ route('get_partner_machine') }}",
            data: {
                _token: "{{ csrf_token() }}",
                item_code: item_code,
            },
            success: function(res) {
                const data = JSON.parse(res);
                // console.log(data);
                showMachines(data);
            },
            error: function(err) {
                console.log(err);
                Swal.fire({
                    title: "Oops.",
                    html:  'Something went wrong!',
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok',
                    returnFocus: false,
                    reverseButtons: true,
                });
            }
        });
    }

    function numberOnly(numberElement){
        numberElement.value = numberElement.value.replace(/[^0-9]/g,'');
    }

    function validateInput(inputElement) {
        let value = inputElement.value;
        value = Number(value.replace(/[^0-9]/g, ''));
        inputElement.value = value ? value.toLocaleString() : '';
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

    $('#save-btn').on('click', function() {
        Swal.fire({
            title: "Do you want to save the changes?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Save',
            returnFocus: false,
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#real-submit-btn').click();
            }
        });

    });

    $('form').on('submit', function(event) {
        event.preventDefault();
        $('input').get().forEach(input => {
            const value = $(this).val();
            $(this).val(value.trim());
        });
        if (!Number($('#quantity').val().replace(/\D/, ''))) {
            Swal.fire({
                title: 'Oops...',
                html: 'Quantity cannot be 0!',
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
                reverseButtons: true,
            });
            return;
        }
        const formData = $('form').serialize();
        $('#save-btn').attr('disabled', true);
        $.ajax({
            type: 'POST',
            url: "{{ route('submit_capsule_refill') }}",
            data: formData,
            success: function(res) {
                if (res.invalid_capsule_refill) {
                    Swal.fire({
                        title: `Capsule Refill is Unavailable`,
                        html: `${res.invalid_capsule_refill}
                            <br><br>
                            <small><b>COLLECT TOKEN REFERENCE</b></small>
                                <table class="table table-bordered" style="font-size: 70%; text-align:center;border-radius: 10px;">
                                    <thead style="background-color:#3c8dbc;color:white;">
                                        <tr>
                                            <th style="text-align:center;font-weight:normal">
                                                Reference_#
                                            </th>
                                            <th style="text-align:center;font-weight:normal">
                                                Bay
                                            </th>
                                            <th style="text-align:center;font-weight:normal">
                                                Serial_#
                                            </th>
                                            <th style="text-align:center;font-weight:normal">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody style="background-color:#E9DCC9;"">
                                        <tr>
                                            <td>
                                                ${res.collect_token_details.collect_token_header.reference_number}
                                            </td>
                                            <td>
                                                ${res.collect_token_details.collect_token_header.get_bay.name}
                                            </td>
                                            <td>
                                                ${res.collect_token_details.machine_serial.serial_number}
                                            </td>
                                            <td>
                                                ${res.collect_token_details.collect_token_header.get_status.status_description}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            `,
                        icon: 'error',
                        returnFocus: false,
                    });
                } else {
                    const data = JSON.parse(res);
                    processResult(data);
                }
                $('#save-btn').attr('disabled', false);
            },
            error: function(err) {
                Swal.fire({
                    title: "Oops.",
                    html:  'Something went wrong!',
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok',
                    returnFocus: false,
                    reverseButtons: true,
                });
            }
        });
    });

    $('input').on('keydown', function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            $('#save-btn').click();
        }
    });

    $('#item_code').on('input', function() {
        clearTimeout(timeout); 

        timeout = setTimeout(() => {
            checkMachinePartner($('#item_code').val());
        }, 500);
    })

</script>
@endpush