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
   </style>
@endpush
@section('content')
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
                <form method="POST">
                    @csrf
                    <div class='form-group'>
                        <label>Capsule Barcode <span style="color: red">*</span></label>
                        <div class="flex input-btn">
                            <input input-for="capsule" type='number' id="item_code" name='item_code' required class='form-control'/>
                            <button btn-for="capsule" type="button" class="btn btn-primary open-camera"><i class="fa fa-camera"></i></button>
                        </div>
                        <label>To Gasha Machine <span style="color: red">*</span> </label>
                        <div class="flex input-btn">
                            <input input-for="machine" type='text' id="tiem_code" name='machine_code' oninput="this.value = this.value.toUpperCase()" required class='form-control'/>
                            <button btn-for="machine" type="button" class="btn btn-primary open-camera"><i class="fa fa-camera"></i></button>
                        </div>
                        <label>Quantity <span style="color: red">*</span></label>
                        <input type='number' name='qty' required class='form-control' oninput="numberOnly(this)" id="quantity" min="1"/>
                    </div>
                    <div class='panel-img'>
                        <img src="{{ asset('img/capsule-refill.png') }}">
                    </div>
                     <button class="hide" type="submit" id="real-submit-btn"></button> 
                </form>
            </div>
            <div class='panel-footer'>
            <button class="btn btn-primary" id="save-btn" data-swal-toast-template="#my-template">Save</button>
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

    function populateInput(text) {
        $(`input[input-for="${selectedInput}"]`).val(text);
        console.log(selectedInput, text)
        if (selectedInput == 'capsule') checkMachinePartner(text);
    }

    async function showMachines(data) {
        const item = data.item;
        const machines = data.machines;
        let machineOptions = {};
        machines.forEach(machine => {
            machineOptions[machine.serial_number] = machine.serial_number; 
        });

        if (!machines.length) {
            $('input[name="machine_code"]').val('');
        }
        else if (machines.length) {
            const serialNumbers = machines.map(machine => `<strong>${machine.serial_number}</strong>`);
            Swal.fire({
                title: "Machine Found.",
                html:  `This item: <strong>(${item.digits_code} - ${item.item_description})</strong> is found in machine ${serialNumbers.join(', ')}!`,
                icon: 'info',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok',
                returnFocus: false,
            });
        }
    }

    function processResult(data) {
        console.log(data);
        if (data.is_missing) {
            Swal.fire({
                title: `${data.missing} code not found.`,
                icon: 'error',
                returnFocus: false,
            });
        } else if (data.is_tally == false) {
            Swal.fire({
                title: `No. of tokens mismatched.`,
                html: `${data.item.digits_code} is worth ${data.item.no_of_tokens} tokens and ${data.machine.serial_number} accepts ${data.machine.no_of_token} tokens.`,
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
        } else {
            Swal.fire({
                title: 'Machine successfully refilled.',
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
                showMachines(data);
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

    function numberOnly(numberElement){
        numberElement.value = numberElement.value.replace(/[^0-9]/g,'');
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
        }).then((result) => {
            if (result.isConfirmed) {
                $('#real-submit-btn').click();
            }
        });

    });

    $('form').on('submit', function(event) {
        event.preventDefault();
        const formData = $('form').serialize();
        $.ajax({
            type: 'POST',
            url: "{{ route('submit_capsule_refill') }}",
            data: formData,
            success: function(res) {
                const data = JSON.parse(res);
                processResult(data);
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