@extends('crudbooster::admin_template')
@push('head')
   <link rel="stylesheet" href="{{ asset('css/capsule-refill.css') }}">
   <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
   <script src="{{ asset('js/code-scanner.js') }}"></script>
   <script src="{{ asset('plugins/sweetalert.js') }}"></script>
@endpush
@section('content')
    <div class="panel-content">
        <div class='panel panel-default'>
            <div class='panel-header'>
                <label>CAPSULE REFILL</label>
            </div>
            <div class='panel-body'>
                <div id="reader-wrapper" style="z-index: -3">
                    <div id="reader"></div>
                </div>
                <form method='post' action='{{CRUDBooster::mainpath('add-save')}}'>
                    <div class='form-group'>
                        <label>Capsule Barcode</label>
                        <div class="flex input-btn">
                            <input input-for="capsule" type='text' name='label1' required class='form-control'/>
                            <button btn-for="capsule" type="button" class="btn btn-primary open-camera"><i class="fa fa-camera"></i></button>
                        </div>
                        <label>To Gasha Machine</label>
                        <div class="flex input-btn">
                            <input input-for="machine" type='text' name='label1' required class='form-control'/>
                            <button btn-for="machine" type="button" class="btn btn-primary open-camera"><i class="fa fa-camera"></i></button>
                        </div>
                        <label>Quantity</label>
                        <input type='text' name='label1' required class='form-control' id="quantity"/>
                    </div>
                    <div class='panel-img'>
                        <img src="{{ asset('img/capsule-refill.png') }}">
                    </div>
                                    
                </form>
            </div>
            <div class='panel-footer'>
            <button class="btn btn-primary" id="save-btn">Save Changes</button>
            </div>
        </div>
    </div>
@endsection
@push('bottom')
<script>
    let selectedCameraId = null;
    let selectedInput = null;
    let html5QrCode = null;
    async function showCameraOptions(cameras) {
        let cameraOptions = {}
        cameras.forEach(camera => cameraOptions[camera.id] = camera.label);
        const { value: fruit } = await Swal.fire({
            title: 'Select a camera',
            input: 'select',
            inputOptions: cameraOptions,
            inputPlaceholder: 'Select a camera',
            showCancelButton: true,
            inputValidator: (value) => {
                selectedCameraId = value;
                openVideo(selectedCameraId);
            }
        });

    }

    function openVideo(cameraId) {
        if (html5QrCode) html5QrCode.stop();
        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
        cameraId, 
        {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        },
        (decodedText, decodedResult) => {
            html5QrCode.stop();
            html5QrCode = null;
            populateInput(decodedText);
            $('#reader-wrapper').css('z-index', '-3')
            
        },
        (errorMessage) => {
            // parse error, ignore it.
        })
        .catch((err) => {
        // Start failed, handle it.
        });

        $('#reader-wrapper').css('z-index', '1')
    }

    function populateInput(text) {
        $(`input[input-for="${selectedInput}"]`).val(text);
    }

    $('.open-camera').on('click', function() {
        selectedInput = $(this).attr('btn-for');
        console.log(selectedInput);
        if (selectedCameraId) {
            openVideo(selectedCameraId);
            return;
        } 
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                console.log(devices);
                if (!selectedCameraId) {
                    showCameraOptions(devices);
                }
            }
        }).catch(err => {
            alert('no cameras detected');
        });
    });

</script>
@endpush