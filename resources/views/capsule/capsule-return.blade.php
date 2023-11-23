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
                <label>CAPSULE RETURN</label>
            </div>
            <div class='panel-body'>
            <div id="reader-wrapper" style="display: none;">
                <div class="close-reader">×</div>
                <div id="reader"></div>
            </div>
            <form method='post' action='{{CRUDBooster::mainpath('add-save')}}'>
                @csrf
                <div class='form-group'>
                <p>From Gasha Machine</p>
                <div class="flex input-btn">
                    <input input-for="machine" type='text' name='gasha_machine' id="gasha_machine" required class='form-control'/>
                    <button btn-for="machine" type="button" class="btn btn-primary open-camera"><i class="fa fa-camera"></i></button>
                </div>
                <p>To Stockroom</p>
                <div class="flex input-btn">
                    <input input-for="stockroom" type='text'  required class='form-control' value="{{ $stockroom->location_name }}" readonly style="max-width: 450px;"/>
                    <input class="hide" input-for="stockroom" type='text' name='stock_room' required class='form-control' value="{{ $user_location_id->location_id }}" readonly style="width: 300.6px"/>
                </div>
                {{-- <label>Quantity</label>
                <input type='text' name='qty' required class='form-control qty_input' id="quantity"/> --}}
                </div>
                <div class='panel-img'>
                    <img src="{{ asset('img/cap-return.png') }}">
                </div>
                <button class="hide" type="submit" id="real-submit-btn"></button> 
            </form>
            </div>
            <div class='panel-footer'>
            </div>
        </div>
    </div>
    <div class="hide">
        <div class="return_quantity_content">
            <label id="gm_serial_number"></label>
            <br>
            <br>
        </div>
    </div>
