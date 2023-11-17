<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@push('head')
   <link rel="stylesheet" href="{{ asset('css/capsule-refill.css') }}">
   <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
   <script src="{{ asset('js/code-scanner.js') }}"></script>
   <script src="{{ asset('plugins/sweetalert.js') }}"></script>
@endpush
@section('content')
<style>

    .btn-blue{
        background-color: rgb(48, 133, 214);
    }

    .btn-red{
        background-color: rgb(221, 51, 51);
    }
    input{
        text-align: center;
    }
    .danger{
        color: red;
    }
    /* .swal2-popup {
        padding: 0;
    } */
    .swal-inputs{
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        margin: 10px 0;
        justify-content: space-evenly;
    }
    .swal-inputs label{
        width: 40%;
    }
    .swal-inputs input{
        width: 40%;
        /* width: 100px; */
        border-radius: 5px;
        font-size: 16px;
        height: 35px;
        /* margin-left: 50px; */

    }
    .swal-inputs input:focus{
        outline: none;
    }
    .return_quantity_content{
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .label-input{
        border: 1px solid white;
    }
    .label-input:read-only{
        background-color: #fff;
    }
    #save_gm, .save_gm{
        padding: 8px 20px;
        background-color: #3c8dbc;
        border: 1px solid #f2f1f100;
        color: white;
        outline: none;
    }
    #cancel_gm{
        padding: 8px 15px;
        background-color: #d33;
        border: 1px solid #f2f1f100;
        color: white;
        outline: none;
    }
    #save_gm:hover,#cancel_gm:hover{
        opacity: 0.7;
    }
    .bg-color{
        background: rgb(216, 216, 216);
    }
    .total_quantity{
        font-size: 15px;
        color: #3c8dbc
    }
    .save_gm:disabled{
        background-color: #9c9c9c;
    }

    .swal-clone {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            animation: bounceIn 0.6s cubic-bezier(0.68, -0.55, 0.27, 1.55);
    }

    @keyframes bounceIn {
        0% {
            transform: translate(-50%, -50%) scale(0.8);
        }
        70% {
            transform: translate(-50%, -50%) scale(1.1);
        }
        100% {
            transform: translate(-50%, -50%) scale(1);
        }
    }


    .swal2-container1 {
        position: relative;
        width: 37em;
        padding: 30px 0 !important;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }


    .warning{
        color: #f8bb86;
        border: 1px solid #facea8;
        width: 5em;
        height: 5em;
        border-radius: 50%;
        display: grid;
        place-content: center;
    }

    .warning span{
        font-size: 50px;
    }

    .warning-content #warning-title{
        padding: .8em 1em 0;
        font-size: 1.875em;
        font-weight: 600;
        text-align: center;
        max-width: 100%;
    }

    .warning-content #warning-message{
        margin: 1em 1.6em .3em;
        font-size: 1.125em;
        text-align: center;
    }

    .swal-buttons{
        margin: 1.25em auto 0;
    }

    .swal-btn{
        border: none;
        color: #fff;
        padding: 7px 15px;
        margin: 5px;
        border-radius: 5px;
    }

    .machine-group {
        position: relative;
        width: 100%;
        max-width: 550px;
    }

    .input-validation {
        position: absolute;
        bottom: -24px;
        left: 0;
        /* left: 50%;
        transform: translateX(-50%) */
    }

    .form-group p{
        margin: 20px 0;
    }

