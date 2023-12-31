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
        left: 50%;
        transform: translateX(-50%);
    }

    .input-btn {
        margin-bottom: 30px;
    }

    .form-group p{
        margin: 7px 0 ;
    }

    table tr td{
        border: 1px solid #bdbdbd !important;
        text-align: center !important;
        vertical-align: middle !important;
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
                <p style="font-size: 16px;"><span style="color: red;">* </span>From Gasha Machine</p>
                <div class="flex input-btn">
                    <div class="machine-group">
                        <input input-for="from_machine" type='text' name='from_machine' id="from_machine" required class='form-control'/>
                        <label class="label label-danger input-validation" id="warning-label-from"></label>
                    </div>
                    <button btn-for="from_machine" type="button" class="btn btn-primary open-camera" tabindex="-1"><i class="fa fa-camera"></i></button>
                </div>
                <p style="font-size: 16px;"><span style="color: red;">* </span>To Gasha Machine</p>
                <div class="flex input-btn">
                    <div class="machine-group">
                        <input input-for="to_machine" type='text' name='to_machine' id="to_machine" required class='form-control'/>
                        <label class="label label-danger input-validation" id="warning-label-to"></label>
                    </div>
                    <button btn-for="to_machine" type="button" class="btn btn-primary open-camera" tabindex="-1"><i class="fa fa-camera"></i></button>
                </div>
                </div>
                <p style="color: red; font-weight: bold; text-align: center; font-size: 16px;">* Must be same number of tokens *</p>
                <div class='panel-img'>
                    <img src="{{ asset('img/capsule-merge.jpg') }}">
                </div>
            </form>
            </div>
            <br>
            <div class='panel-footer'>
                <button class="btn btn-primary btn-submit-size" type="button" id="merge-btn" disabled>Merge</button> 
            </div>
        </div>
    </div>

    <div id="addRowModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header label-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center"><strong>Capsule Merge</strong></h4>
                </div>
                <div class="row">
                    <div class="modal-body">
                        <div class="col-md-6">
                            <p class="text-center text-bold" for="" id="label_from_machine" style="font-size: 18px;">From Machine <span id="label_from_item_code" style="color:rgb(48, 133, 214)"></span></p>
                            <table class="table table-responsive table-bordered gm_from" id="newItemModalTable">
                                <thead>
                                    <tr>
                                        <td class="text-center" width="25%"><b>Item Code</b></td>
                                        <td class="text-center" width="50%"><b>Item Description</b></td>
                                        <td class="text-center" width="25%"><b>Qty</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-bold" colspan="2">Total</td>
                                        <td class="text-center"><span class="total-item-qty" id="from-machine-total">0</span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <p class="text-center text-bold" id="label_to_machine" for="" style="font-size: 18px;">To Machine <span id="label_to_item_code" style="color: rgb(67, 136, 113)"></span></p>
                            <table class="table table-responsive table-bordered gm_to" id="newItemModalTable">
                                <thead>
                                    <tr>
                                        <td class="text-center" width="25%"><b>Item Code</b></td>
                                        <td class="text-center" width="50%"><b>Item Description</b></td>
                                        <td class="text-center hide" width="25%"><b>Qty</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr style="display: none;">
                                        <td class="text-bold" colspan="2">Total</td>
                                        <td class="text-center"><span class="total-item-qty" id="to-machine-total">0</span></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <p class="no-inventory" style="display: none; color: red; font-weight: bold; text-align: center;">No current inventory for this machine</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Cancel</button>
                    <button type='button' id="save-modal" class="btn btn-primary" disabled>
                        <i class="fa fa-save"></i> 
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('bottom')
<script>

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
            success: function(res) {
                console.log(res);
                handleMachineCheck(res);
            },
            error: function(err) {
                console.log(err);
            }
        });
    });

    $(document).on('click', 'button[data-dismiss="modal"]', function() {
        resetModal();
    });

    $(document).on('input', '.item-qty', function() {
        const currentVal = $(this).val();
        const val = Number(currentVal.replace(/\D/g, ''));
        $(this).val(currentVal ? val.toLocaleString() : '');
        sumQty();
        validateInput();
    });

    $('#from_machine, #to_machine').on('input', function() {
        const fromMachineVal = $('#from_machine').val().trim();
        const toMachineVal = $('#to_machine').val().trim();
        const isDisabled = (fromMachineVal == toMachineVal) || !(fromMachineVal && toMachineVal);
        $('#merge-btn').attr('disabled', isDisabled);
    });

    $('#save-modal').on('click', function() {

        showSummary();
    })

    function submitModal() {
        const inputs = $('.modal-body .item-qty').get();
        let data = {
            _token: "{{ csrf_token() }}",
            machine_from: $('#from_machine').val(),
            machine_to: $('#to_machine').val(),
            items: [],
        };
        
        inputs.forEach((input, index) => {
            
            data.items.push({
                item_code: $(input).attr('item_code'),
                qty: $(input).val().replace(/\D/g, '')
            });
        });

        $.ajax({
            url: "{{ route('submit_merge') }}",
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(res) {
                console.log(res);
                if (res.success) {
                    showMergeSuccess(res);
                } else {
                    alert('Something went wrong...');
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    function sumQty() {
        let sum = 0;
        const qtyInput = $('.gm_from .item-qty').get();
        qtyInput.forEach(input => {
            const value = Number($(input).val().replace(/\D/g, ''));
            sum += value;
        });

        $('#from-machine-total').text(sum.toLocaleString());
    }

    function validateInput() {
        const qtyInput = $('.gm_from .item-qty').get();
        let isValid = true;
        let found = false;
        qtyInput.forEach(input => {
            const currentVal = $(input).val(); 
            const value = Number(currentVal.replace(/\D/g, ''));
            const maxValue = Number($(input).attr('qty'));
            if (value > maxValue) {
                $(input).css('border', '2px solid red');
                isValid = false;
            } else if (!currentVal) {
                isValid = false;
                $(input).css('border', '');
            } else {
                $(input).css('border', '');
            }

            if (!found && value) {
                found = true;
            } else if (found && value) {
                isValid = false;
            }
        });
        isValid = isValid && !qtyInput.every(input => !Number($(input).val().replace(/\D/g, '')));


        $('#save-modal').attr('disabled', !isValid);
    }

    function resetModal() {
        $('.gm_from tbody, .gm_to tbody').html('');
        $('#from-machine-total').text('0')
        $('#save-modal').attr('disabled', true);
    }

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
        } else {
            const gm_from = data.gm_list_from;
            const gm_to = data.gm_list_to;
            const isEmpty = gm_from.every(item => item.qty == 0);
            if (isEmpty) {
                $('#warning-label-from').text('No current Inventory for this machine.');
                return;
            }
            $('#label_from_machine span').text(data.from_machine.serial_number);
            $('#label_to_machine span').text(data.to_machine.serial_number);
            $('.gm_from tbody').html('');
            gm_from.forEach((ic, index) => {
                const gm_item_code = $('<td>').text(ic.item_code);
                const gm_description = $('<td>').text(ic.item_description);
                const qtyInput = $('<input>').attr({
                    qty: ic.qty,
                    item_code: ic.item_code, 
                    machine: data.from_machine.serial_number,
                }).addClass('form-control item-qty');
                const gm_qty = $('<td>').append(qtyInput);
                
                const tr = $('<tr>').append(gm_item_code, gm_description, gm_qty);
                $('.gm_from').append(tr);
            });

            if (!data.gm_list_to.length) {
                $('.no-inventory').show();
            } else {
                $('.no-inventory').hide();

                let toMachineTotal = 0;
                $('.gm_to tbody').html('');
                gm_to.forEach((ic, index) => {
                    const gm_item_code = $('<td>').text(ic.item_code);
                    const gm_description = $('<td>').text(ic.item_description);
                    const qtyInput = $('<input>')
                        .attr('readonly', true)
                        .addClass('form-control')
                        .val(ic.qty.toLocaleString());
                    const gm_qty = $('<td style="display: none;">').append(qtyInput);
                    
                    const tr = $('<tr>').append(gm_item_code, gm_description, gm_qty);
                    toMachineTotal += ic.qty;
                    
                    $('.gm_to').append(tr);
                });

                $('#to-machine-total').text(toMachineTotal.toLocaleString());
            }

            $('#addRowModal').modal('show');
        }
    }

    function showSummary(){
        const from_header = $('#label_from_item_code').text();
        const to_header = $('#label_to_item_code').text();

        const wrapper = $('<div>')
        wrapper.append(`<p style="font-weight: bold; font-size: 20px;">From Machine <span style="color:rgb(48, 133, 214)">${from_header}</span> to Machine <span style="color: rgb(67, 136, 113)">${to_header}</span></p>`);

        const from_machine = $('.gm_from').clone();
        from_machine.find('input').css({'font-size':'20px', 'border':'1px solid white'});

        wrapper.append(from_machine);

        Swal.fire({
            title: "Are you sure?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            reverseButtons: true,
            returnFocus: false,
            html: wrapper,
            width: '600px',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#save-modal, button[data-dismiss="modal"]').attr('disabled', true);
                submitModal();
            }
        });
    }

    function showMergeSuccess(data){

        const wrapper = $('<div>')
        wrapper.append(`<p style="font-weight: bold; font-size: 20px;">From Machine <span style="color: rgb(48, 133, 214)">${data.machine_from.serial_number}</span> to Machine <span style="color: rgb(67, 136, 113)">${data.machine_to.serial_number}</span></p>`);
        wrapper.append(`<label class="label label-success text-center" style="display: inline-block; font-size: 100%; margin-bottom: 18px;">Reference #: ${data.reference_number}</label>`);
        const to_machine = $('.gm_to').clone();
        to_machine.find('tbody').html('');
        to_machine.find('#to-machine-total').text('');

        let total = 0;

        data.items.filter(item => !!Number(item.qty)).forEach((item) => {
            const tr = $('<tr>');
            const item_code = $('<td>').text(item.item_code);
            const item_description = $('<td>').text(item.item_description);
            const qty = $('<td>').text(item.qty.toLocaleString());

            tr.append(item_code, item_description, qty);
            to_machine.find('tbody').append(tr);

            total += item.qty;
        })

        to_machine.find('#to-machine-total').text(total.toLocaleString());
        wrapper.append(to_machine);
        wrapper.find('.hide').removeClass('hide');

        Swal.fire({
            title: "Merged successfully",
            icon: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok',
            returnFocus: false,
            html: wrapper,
            width: '600px',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#save-modal, button[data-dismiss="modal"]').attr('disabled', false);
                $('button[data-dismiss="modal"]').eq(0).click();
                $('#from_machine, #to_machine').val('');
                $('#merge-btn').attr('disabled', true);
            }
        });;
    }
</script>
@endpush