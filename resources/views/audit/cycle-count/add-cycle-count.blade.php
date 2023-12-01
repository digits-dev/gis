@push('head')
    <script src="{{ asset('plugins/sweetalert.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/code-scanner.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/custom.css')}}">
    <style type="text/css">
        .select2-selection__choice {
            font-size: 14px !important;
            color: black !important;
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

        .modal-content {
            -webkit-border-radius: 10px !important;
            -moz-border-radius: 10px !important;
            border-radius: 10px !important;
        }

        .modal-header {
            background-color: #3c8dbc;
            -webkit-border-radius: 10px !important;
            -moz-border-radius: 10px !important;
            border-radius: 10px 10px 0px 0px !important;
            color: #fff;
        }

        #other-detail th,
        td {
            border: 1px solid rgba(000, 0, 0, .5);
            padding: 8px;
        }

        #cycle-count th,
        td {
            border: 1px solid rgba(000, 0, 0, .5);
            padding: 8px;
        }

        .td-style{
            text-align: center;
            vertical-align: middle !important;
        }

        .plus {
            font-size: 20px;
        }

        #add-Row {
            border: none;
            background-color: #fff;
        }

        .iconPlus {
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

        #bodytable tr td{
            text-align: center;
        }

        #bigplus {
            transition: transform 0.5s ease 0s;
        }

        #bigplus:before {
            content: '\FF0B';
            background-color: #3c8dbc: font-size: 50px;
        }

        #bigplus:hover {
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

        @media (min-width:729px) {
            .panel-default {
                width: 70% !important;
                margin: auto !important;
            }
        }
    </style>
