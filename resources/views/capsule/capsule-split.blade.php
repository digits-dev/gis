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

    .swal-table {
        min-width: 600px;
        overflow-x: auto;
    }

    .swal-table table * {
        font-size: 16px !important;
    }

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
    /* .modal-body {
        display: flex;
        justify-content: center;
    } */
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
                <label>CAPSULE SPLIT</label>
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
                        <input  input-for="to_machine" type='text' name='to_machine' id="to_machine" required class='form-control'/>
                        <label class="label label-danger input-validation" id="warning-label-to"></label>
                    </div>
                    <button btn-for="to_machine" type="button" class="btn btn-primary open-camera" tabindex="-1"><i class="fa fa-camera"></i></button>
                </div>
                </div>
                <p style="color: red; font-weight: bold; text-align: center; font-size: 16px;">* Must be same number of tokens *</p>
                <div class='panel-img'>
                    <img src="{{ asset('img/capsule-split.jpg') }}">
                </div>
            </form>
            </div>
            <br>
            <div class='panel-footer'>
                <button class="btn btn-primary btn-submit-size" type="button" id="merge-btn" disabled>Split</button> 
            </div>
        </div>
    </div>

    <div id="addRowModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header label-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center"><strong>Capsule Split</strong></h4>
                </div>
                <div class="row">
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-responsive table-bordered gm_from" id="newItemModalTable">
                                    <thead>
                                        <tr>
                                            <td colspan="4" class="text-center"><p class="text-center text-bold" for="" id="label_from_machine" style="font-size: 18px;"><span id="label_from_item_code" style="color:rgb(48, 133, 214)"></span></p></td>
                                            <td class="text-center"> <p class="text-center text-bold" id="label_to_machine" for="" style="font-size: 18px;"><span id="label_to_item_code" style="color: rgb(67, 136, 113)"></span></p></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center"><b>Item Code</b></td>
                                            <td class="text-center" ><b>Item Description</b></td>
                                            <td class="text-center" ><b>Actual Qty</b></td>
                                            <td class="text-center" ><b>Remaining Qty</b></td>
                                            <td class="text-center"><b>Transfer Qty</b></td>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>   
                            </div>
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
            url: "{{ route('check_split_machines') }}",
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

    $(document).on('input', '.transferQty, .actualQty', function() {
        var currentRow = $(this).closest("tr");
        const currentVal = $(this).val();
        const val = Number(currentVal.replace(/\D/g, ''));
        $(this).val(currentVal ? val.toLocaleString() : '');
        onRemainingQty(currentRow);
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

    function onRemainingQty(currentRow) {

        const actualQty = currentRow.find(".actualQty").val().replace(/\D/g, '');
        const transferQty = currentRow.find(".transferQty").val().replace(/\D/g, '');

        const remainingQty = actualQty - transferQty;
        currentRow.find(".remainingQty").val(remainingQty.toLocaleString());

        $(".actualQty, .transferQty, .remainingQty")
            .not(currentRow.find(".actualQty, .transferQty, .remainingQty"))
            .get()
            .forEach(input => {
                const currentVal = $(input).val();
                if (!currentVal) {
                    $(input).val(0);
                }
            });


    }

    function submitModal() {
        const inputs = $('.modal-body .item-qty').get();
        let data = {
            _token: "{{ csrf_token() }}",
            machine_from: $('#from_machine').val(),
            machine_to: $('#to_machine').val(),
            items: [],
        };
        
        inputs.forEach((input, index) => {
            const row = $(input).parents('tr');
            const transfer_qty = row.find('.transferQty').val();
            const remaining_qty = row.find('.remainingQty').val();
            data.items.push({
                item_code: $(input).attr('item_code'),
                actual_qty: $(input).val().replace(/\D/g, ''),
                transfer_qty: transfer_qty,
                remaining_qty: remaining_qty,
            });
        });
        $.ajax({
            url: "{{ route('submit_split') }}",
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(res) {
                console.log(res);
                if (res.success) {
                    showSplitSuccess(res);
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
        // const rows = $('.modal-body table tbody tr').get();
        // let isValid = true;
        // rows.forEach(row => {
        //     const actualQTyInput = $(row).find('.actualQty');
        //     const transferQtyInput = $(row).find('.transferQty');
        //     const remainingQtyInput = $(row).find('.remainingQty');
        //     const maxQty = Number(actualQTyInput.attr('qty') || 0);
        //     const actualQTyValue = Number(actualQTyInput.val().replace(/\D/g, '') || 0);
        //     const transferQtyValue = Number(transferQtyInput.val().replace(/\D/g, '') || 0);
        //     if (actualQTyValue > maxQty) {
        //         isValid = false;
        //         actualQTyInput.css('border', '2px solid red');
        //     } else {
        //         actualQTyInput.css('border', '');
        //     }

        //     if (transferQtyValue >= actualQTyValue && actualQTyInput) {
        //         transferQtyInput.css('border', '2px solid red');
        //         isValid = false;
        //     } else {
        //         transferQtyInput.css('border', '');
        //     }
        // });
        // $('#save-modal').attr('disabled', !isValidActual);
        let isValidActual = true;
        let isFoundActual = false;
        const actualQtyInputs = $('.actualQty').get();
        const transferQtyInputs = $('.transferQty').get();
        actualQtyInputs.forEach(input => {
            const currentVal = $(input).val(); 
            const value = Number(currentVal.replace(/\D/g, ''));
            const maxValue = Number($(input).attr('qty'));
            if (value > maxValue) {
                $(input).css('border', '2px solid red');
                isValidActual = false;
            } else if (!currentVal) {
                isValidActual = false;
                $(input).css('border', '');
            } else {
                $(input).css('border', '');
            }

            if (!isFoundActual && value) {
                isFoundActual = true;
            } else if (isFoundActual && value) {
                isValidActual = false;
                $('.actualQty').css('border', '2px solid red');
            }
        });

        let isValidTransfer = true;
        let isFoundTransfer = false;

        transferQtyInputs.forEach((input, index) => {
            const currentVal = $(input).val().replace(/\D/g, '');
            const value = Number(currentVal);
            const row = $(input).parents('tr');
            const actualQty = Number((row.find('.actualQty').val()).replace(/\D/g, ''));
            if (value >= actualQty && value) {
                $(input).css('border', '2px solid red');
                isValidTransfer = false;
            } else if (!currentVal) {
                isValidTransfer = false;
                $(input).css('border', '');
            } else {
                $(input).css('border', '');
            }

            if (!isFoundTransfer && value) {
                isFoundTransfer = true;
            } else if (isFoundTransfer && value) {
                isValidTransfer = false;
            }

            if (index == transferQtyInputs.length - 1 && !isFoundTransfer) {
                isValidTransfer = false;
            }
        });
        $('#save-modal').attr('disabled', !(isValidActual && isValidTransfer));
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
            gm_from.forEach((ic, index) => {
                const gm_item_code = $('<td>').text(ic.item_code);
                const gm_description = $('<td>').text(ic.item_description);
                    const qtyInput = $('<input>').attr({
                    qty: ic.qty,
                    item_code: ic.item_code, 
                    machine: data.from_machine.serial_number,
                }).addClass('form-control item-qty actualQty');
                const gm_qty = $('<td>').append(qtyInput);

                const remainingInput = $('<input>').attr('readonly', true).addClass('form-control remainingQty');
                const remaining_qty = $('<td>').append(remainingInput);

                const transferInput = $('<input>').addClass('form-control transferQty');
                const transfer_qty = $('<td>').append(transferInput);
                
                const tr = $('<tr>').append(gm_item_code, gm_description, gm_qty, remaining_qty, transfer_qty );
                $('.gm_from tbody').append(tr);
            });

            $('#addRowModal').modal('show');
        }
    }

    function showSummary(){
        const from_header = $('#label_from_item_code').text();
        const to_header = $('#label_to_item_code').text();

        const wrapper = $('<div class="swal-table">');
        wrapper.append(`<p style="font-weight: bold; font-size: 20px;">From Machine <span style="color:rgb(48, 133, 214)">${from_header}</span> to Machine <span style="color: rgb(67, 136, 113)">${to_header}</span></p>`);

        const from_machine = $('.gm_from').clone();
        from_machine.find('input').get().forEach(input => {
            const td = $(input).parents('td');
            const val = $(input).val();
            td.text(val);
            $(input).remove();
        });

        wrapper.append(from_machine);

        Swal.fire({
            title: "Are you sure?",
            html: `<p style="font-weight: bold; font-size: 20px;">From Machine <span style="color:rgb(48, 133, 214)">${from_header}</span> to Machine <span style="color: rgb(67, 136, 113)">${to_header}</span></p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            reverseButtons: true,
            returnFocus: false,
            html: wrapper,
            width: '800px',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#save-modal, button[data-dismiss="modal"]').attr('disabled', true);
                submitModal();
            }
        });
    }

    function showSplitSuccess(data){

        const wrapper = $('<div class="swal-table" style="overflow-x: auto; min-width: 600px;">')
        wrapper.append(`<p style="font-weight: bold; font-size: 20px;">From Machine <span style="color: rgb(48, 133, 214)">${data.machine_from.serial_number}</span> to Machine <span style="color: rgb(67, 136, 113)">${data.machine_to.serial_number}</span></p>`);
        wrapper.append(`<label class="label label-success text-center" style="display: inline-block; font-size: 100%; margin-bottom: 18px;">Reference #: ${data.reference_number}</label>`);
        const from_machine = $('.gm_from').clone();
        from_machine.find('tbody').html('');

        data.items.filter(item => !!Number(item.actual_qty)).forEach((item) => {
            const tr = $('<tr>');
            const item_code = $('<td>').text(item.item_code);
            const item_description = $('<td>').text(item.item_description);
            const actual_qty = $('<td>').text(item.actual_qty.toLocaleString());
            const remaining_qty = $('<td>').text(item.remaining_qty.toLocaleString());
            const transfer_qty = $('<td>').text(item.transfer_qty.toLocaleString());

            tr.append(item_code, item_description, actual_qty, remaining_qty, transfer_qty);
            from_machine.find('tbody').append(tr);
        });

        wrapper.append(from_machine);
        
        Swal.fire({
            title: "Split successful!",
            icon: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok',
            returnFocus: false,
            html: wrapper,
            width: '800px',
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