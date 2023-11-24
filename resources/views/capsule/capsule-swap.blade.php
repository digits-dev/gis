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

    .swal2-html-container{
        overflow: unset !important;
    }

    table tr td{
        border: 1px solid #bdbdbd !important;
        text-align: center !important;
        vertical-align: middle !important;
    }
</style>
    <!-- Your html goes here -->
    <div class="panel-content">
        <div class='panel panel-default'>
            <div class='panel-header'>
                <label>CAPSULE SWAP</label>
            </div>
            <div class='panel-body'>
            <div id="reader-wrapper" style="display: none;">
                <div class="close-reader">×</div>
                <div id="reader"></div>
            </div>
            <form method='POST' action="{{CRUDBooster::mainpath('add-save')}}" autocomplete="off" id="capsuleSwapForm">
                @csrf
                <div class='form-group'>
                    <p style="font-size: 16px;"><span style="color: red;">* </span>Gasha Machine One</p>
                    <div class="flex input-btn">
                    <div class="machine-group">
                        <input input-for="from_machine" type='text' id="machine_no_one" name="machine_no_one" required class='form-control'/>
                        <label class="label label-danger input-validation" id="warning-label-from"></label>
                    </div>
                    <button btn-for="from_machine" type="button" class="btn btn-primary open-camera" tabindex="-1"><i class="fa fa-camera"></i></button>
                </div>
                <p style="font-size: 16px;"><span style="color: red;">* </span>Gasha Machine Two</p>
                <div class="flex input-btn">
                    <div class="machine-group">
                        <input input-for="to_machine" type='text'id="machine_no_two" name="machine_no_two" required class='form-control'/>
                        <label class="label label-danger input-validation" id="warning-label-to"></label>
                    </div>
                    <button btn-for="to_machine" type="button" class="btn btn-primary open-camera" tabindex="-1"><i class="fa fa-camera"></i></button>
                </div>
                </div>
                <p style="color: red; font-weight: bold; text-align: center; font-size: 16px;">* Must be same number of tokens *</p>
                <div class='panel-img'>
                    <img src="{{ asset('img/capsule-swap.png') }}">
                </div>
            </form>
            </div>
            <br>
            <div class='panel-footer'>
                <button class="btn btn-primary btn-submit-size" type="button" id="merge-btn" disabled>Swap</button> 
            </div>
        </div>
    </div>

    <div id="addRowModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header label-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center"><strong>Capsule Swap</strong></h4>
                </div>
                <div class="row swap-table-summary">
                    <div class="modal-body">
                        <div class="col-md-6">
                            <p class="text-center text-bold" for="" id="label_from_machine" style="font-size: 16px;">Gasha Machine One: <span style="color:rgb(48, 133, 214)"></span></p>
                            <table class="table table-bordered gm_from" id="newItemModalTable">
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
                                        <td class="text-center" id="from-machine-total"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <p class="text-center text-bold" id="label_to_machine" for="" style="font-size: 16px;">Gasha Machine Two: <span style="color: rgb(67, 136, 113)"></span></p>
                            <table class="table table-bordered gm_to" id="newItemModalTable">
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
                                        <td class="text-center" id="to-machine-total"></td>
                                    </tr>
                                </tfoot>
                            </table>
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

    $('#machine_no_one, #machine_no_two').on('input', function() {
        $(this).val($(this).val().toUpperCase());
    })

    $('#merge-btn').on('click', function(event) {

        const fromMachine = $('#machine_no_one').val().trim();
        const toMachine = $('#machine_no_two').val().trim();

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

    $(document).on('input', '.item-qty2', function() {
        const currentVal = $(this).val();
        const val = Number(currentVal.replace(/\D/g, ''));
        $(this).val(currentVal ? val.toLocaleString() : '');
        sumQty2();
        validateInput();
    });

    $('#machine_no_one, #machine_no_two').on('input', function() {
        const fromMachineVal = $('#machine_no_one').val().trim();
        const toMachineVal = $('#machine_no_two').val().trim();
        $('#merge-btn').attr('disabled', fromMachineVal == toMachineVal);
    });

    $('#save-modal').on('click', function() {
    
        swapTableSummary();
    });

    function submitModal() {
        const inputs = $('.item-qty').get();
        const inputs2 = $('.item-qty2').get();
        let data = {
            _token: "{{ csrf_token() }}",
            machine_no_one: $('#machine_no_one').val(),
            capsule_qty_one_total: $('#capsule_qty_one_total').val(),
            machine_no_two: $('#machine_no_two').val(),
            capsule_qty_two_total: $('#capsule_qty_two_total').val(),
        
            machine_no_two: $('#machine_no_two').val(),
            jan_no_one: [],
            jan_no_two: [],
        };
        
        inputs.forEach((input, index) => {
            data.jan_no_one.push({
                jan_no_one: $(input).attr('item_code'),
                capsule_qty_one: $(input).val().replace(/\D/g, ''),
            });
        });

        inputs2.forEach((input, index) => {
            data.jan_no_two.push({
                jan_no_two: $(input).attr('item_code'),
                capsule_qty_two: $(input).val().replace(/\D/g, ''),
            });
        });

        $.ajax({
            url: "{{ route('submit_swap') }}",
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(res) {
                if (res.success) {
                    showSwapSuccess(res);
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

        $('input[name="capsule_qty_one_total[]"]').val(sum.toLocaleString());
        $('input[name="capsule_qty_one_total[]"]').attr('totalOneValue',sum.toLocaleString());
    }

    function sumQty2() {
        let sum2 = 0;
        const qtyInput2 = $('.gm_to .item-qty2').get();
        qtyInput2.forEach(input => {
            const value = Number($(input).val().replace(/\D/g, ''));
            sum2 += value;
        });

        $('input[name="capsule_qty_two_total[]"]').val(sum2.toLocaleString());
        $('input[name="capsule_qty_two_total[]"]').attr('totalTwoValue',sum2.toLocaleString());
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

        const qtyInput2 = $('.gm_to .item-qty2').get();
        let isValid2 = true;
        let found2 = false;
        qtyInput2.forEach(input => {
            const currentVal2 = $(input).val(); 
            const value2 = Number(currentVal2.replace(/\D/g, ''));
            const maxValue2 = Number($(input).attr('qty'));
            if (value2 > maxValue2) {
                $(input).css('border', '2px solid red');
                isValid = false;
            } else if (!currentVal2) {
                isValid = false;
                $(input).css('border', '');
            } else {
                $(input).css('border', '');
            }

            if (!found2 && value2) {
                found2 = true;
            } else if (found2 && value2) {
                isValid2 = false;
            }
        });


        isValid = isValid && !qtyInput.every(input => !Number($(input).val().replace(/\D/g, '')));
        isValid2 = isValid2 && !qtyInput2.every(input => !Number($(input).val().replace(/\D/g, '')));


        $('#save-modal').attr('disabled', !(isValid && isValid2));
    }

    function resetModal() {
        $('.gm_from tbody, .gm_to tbody').html('');
        $('#capsule_qty_one_total').remove();
        $('#capsule_qty_two_total').remove();
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
            const isEmpty2 = gm_to.every(item => item.qty == 0);
            if (isEmpty || isEmpty2){
                if (isEmpty) {
                    $('#warning-label-from').text('No current Inventory for this machine.');
                }
                if (isEmpty2) {
                    $('#warning-label-to').text('No current Inventory for this machine.');
                }
                return;
            }

            $('#label_from_machine span').text(data.from_machine.serial_number);
            $('#label_to_machine span').text(data.to_machine.serial_number);

            gm_from.forEach((ic, index) => {
                // const jan_no_one = $('<input>').attr({
                //     type: 'text',
                //     item_code: ic.item_code, 
                //     machine: data.from_machine.serial_number,
                //     name: 'jan_no_one[]',
                //     value: ic.item_code,
                //     readonly: 'readonly',
                // }).addClass('form-control');
                const gm_item_code = $('<td>').text(ic.item_code);
                // const gm_item_code = $('<td>').append(jan_no_one);
                const gm_description = $('<td>').text(ic.item_description);
                const qtyInput = $('<input>').attr({
                    type: 'text',
                    qty: ic.qty,
                    item_code: ic.item_code, 
                    machine: data.from_machine.serial_number,
                    // placeholder: ic.qty,
                    name: 'capsule_qty_one[]',
                }).addClass('form-control item-qty');
                const gm_qty = $('<td>').append(qtyInput);
                
                const tr = $('<tr>').append(gm_item_code, gm_description, gm_qty);
                $('.gm_from').append(tr);
            });
            $('#from-machine-total').append('<input type="text" class="form-control" name="capsule_qty_one_total[]" id="capsule_qty_one_total" readonly>');
            gm_to.forEach((ic, index) => {
                // const jan_no_two = $('<input>').attr({
                //     type: 'text',
                //     item_code: ic.item_code, 
                //     machine: data.from_machine.serial_number,
                //     name: 'jan_no_two[]',
                //     value: ic.item_code,
                //     readonly: 'readonly',
                // }).addClass('form-control');
                const gm_item_code = $('<td>').text(ic.item_code);
                // const gm_item_code = $('<td>').append(jan_no_two);
                const gm_description = $('<td>').text(ic.item_description);
                const qtyInput = $('<input>')
                    .attr('name','capsule_qty_two[]')
                    .attr('qty', ic.qty)
                    .attr('type', 'text')
                    .attr('item_code', ic.item_code)
                    .addClass('form-control item-qty2');
                const gm_qty = $('<td>').append(qtyInput);
                
                const tr = $('<tr>').append(gm_item_code, gm_description, gm_qty);
                $('.gm_to').append(tr);
            });
            $('#to-machine-total').append('<input type="text" class="form-control" name="capsule_qty_two_total[]" id="capsule_qty_two_total" readonly>');
            $('#addRowModal').modal('show');
        }

    }
    function swapTableSummary(){
        const summaryTable = $('.swap-table-summary').clone();
        summaryTable.find('input').get().forEach(input => {
            const value = $(input).val();
            $(input).parents('td').text(value);
            $(input).remove();

        })
        summaryTable.find('td').css('font-size', '14px')
        Swal.fire({
            title: "Are you sure?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            reverseButtons: true,
            returnFocus: false,
            html: summaryTable,
            width: '900px',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#save-modal, button[data-dismiss="modal"]').attr('disabled', true);
                submitModal();
            }
        });
    }
    function showSwapSuccess(data){
        console.log(data);
        const summaryTable = $('.swap-table-summary').clone();
        summaryTable.prepend(`<label class="label label-success text-center" style="display: inline-block; font-size: 100%; margin-bottom: 18px;">Reference #: ${data.reference_number}</label>`);
        summaryTable.find('input').get().forEach(input => {
            const value = $(input).val();
            $(input).parents('td').text(value);
            $(input).remove();
            
        })
        summaryTable.find('tbody').html('');
        summaryTable.find('#from-machine-total').text('');
        summaryTable.find('#to-machine-total').text('');

        let total = 0;
        let total2 = 0;

        data.machine_one_after.forEach((item) => {
            const tr = $('<tr>');
            const item_code = $('<td>').text(item.item_code);
            const item_description = $('<td>').text(item.item_description);
            const qty = $('<td>').text(item.qty.toLocaleString());

            tr.append(item_code, item_description, qty);
            summaryTable.find('.gm_from tbody').append(tr);

            total += item.qty;
        })

        data.machine_two_after.forEach((item) => {
            const tr = $('<tr>');
            const item_code2 = $('<td>').text(item.item_code);
            const item_description2 = $('<td>').text(item.item_description);
            const qty2 = $('<td>').text(item.qty.toLocaleString());

            tr.append(item_code2, item_description2, qty2);
            summaryTable.find('.gm_to tbody').append(tr);

            total2 += item.qty;
        })

        summaryTable.find('#from-machine-total').text(total.toLocaleString());
        summaryTable.find('#to-machine-total').text(total2.toLocaleString());

        Swal.fire({
            title: "Swapped successfully",
            icon: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok',
            returnFocus: false,
            html: summaryTable,
            width: '900px',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#save-modal, button[data-dismiss="modal"]').attr('disabled', false);
                $('button[data-dismiss="modal"]').eq(0).click();
                $('#from_machine, #to_machine').val('');
                $('#merge-btn').attr('disabled', true);
                location.assign("{{ CRUDBooster::mainPath() }}");
            }
        });
    }

</script>
@endpush