@endsection
@push('bottom')
<script>

    $('.content-header').hide();
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

    $(document).on('input', '.qty_input', function(event) {
        const inputs = $('.qty_input').get();
        const total = inputs.reduce((a, b) => {
            return a += Number($(b).val().replace(/\D/g, ''));
        }, 0);

        const isValid = inputs.reduce((a, e) => {
            Number($(e).val()) <= Number($(e).attr('max-value'));
            const maxAmount = Number($(e).attr('max-value').replace(/\D/g, ''));
            const currentValue = Number($(e).val().replace(/\D/g, ''));
            
            const isCorrect = maxAmount >= currentValue;
            if (!isCorrect) {
                $(e).css('border', '2px solid red');
            } else {
                $(e).css('border', '');
            }
            return a && isCorrect;
        }, true)

        if (!isValid || inputs.some(e => $(e).val().trim() === '')) {
            $('#save-btn').prop('disabled', true);
        }else{
            $('#save-btn').prop('disabled', false);
        }

        $('#total_quantity').val(total.toLocaleString());
    });

    $(document).on('click', '#save-btn', function(){
        $('.swal-clone').removeClass('hide');
    });

    $(document).on('click', '.swal-btn-save', function(){
        $('.swal-clone').addClass('hide');
        $('#save_gm').click();
    });

    $(document).on('click', '.swal-btn-cancel', function(){
        $('.swal-clone').addClass('hide');
    });

    $(document).on('click', '.swal2-close', function(){
        $('.swal-clone').addClass('hide');
    });

    $(document).on('click', '#cancel_gm', function(){
        $('.swal2-close').trigger('click');
    });

    $(document).on('submit', '#swal_form', function(){
        event.preventDefault();

        const formData = $('form').serialize();
        $.ajax({
            type: 'POST',
            url: "{{ route('submit_capsule_return') }}",
            data: formData,
            success: function(res) {
                console.log(res);
                Swal.fire({
                    title: 'Capsule successfully returned.',
                    icon: 'success',
                    html: `<strong>Ref #: ${res.reference_number} </strong>`,
                    returnFocus: false,
                }).then(() => {
                    $('#gasha_machine, #quantity').val('');
                });
            },
            error: function(err) {
                console.log(err);
            }
        });
    })

    $(document).ready(function () {

        $('#save-btn').attr('disabled', true)
        
        const inputElement = $('#gasha_machine, #quantity'); 

        let timeout;

        inputElement.on('input', function () {

            $('#gm_serial_number').text('Machine: ' + $('#gasha_machine').val());

            clearTimeout(timeout); 

            timeout = setTimeout(function () {
                
                const formData = $('form').serialize();

                $.ajax({
                    type: 'POST',
                    url: "{{ route('validate_gasha_machine') }}",
                    data: formData,
                    success: function(res) {
                        
                        if(validateGashaMachines(res)){
                            
                            let ics = Object.values(res.list_of_ic);
                            const gm = Object.values(res.list_of_gm);
                            const div = $('<div>').addClass('swal-inputs');
                            const label = $('<input type="text" readonly>').attr('class','label-input');
                            const input = $('<input type="text" required>');

                            let swal_content = $('.return_quantity_content').prop('outerHTML');
                            let additionalContent = $(`
                                <form type="POST" id="swal_form">
                                    <input class="hidden" value="{{ csrf_token() }}">
                                    <div class="swal-inputs">
                                        <label>Jan #</label>
                                        <label>QTY</label>
                                    </div>
                                </form>
                            `);
                            let swal_btn = $(`
                                <div class="swal-inputs">
                                    <label class="total_quantity">Total Quantity</label>
                                    <input readonly class="bg-color" id="total_quantity">
                                </div>
                                <div>
                                    <br>
                                    <button type='submit' class="hide" id="save_gm" type="button">Submit</button>
                                    <button id="cancel_gm" type="button">Cancel</button>
                                    <button type='button' class='save_gm' id="save-btn" value='Save' disabled>Submit</button>
                                </div>
                            `);
                            if(ics.length){
                                ics.forEach((ic, index) => {
                                    const ic_id = ic.id;
                                    const line = gm.find(e => e.inventory_capsules_id === ic_id);
                                    const maxAmount = line.qty;
                                    const clonedDiv = div.clone();
                                    const clonedLabel = label.clone();
                                    const clonedInput = input.clone().attr('class', 'qty_input');
                                    clonedLabel.attr({'value':ic.digits_code, 'disabled':true});
                                    clonedInput.attr({'name':'qty_'+ic.item_code, 'max-value': maxAmount});
                                    clonedDiv.append(clonedLabel, clonedInput);
                                    additionalContent.append(clonedDiv);
                                })
                            }else{
                                const no_item_found = $(`
                                <div class="danger">No items found in the machine</div>
                                `);
                                additionalContent.append(no_item_found);
                                $(swal_btn).find('button').hide();
                                
                            }

                            additionalContent.append(swal_btn);

                            swal_content += additionalContent.prop('outerHTML');
                            
                            Swal.fire({
                                showConfirmButton: false,
                                showCloseButton: true,
                                allowOutsideClick: false,
                                returnFocus: false,
                                html: swal_content,
                            })
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });

            }, 500);
        });

        function validateGashaMachines(data){
            console.log(data);
            if(data.not_exist && ($('#gasha_machine').val() != '' )){
                Swal.fire({
                    title: `Gasha Machine not existing.`,
                    icon: 'error',
                    returnFocus: false,
                });
                $('#save-btn').attr('disabled', true)
            }else if(data.qty){
                Swal.fire({
                    title: `Insufficient qty in gasha machine.`,
                    icon: 'error',
                    returnFocus: false,
                });
                $('#save-btn').attr('disabled', true)

            }else{
                $('#save-btn').attr('disabled', false)

                if($('#gasha_machine').val() != '' ){
                    return true;
                }
            }
        }

        function inputIsNumber(){
            $(document).on('keyup','.qty_input,#total_quantity', function(event) {
                if(event.which >= 37 && event.which <= 40) return;

                if(this.value.charAt(0) == '.'){
                    this.value = this.value.replace(/\.(.*?)(\.+)/, function(match, g1, g2){
                        return '.' + g1;
                    })
                }

                // if(event.key == '.' && this.value.split('.').length > 2){
                if(this.value.split('.').length > 2){
                    this.value = this.value.replace(/([\d,]+)([\.]+.+)/, '$1') 
                        + '.' + this.value.replace(/([\d,]+)([\.]+.+)/, '$2').replace(/\./g,'')
                    return;
                }

                $(this).val( function(index, value) {
                    value = Number(value.replace(/[^0-9.]+/g,''))
                    let parts = value.toString().split(".");
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    return parts.join(".");
                });
            })

            $(document).on('keyup','.qty_input,#total_quantity', function(event) {

                if(event.which >= 37 && event.which <= 40) return;
                $(this).val(function(index, value) {
                    return value
                    .replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                    ;
                });
            })
        }

        inputIsNumber()

    });

</script>
@endpush