</style>
    <!-- Your html goes here -->
    <div class="panel-content">

        <div class="swal-clone hide">
            <div class="swal2-container1">
                <div class="warning">
                    <span>!</span>
                </div>
                <div class="warning-content">
                    <p id="warning-title">Are you sure?</p>
                    <p id="warning-message">You Won't be able to revert this!</p>
                </div>
                <div class="swal-buttons">
                    <button type="button" class="swal-btn btn-red swal-btn-cancel">Cancel</button>
                    <button type="button" class="swal-btn btn-blue swal-btn-save">Yes, save it</button>
                </div>
            </div>

        </div>

        <div class='panel panel-default'>
            <div class='panel-header'>
                <label>CAPSULE MERGE</label>
            </div>
            <div class='panel-body'>
            <div id="reader-wrapper" style="display: none;">
                <div class="close-reader">×</div>
                <div id="reader"></div>
            </div>
            <form method='post' action='{{CRUDBooster::mainpath('add-save')}}'>
                <div class='form-group'>
                <p>From Gasha Machine</p>
                <div class="flex input-btn">
                    <div class="machine-group">
                        <input input-for="from_machine" type='text' name='from_machine' id="from_machine" required class='form-control'/>
                        <label class="label label-danger input-validation" id="warning-label-from"></label>
                    </div>
                    <button btn-for="from_machine" type="button" class="btn btn-primary open-camera"><i class="fa fa-camera"></i></button>
                </div>
                <p></p>
                <p>To Gasha Machine</p>
                <div class="flex input-btn">
                    <div class="machine-group">
                        <input input-for="to_machine" type='text' name='to_machine' id="to_machine" required class='form-control'/>
                        <label class="label label-danger input-validation" id="warning-label-to"></label>
                    </div>
                    <button btn-for="to_machine" type="button" class="btn btn-primary open-camera"><i class="fa fa-camera"></i></button>
                </div>
                </div>
                <div class='panel-img'>
                    <img src="{{ asset('img/capsule-merge.jpg') }}">
                </div>
            </form>
            </div>
            <br>
            <div class='panel-footer'>
                <button class="btn btn-primary btn-submit-size" type="button" id="merge-btn">Merge</button> 
            </div>
        </div>
    </div>

    <div id="addRowModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header label-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center"><strong>Capsule Merge</strong></h4>
                </div>
                <div class="row">
                    <div class="modal-body">
                        <div class="col-md-6">
                            <p class="text-center" for="">PH0001</p>
                            <table class="table table-responsive table-bordered" id="newItemModalTable">
                                <thead>
                                    <tr>
                                        <td class="text-center" width="50%"><b>Item Code</b></td>
                                        <td class="text-center" width="50%"><b>Qty</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="pull-right">Total</td>
                                        <td class="text-center"><span id="total-item-qty">0</span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <p class="text-center" for="">PH0002</p>
                            <table class="table table-responsive table-bordered" id="newItemModalTable">
                                <thead>
                                    <tr>
                                        <td class="text-center" width="50%"><b>Item Code</b></td>
                                        <td class="text-center" width="50%"><b>Qty</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="pull-right">Total</td>
                                        <td class="text-center"><span id="total-item-qty">0</span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type='button' id="add-cycle-count" class="btn btn-primary"><i
                            class="fa fa-save"></i> Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('bottom')
<script>

    // $('#addRowModal').modal('show');

    $('.content-header').hide();
    
    // Read Barcode => start
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
            // html5QrCode = null;
            console.log(decodedText);
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
        $(`input[input-for="${selectedInput}"]`).trigger('input');
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

    $('input').on('keydown', function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
            $('#save-btn').click();
        }
    })
    // Read Barcode => end

    $('#from_machine, #to_machine').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    })

    $('#merge-btn').on('click', function(event) {

        const fromMachine = $('#from_machine').val().trim();
        const toMachine = $('#to_machine').val().trim();

        $.ajax({
            type: "POST",
            url: "{{ route('check_machines') }}",
            dataType: 'json',
            data: {
                _token: "{{ csrf_token() }}",
                from_machine: fromMachine,
                to_machine: toMachine
            },
            success: function(res){
                console.log(res);
                handleMachineCheck(res);
            },
            error: function(err){
                console.log(err);
            }
        });
    });

    function handleMachineCheck(data) {
        $('#warning-label-from').text(data.missing_from ? 'Machine Not Found!' : '');
        $('#warning-label-to').text(data.missing_to ? 'Machine Not Found!' : '');
        if (data.missing_from || data.missing_to) return;
        else if (data.is_tally == false) {
            Swal.fire({
                title: `Capsule Token value mismatch.`,
                icon: 'error',
                returnFocus: false,
            });
        }
    }


</script>
@endpush