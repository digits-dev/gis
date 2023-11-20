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
                <form method="POST" autocomplete="off">
                    @csrf
                    <div class='form-group'>
                        <div class="machine-one-div">
                            <div class="machine-one-label">
                                <label>Machine One<span style="color: red"></span></label>
                            </div>
                            <div class="flex input-btn">
                                <input  type='number' input-for="machine-one"  required class='form-control text-center'/>
                                <button type="button" btn-for="machine-one" class="btn btn-primary open-camera"><i class="fa fa-camera"></i></button>
                            </div>
                            <div class="table_summary styled-table-swap" style="display: none">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>JAN Number</th>
                                            <th>QTY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>JAN Number</td>
                                            <td class="jan-number-cell"></td>
                                        </tr>
                                        <tr>
                                            <td>JAN Number</td>
                                            <td class="jan-number-cell"></td>
                                        </tr>
                                        <tr>
                                            <td>JAN Number</td>
                                            <td class="jan-number-cell"></td>
                                        </tr>
                                        <tr>
                                            <td>JAN Number</td>
                                            <td class="jan-number-cell"></td>
                                        </tr>
                                        <tr>
                                            <td>JAN Number</td>
                                            <td class="jan-number-cell"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="machine-two-div">
                            <div class="machine-two-label">
                                <label>Machine Two <span style="color: red"></span></label>
                            </div>
                            <div class="flex input-btn">
                                <input  type='number' input-for="machine-two" required class='form-control text-center'/>
                                <button type="button" btn-for="machine-two" class="btn btn-primary open-camera"><i class="fa fa-camera"></i></button>
                            </div>
                            <div class="table_summary styled-table-swap" style="display: none" >
                                <table>
                                    <thead>
                                        <tr>
                                            <th>JAN Number</th>
                                            <th>QTY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>JAN Number</td>
                                            <td class="jan-number-cell"></td>
                                        </tr>
                                        <tr>
                                            <td>JAN Number</td>
                                            <td class="jan-number-cell"></td>
                                        </tr>
                                        <tr>
                                            <td>JAN Number</td>
                                            <td class="jan-number-cell"></td>
                                        </tr>
                                        <tr>
                                            <td>JAN Number</td>
                                            <td class="jan-number-cell"></td>
                                        </tr>
                                        <tr>
                                            <td>JAN Number</td>
                                            <td class="new-token-amount-cell"></td>
                                        </tr>
                                    </tbody>
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

    function displaySelectedInputLabel(selectedInput) {
        const labelContainer = $('.machine-camera-label label');
        labelContainer.text(selectedInput);
        labelContainer.parent().show(); // Display the div containing the label
    }

    function populateInput(text) {
        $(`input[input-for="${selectedInput}"]`).val(text);
        console.log(selectedInput, text)
        if (selectedInput == 'capsule') checkMachinePartner(text);
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



</script>
@endpush