@endpush
@extends('crudbooster::admin_template')
@section('content')
    @if (g('return_url'))
        <p class="noprint"><a title='Return' href='{{ g('return_url') }}'><i class='fa fa-chevron-circle-left '></i> &nbsp;
                {{ trans('crudbooster.form_back_to_list', ['module' => CRUDBooster::getCurrentModule()->name]) }}</a></p>
    @else
        <p class="noprint"><a title='Main Module' href='{{ CRUDBooster::mainpath() }}'><i
                    class='fa fa-chevron-circle-left '></i> &nbsp;
                {{ trans('crudbooster.form_back_to_list', ['module' => CRUDBooster::getCurrentModule()->name]) }}</a></p>
    @endif

    <div class='panel panel-default'>
        <div class='panel-heading' style="background-color:#3c8dbc; color:#fff">
            Cycle Count (Capsule) Form
        </div>

        <form action="{{ route('submit-cycle-count-floor') }}" method="POST" id="cycleCount" enctype="multipart/form-data">
            <input type="hidden" value="{{ csrf_token() }}" name="_token" id="token">

            <div class='panel-body'>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><span style="color:red">*</span> Location</label>
                            <select selected data-placeholder="Choose location" id="location_id" name="location_id"
                                class="form-select select2" style="width:100%;">
                                @foreach ($locations as $location)
                                    <option value=""></option>
                                    <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                                @endforeach
                            </select>
                            <span class="label label-danger" id="location_error"></span>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-12">
                        <table class="table table-responsive" id="cycle-count">
                            <tbody id="bodyTable">
                                <tr>
                                    <th width="20%" class="text-center">Machine</th>
                                    <th width="15%" class="text-center">Item Code</th>
                                    <th width="30%" class="text-center">Item Description</th>
                                    <th width="15%" class="text-center">Quantity</th>
                                    <th width="3%" class="text-center"><i class="fa fa-trash"></i></th>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr id="tr-table1" class="bottom">
                                    <td class="text-center">
                                        <button class="red-tooltip" id="add-Row" name="add-Row" title="Add Row">
                                            <div class="iconPlus" id="bigplus"></div>
                                        </button>
                                    </td>
                                    <td colspan="2">
                                    </td>
                                    <td colspan="1">
                                        <input type="text" name="quantity_total" class="form-control text-center"
                                            id="quantity_total" readonly>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div id="addRowModal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">

                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-center"><strong>Cycle Count (Machine)</strong></h4>
                        </div>
                        <div class="row">
                            <div class="modal-body">
                                <div class="col-md-12" style="margin-bottom: 10px">
                                    <div id="reader-wrapper" style="display: none;">
                                        <div class="close-reader">X</div>
                                        <div id="reader"></div>
                                    </div>
                                </div>
                                <div class="col-md-12 machine-form-group">
                                    <div class="form-group" style="display: flex; position: relative">
                                        <input input-for="machine" class="form-control text-center finput" type="text"
                                            placeholder="Scan/Enter Machine" id="gasha_machines_id_inputed" autocomplete="off">
                                        <button btn-for="machine" type="button" class="btn btn-danger btn-sm open-camera" id="gm-camera"
                                            tabindex="-1"><i class="fa fa-camera"></i></button>
                                    </div>
                                </div>

                                {{-- <div class="col-md-6">
                                    <div class="form-group" style="display: flex">
                                        <input input-for="searchitem" class="form-control text-center finput"
                                            type="text" placeholder="Scan/Enter Item Code" id="search_item"
                                            style="width:100%" autocomplete="off" readonly>
                                        <button btn-for="searchitem" type="button"
                                            class="btn btn-danger btn-sm open-camera" id="ic-camera" tabindex="-1" disabled><i
                                                class="fa fa-camera"></i></button>
                                        <div id="item_display_error"></div>
                                    </div>
                                </div> --}}

                                <div class="col-md-12">
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="float: left;">Cancel</button>
                            <button type='button' id="add-cycle-count" class="btn btn-primary" disabled><i
                                    class="fa fa-save"></i> Add</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class='panel-footer'>
                <a href="#" id="cancel-form" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                <button class="btn btn-primary pull-right" type="submit" id="btnSubmit"> <i class="fa fa-save"></i>
                    {{ trans('message.form.new') }}</button>
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
        let machineTokenNo = 0;
        let itemTokenNo = 0;

        $('#location_id').change(function() {
            $(this).attr('disabled', true);

            $.ajax({
                type: 'POST',
                url: "{{ route('check-inventory-qty') }}",
                data: {
                    "location_id": $(this).val()
                },
                success: function(res) {
                    const data = JSON.parse(res);
                    if ($.isEmptyObject(data.capsuleInventory)) {
                        $('#add-Row').attr('disabled', true);
                        $('#location_error').text('No available inventory');
                    } else {
                        $('#location_error').text('');
                        $('#add-Row').attr('disabled', false);
                    }
                    console.log(res);
                }
            });
        });

        async function showCameraOptions(cameras) {
            let cameraOptions = {}
            cameras.forEach(camera => cameraOptions[camera.id] = camera.label);
            const {
                value: camera
            } = await Swal.fire({
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
                    cameraId, {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
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
            if (selectedInput == 'machine') checkMachine(text);
        }

        function checkMachine(machine_code) {
            $.ajax({
                type: 'POST',
                url: "{{ route('get-machine-cycle-count') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    machine_code: machine_code,
                    location_id: $('#location_id').val(),
                },
                success: function(res) {
                    $('.machine-form-group .machine-warning').remove();
                    const data = JSON.parse(res);
                    console.log('heeeey',data);
                    const existingMachines = $('#cycle-count .existing-machines').get().map(e => $(e).attr('machine'));
                    const alreadyExists = existingMachines.includes(machine_code);
                    const isInvalidMachine = $.isEmptyObject(data.machines);
                    if (alreadyExists) {
                        const span = $('<span>').addClass('label label-danger machine-warning').text('Machine already exists in table!').css({
                            position: 'absolute',
                            bottom: '0',
                        });
                        $('.machine-form-group').append(span);

                    } else if (!machine_code) {
                        $('.machine-form-group .machine-warning').remove();
                    } else if (!data.items.length) {
                        Swal.fire({
                            title: "Oops.",
                            html: 'No current inventory for this machine!',
                            icon: 'error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok',
                            returnFocus: false,
                        });
                    } else if (!isInvalidMachine) {
                        $('.machine-form-group .machine-warning').remove();
                        data.items.forEach(item => {
                            populateTable({
                                item,
                                machine: data.machine,
                            });
                        });
                        $('#search_item').attr('readonly', true);
                        $('#ic-camera').attr('disabled', true);
                        $('#gasha_machines_id_inputed').attr('readonly', true);
                        $('#gm-camera').attr('disabled', true);
                    } else {
                        const span = $('<span>').addClass('label label-danger machine-warning').text('Machine not Found!').css({
                            position: 'absolute',
                            bottom: '0',
                        });
                        $('.machine-form-group').append(span);
                    }

                    $('#ic-camera').attr('disabled', isInvalidMachine || alreadyExists);
                    $('#search_item').attr('readonly', isInvalidMachine || alreadyExists);

                },
                error: function(err) {
                    Swal.fire({
                        title: "Oops.",
                        html: 'Something went wrong!',
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
            $(this).val($(this).val().trim().toUpperCase());
        });

        $('#gasha_machines_id_inputed').on('keypress', function(event) {
            if (event.keyCode == 13) {
                checkMachine($(this).val());
            }
        })

        //Add Row
        $('#add-Row').click(function(e) {
            e.preventDefault();
            if ($('#location_id').val() === '') {
                swal({
                    type: 'error',
                    title: 'Please select location first!',
                    icon: 'error',
                    confirmButtonColor: '#3c8dbc',
                });
            } else {
                $("#addRowModal").modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        });

        //Add Row
        $('#add-cycle-count').on('click', function(event) {
            event.preventDefault();
            const allItemQty = $('table tbody .itemQty').get();
            const isValid = allItemQty.every(e => $(e).val() && Number($(e).val().replace(/\D/g, '')) <= Number($(e).attr('max-qty')));
            if (!isValid) {
                $(this).attr('disabled', true);
                return;
            }
            const data = {
                items: []
            };
            const rows = $('#newItemModalTable').find('tbody').find('tr').get();
            rows.forEach(row=> {
                const input = $(row).find('.item-data');
                const machine = input.attr('machine');
                data.machine = machine;
                const item_obj = {
                    item_description: input.attr('item-description'),
                    machine,
                    qty: input.val(),
                    item_code : input.attr('item-code'),
                }
                data.items.push(item_obj);
            });

            populateOutsideTable(data);
            $('.modal-header .close').click();
            resetModal();
            $('#quantity_total').val(calculateTotalQuantity());
        });

        function resetModal() {
            $('#newItemModalTable tbody').html('');
            $('#gasha_machines_id_inputed').attr('readonly', false);
            $('#gm-camera').attr('disabled', false)
            $('#ic-camera').attr('disabled', true)
            $('#add-cycle-count').attr('disabled', true);
            $('#search_item').attr('readonly', true);
            $('#total-item-qty').text('0');
        }

        $(document).on('click', 'button[data-dismiss="modal"]', function() {
            resetModal();
        });

        $('#addRowModal').on('shown.bs.modal', function() {
            $('#gasha_machines_id_inputed').focus();
        });

        $('#addRowModal').on('hidden.bs.modal', function(e) {
            $(this)
                .find("input")
                .val('')
                .end();
        });

        $(document).on('keyup', '.qty', function(e) {
            if (event.which >= 37 && event.which <= 40) return;

            if (this.value.charAt(0) == '.') {
                this.value = this.value.replace(/\.(.*?)(\.+)/, function(match, g1, g2) {
                    return '.' + g1;
                })
            }

            if (this.value.split('.').length > 2) {
                this.value = this.value.replace(/([\d,]+)([\.]+.+)/, '$1') +
                    '.' + this.value.replace(/([\d,]+)([\.]+.+)/, '$2').replace(/\./g, '')
                return;
            }

            $(this).val(function(index, value) {
                value = value.replace(/[^0-9.]+/g, '')
                let parts = value.toString().split(".");
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return parts.join(".");
            });

            $(this).val(function(index, value) {
                return value
                    .replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            });

            $('#quantity_total').val(calculateTotalQuantity());
        });

        function validateModal() {
            const allItemQty = $('table tbody .itemQty').get();
            const isValid = allItemQty.every(e => $(e).val() && Number($(e).val().replace(/\D/g, '')) <= Number($(e).attr('max-qty')));
            $('#search_item').attr('readonly', !isValid);
            $('#add-cycle-count, #ic-camera').attr('disabled', !isValid);
        }

        $(document).on('input', '#search_item', function(){

            $(this).val($(this).val().trim());
        });

        $(document).on('keypress', '#search_item', function(event) {
            if (event.which >= 37 && event.which <= 40) return;
            if (event.which == 13) {
                if (!$('#search_item').val()) return;
                //disable machine
                $('#gasha_machines_id_inputed').attr('readonly', true);
                $('#gm-camera').attr('disabled', true);

                const gm = $('#gasha_machines_id_inputed').val();
                const item_code = $(this).val();

                $.ajax({

                    url: "{{ route('check-item-code') }}",
                    type: "POST",
                    data: {
                        gm,
                        item_code,
                        _token: $('#token').val(),
                    },
                    success: function(res){
                        // console.log(JSON.parse(res));
                        const data = JSON.parse(res);
                        console.log(data);return;

                        if(data.missing){
                            Swal.fire({
                                title: "Oops.",
                                html:  'Item code not found!',
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok',
                                returnFocus: false,
                                reverseButtons: true,
                                allowOutsideClick: false,
                            });
                        }else if(data.mismatch_token){
                            Swal.fire({
                                title: "Oops.",
                                html:  'No. of tokens mismatch!',
                                icon: 'warning  ',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok',
                                returnFocus: false,
                                reverseButtons: true,
                                allowOutsideClick: false,
                            }).then(function(){
                                populateTable(data);
                            });
                        }
                        else{
                            populateTable(data);
                        }
                        validateModal();
                    },
                    error: function(err){
                        console.log(err);
                    }
                })

            }
        });

        $(document).on('keyup', '.item-data', function(e) {
            const withoutLeadingZero = Number($(this).val().replace(/\D/g, ''));
            $(this).val($(this).val() ? withoutLeadingZero : '');

            if (e.which >= 37 && e.which <= 40) return;

            if (this.value.charAt(0) == '.') {
                this.value = this.value.replace(/\.(.*?)(\.+)/, function(match, g1, g2) {
                    return '.' + g1;
                })
            }

            if (this.value.split('.').length > 2) {
                this.value = this.value.replace(/([\d,]+)([\.]+.+)/, '$1') +
                    '.' + this.value.replace(/([\d,]+)([\.]+.+)/, '$2').replace(/\./g, '')
                return;
            }

            $(this).val(function(index, value) {
                value = value.replace(/[^0-9.]+/g, '')
                let parts = value.toString().split(".");
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return parts.join(".");
            });

            $(this).val(function(index, value) {
                return value
                    .replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            });

            const max = Number($(this).attr('max-qty'));
            const val = Number($(this).val().replace(/\D/g, ''));
            if (val > max) {
                $(this).css('border', '2px solid red');
            } else {
                $(this).css('border', '');
            }

            $('#total-item-qty').text(calculateItemQuantity());
            validateModal();
        });

        $(document).ready(function() {
            $('#btnSubmit').click(function(event) {
                event.preventDefault();
                var rowCount = $('#cycle-count tr').length - 2;
                // console.log(rowCount);
                if (rowCount === 0) {
                    swal({
                        type: 'error',
                        title: 'Please add an item!',
                        icon: 'error',
                        confirmButtonColor: "#3c8dbc",
                    });
                    event.preventDefault();
                    return false;
                } else if ($('#location_id').val() === '') {
                    swal({
                        type: 'error',
                        title: 'Location cannot be empty!',
                        icon: 'error',
                        confirmButtonColor: '#3c8dbc',
                    });
                    event.preventDefault();
                    return false;
                }
                let gasha_machines_id = $('.gasha_machine').length;
                let gasha_machines_id_value = $('.gasha_machine').find(':selected');;
                for (i = 0; i < gasha_machines_id; i++) {
                    if (gasha_machines_id_value.eq(i).val() == 0 || gasha_machines_id_value.eq(i).val() ==
                        null) {
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

                let qty = $('input[name^="qty[]"]').length;
                let qty_value = $('input[name^="qty[]"]');
                for (i = 0; i < qty; i++) {
                    if (qty_value.eq(i).val() == 0 || qty_value.eq(i).val() == null) {
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
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#location_id').attr('disabled', false);
                        $('#cycleCount').submit();
                    }
                });

            });
        });



        // fix for bug when pressing enter key removing lines on outside table
        $(document).on('keypress', 'input', function(event) {
            if (event.key == 'Enter') {
                event.preventDefault();
            }
        });

        function calculateTotalQuantity() {
            let totalQuantity = 0;
            $('.qty').each(function() {
                let qty = 0;
                if (!($(this).val() === '')) {
                    qty = parseInt($(this).val().replace(/,/g, ''));
                }

                totalQuantity += qty;
            });
            return totalQuantity;
        }

        function calculateItemQuantity() {
            let itemQty = 0;
            $('table tbody .item-data').each(function() {
                let qty = 0;
                if (!($(this).val() === '')) {
                    qty = parseInt($(this).val().replace(/,/g, ''));
                }

                itemQty += qty;
            });
            return itemQty.toLocaleString();
        }

        function populateTable(data) {

            let searchedItem = data.item.digits_code;
            const existingItems = $('#newItemModalTable tbody .existing-item-code').get().map(e => $(e).text());
            console.log(existingItems);

            if(!existingItems.includes(searchedItem)){
                let newItem = `
                <tr>
                    <td class='text-center existing-item-code'>${searchedItem}</td>
                    <td><input item-description="${data.item.item_description}" machine='${data.machine.serial_number}' item-code='${searchedItem}' max-qty='${data.item.qty}' class='form-control text-center finput itemQty item-data' type='text' placeholder='Qty' name='item_qty[]' style='width:100%' autocomplete='off'</td>
                </tr>`;

                $(newItem).prependTo($('#newItemModalTable tbody'));

                //reset search and focus qty
                $('#search_item').val('').attr('readonly', true);
                $(`table tbody .itemQty[item-code="${searchedItem}"]`).focus();
                $('#ic-camera').attr('disabled', true);
            }
            else{
                $('#search_item').val('');
                swal({
                    type: 'error',
                    title: 'Item already added! Please try again with different Item Code.',
                    icon: 'error',
                    confirmButtonColor: "#3c8dbc",
                });
                event.preventDefault();
                return false;
            }
        }

        $(document).on('click', '#deleteRow', function() {
            const machine = $(this).attr('machine');
            $(`tr[machine="${machine}"]`).remove();
            $('#quantity_total').val(calculateTotalQuantity());
        });

        function populateOutsideTable(data){

            data.items.forEach((item, index) => {
                let rowspan = data.items.length;
                const newrow =`
                    <tr class="item-row existing-machines" style="background-color: #d4edda; color:#155724" machine="${data.machine}">
                        ${index == 0 ? `
                            <td rowspan="${rowspan}" class="td-style">
                                ${data.machine}
                                <input type="hidden" name="machine_code[]" value="${item.machine}">
                            </td> ` : ''
                        }

                        <td class="td-style">${item.item_code}
                            <input type="hidden" name="item_code[${item.machine}][]" value="${item.item_code}">
                        </td>

                        <td class="td-style existing-item-description">${item.item_description}</td>

                        <td class="td-style">
                            <input machine="${data.machine}" item="${data.item_code}" description="${item.item_description}" class="form-control text-center finput qty item-details" type="text" name="qty[${item.machine}][]" style="width:100%" value="${item.qty}" autocomplete="off" required readonly>
                        </td>

                        ${index == 0 ? `
                            <td rowspan=${rowspan} class="td-style">
                                <button id="deleteRow" machine="${data.machine}" class="btn btn-danger btn-sm removeRow">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>
                            </td>` : ''
                        }

                    </tr>

                `;

                $('#cycle-count tbody').append(newrow);
            });
        }

        $(document).on('submit', 'form#cycle-count', function(vent) {
            const itemsToBeSubmitted = [];
            const items = $(this).find('.item-details').get();
            items.forEach(item => {
                item = $(item);
                itemsToBeSubmitted.push({
                    item_code: item.attr('item_code'),
                    machine: item.attr('machine'),
                    description: item.attr('description'),
                    qty: item.val(),
                });
            });
        });

        $('#cancel-form').on('click', function() {
            Swal.fire({
                title: "Are you sure you want to cancel?",
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#dd3333',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
                showCancelButton: true,
                returnFocus: false,
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    location.assign("{{ CRUDBooster::mainPath() }}");
                }
            });
        });
    </script>
@endpush
