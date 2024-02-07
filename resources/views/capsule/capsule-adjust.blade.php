@extends('crudbooster::admin_template')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/capsule-adjustment.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="{{ asset('plugins/sweetalert.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/select2-custom.css') }}">
    <style>
        input.dark:read-only {
            background-color: #ecf0f5;
            border: 1px solid #999;
            border-radius: 3px;
        }
    </style>
@endpush
@section('content')

<div class="table_summary styled-table-swap" style="display:none;">
    <table>
        <tbody>
            <tr>
                <td>Location</td>
                <td class="location-cell"></td>
            </tr>
            <tr>
                <td>Action</td>
                <td class="action-cell"></td>
            </tr>
            <tr>
                <td>Current Capsule Quantity</td>
                <td class="current-capsule-amount-cell"></td>
            </tr>
            <tr>
                <td>Adjustment Quantity</td>
                <td class="adjusment-amount-cell"></td>
            </tr>
            <tr>
                <td class="new-capsule-cell">New Capsule Quantity</td>
                <td class="new-capsule-amount-cell"></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="panel-content">
    <div class='panel panel-default'>
        <form action="{{ route('submitCapsuleAmount') }}" method="POST" autocomplete="off">
            @csrf
            <input type="text" name="action" hidden>
            <div class='panel-header'>
                <label>ADJUST CAPSULE BALANCE</label>
            </div>
            <div class='panel-body'>
                <div class="select_store_div">
                    <label><span style="color:red">*</span>Select Store Location:</label>
                    <select class="js-example-basic-single s-single" name="locations_id" id="select_store_location" >
                        <option selected disabled>None Selected</option>
                        @foreach ( $locations as $location )
                            <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="jan_no_div select_store_div" style="display:none;">
                    <label><span style="color:red">*</span>Select Jan#</label>
                    <select selected data-placeholder="Select Jan#" class="js-example-basic-single s-single" name="jan_no" id="jan_no" >
                  
                    </select>
                </div>

                <div class="machine_div select_store_div" style="display:none;">
                    <label><span style="color:red">*</span>Select Machine/Stockroom</label>
                    <select selected data-placeholder="Select machine/stockroom" class="js-example-basic-single s-single" name="machine" id="machine" >
                       
                    </select>
                </div>
                <input type="hidden" name="type" id="type">
                <div class="display_qty_div">
                    <label>Current Capsule Quantity:</label>
                    <input class="form-control text-center dark" type="text" id ="current_capsule_amount" readonly>
                </div>
                
                <div class="add_deduct_div select_store_div" style="display:none;">
                    <label><span style="color:red">*</span>Action</label>
                    <select class="js-example-basic-single s-single" id="action_type" >
                        <option value="" disabled selected>None Selected</option>
                        <option value="Add">Add</option>
                        <option value="Deduct">Deduct</option>
                    </select>
                </div>

                 {{-- <div class="add_deduct_div" style="display: none">
                    <a class="action_btn add_token_btn" id="add_token_btn">Add Token</a>
                    <a class="action_btn deduct_token_btn" id="deduct_token_btn">Deduct Token</a>
                </div> --}}
                <div class="add_capsule_div" style="display: none" >
                    <hr style="border: 1px solid #5b89a3">
                    <div class="input_qty_div">
                        <label><span style="color:red">*</span>Add Capsule Quantity:</label>
                        <input type="text" name="adjustment_qty_add" id="add_capsule_input" _action="add" oninput="formatNumber(this)">
                    </div>
                    <div class="display_qty_div">
                        <label>New Capsule Quantity:</label>
                        <input class="form-control text-center dark" type="text" id="new_capsule_add" readonly>
                    </div>
                    <div class="reason_input_div">
                        <label><span style="color:red">*</span>Memo:</label>
                        <textarea name="reason_add" id="textarea_add" cols="50" rows="1" required></textarea>
                    </div>
                </div>
                <div class="deduct_capsule_div" style="display: none" >
                    <hr style="border: 1px solid #5b89a3">
                    <div class="input_qty_div">
                        <label>Deduct Capsule Quantity:</label>
                        <input type="text" name="adjustment_qty_deduct" id="deduct_capsule_input" _action="deduct" oninput="formatNumber(this)" >
                    </div>
                    <div class="display_qty_div">
                        <label>New Capsule Quantity:</label>
                        <input class="form-control dark" type="text" id="new_capsule_deduct" readonly>
                    </div>
                    <div class="reason_input_div">
                        <label><span style="color:red">*</span>Memo:</label>
                        <textarea name="reason_deduct" id="textarea_deduct" cols="50" rows="1" required></textarea>
                    </div>
                </div>
            </div>
            <div class='panel-footer' style="display: none" >
                <button type="submit" id="real-submit-btn" style="display: none;">Real submit</button>
                <button class="capsule_adj_save" id="save-btn" type="button" disabled>Save</button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('bottom')
<script>
    $('.s-single').select2({
        width: '100%'
    });

    function preventBack() {
        window.history.forward();
    }
     window.onunload = function() {
        null;
    };
    setTimeout("preventBack()",0);
    
    $(document).ready(function(){
        $('#select_store_location').on('change',function() {
            const selectedValue = $(this).val();
            $(this).attr('disabled', true);
            $.ajax({
                url:"{{ route('getJanCode')}}",
                type:"POST",
                dataType:'json',
                data:{
                    _token:"{{csrf_token()}}",
                    location_id: selectedValue,
                },
                success:function(res){
                    console.log(res);
                    populateDivs(res);
                },
                error:function(res){
                    alert('Failed')
                },
            });
        });

        //JAN # SELECT
        $('#jan_no').on('change',function() {
            const selectedValue = $(this).val();
            const selectedLocationValue = $('#select_store_location').val();
            //$(this).attr('disabled', true);
            $.ajax({
                url:"{{ route('getMachines')}}",
                type:"POST",
                dataType:'json',
                data:{
                    _token:"{{csrf_token()}}",
                    id: selectedValue,
                    location_id: selectedLocationValue
                },
                success:function(res){
                    console.log(res);
                    const result = res.items;
       
                    var i;
                    var showData = [];
                    showData[0] = "<option value=''></option>";
                    for (i = 0; i < result.length; ++i) {
                        var j = i + 1;
                        showData[j] = "<option value='"+result[i].item_id+"'>"+result[i].description+"</option>";
                    }
                    $('#machine').html(showData); 
                },
                error:function(res){
                    alert('Failed')
                },
            });
        });

        //MACHINE SELECT
        $('#machine').on('change',function() {
            const janCodeId = $('#jan_no').val();
            const selectedValue = $(this).val();
            const type = $('#machine option:selected').text();
  
            $('#action_type').val('').trigger('change');  
            //$(this).attr('disabled', true);
            $.ajax({
                url:"{{ route('getMachinesQty')}}",
                type:"POST",
                dataType:'json',
                data:{
                    _token:"{{csrf_token()}}",
                    id: selectedValue,
                    inv_id: janCodeId,
                    type: type
                },
                success:function(res){
                    populateQty(res);
                },
                error:function(res){
                    alert('Failed')
                },
            });
        });

        $('#action_type').on('change', function(){
            if($(this).val() == 'Add'){
                $('input[name="action"]').val('add');
                $('#textarea_deduct').attr('required', false);
                $('#textarea_add').attr('required', true);
                $(".deduct_capsule_div").fadeOut({
                    done: function() {
                        $(".add_capsule_div").fadeIn();
                        $(".panel-footer").show();
                    }
                });
            }else{
                $('input[name="action"]').val('deduct');
                $('#textarea_add').attr('required', false);
                $('#textarea_deduct').attr('required', true);
                $(".add_capsule_div").fadeOut({
                    done: function() {
                        $(".deduct_capsule_div").fadeIn();
                        $(".panel-footer").show();
                    }
                });
            }
        })

        $(".add_token_btn").click(function(e){
            $('input[name="action"]').val('add');
            $('#textarea_deduct').attr('required', false);
            $('#textarea_add').attr('required', true);
            $(".deduct_capsule_div").fadeOut({
                done: function() {
                    $(".add_capsule_div").fadeIn();
                    $(".panel-footer").show();
                }
            });
        });

        $(".deduct_token_btn").click(function(e){
            $('input[name="action"]').val('deduct');
            $('#textarea_add').attr('required', false);
            $('#textarea_deduct').attr('required', true);
            $(".add_capsule_div").fadeOut({
                done: function() {
                    $(".deduct_capsule_div").fadeIn();
                    $(".panel-footer").show();
                }
            });
        });
    });

    function populateQty(res){
        $(".add_deduct_div").fadeOut({
            done: function() {
                console.log(res.qty)
                const locationQty = res.qty;
                // Format locationQty with commas
                $('#current_capsule_amount').val(locationQty ? locationQty.toLocaleString() : 0);
                $('#new_capsule_add, #new_capsule_deduct').val(locationQty ? locationQty.toLocaleString() : 0);
            }
        });
        
        $('.add_deduct_div').slideDown();
        $('.add_capsule_div').slideUp();
        $('.deduct_capsule_div').slideUp();
        $('.panel-footer').slideUp();
        $('.action_btn').removeClass('active_button');
    }

    function populateDivs(res){
        const result = res.items;
        var i;
        var showData = [];
        showData[0] = "<option value=''></option>";
        for (i = 0; i < result.length; ++i) {
            var j = i + 1;
            showData[j] = "<option value='"+result[i].inv_id+"'>"+result[i].digits_code+"</option>";
        }
        $('#jan_no').html(showData); 
        
        $('.jan_no_div').slideDown();
        $('.machine_div').slideDown();
    }

    $('#add_capsule_input, #deduct_capsule_input').on('input', function(e) {
        const action = $(this).attr('_action');
        const currentTokenAmount = $('#current_capsule_amount').val().replace(/\D/g, '');
        const value = $(this).val().replace(/\D/g, '');
        let newValue = 0;
        if (action == 'add') {
            newValue = Number(currentTokenAmount) + Number(value);
        } else {
            newValue = Number(currentTokenAmount) - Number(value);
        }
        if (newValue < 0 || !value) {
            $('#save-btn').attr('disabled', true);
        } else {
            $('#save-btn').attr('disabled', false);
        }
        $(`#new_capsule_${action}`).val(newValue.toLocaleString());
    })

    $('.action_btn').on('click', function() {
        $('.action_btn').removeClass('active_button');
        $(this).addClass('active_button');
        clearInput();
    });

    function clearInput() { 
        $('#add_capsule_input, #deduct_capsule_input, #textarea_add, #textarea_deduct').val('');
        const currentTokenAmount = $('#current_capsule_amount').val();
        $('#new_capsule_add, #new_capsule_deduct').val(currentTokenAmount);
    }

    $('#save-btn').on('click', function() {
        const selectedLocationValue = $('#select_store_location').val();
        const janCodeId = $('#jan_no').val();
        const selectedValue = $('#machine').val();
        const type = $('#machine option:selected').text();
        const action = $('input[name="action"]').val();
        let value = 0;
        if (action == 'add') {
            value = $('#add_capsule_input').val().replace(/\D/g, '');
        } else {
            value = $('#deduct_capsule_input').val().replace(/\D/g, '');
        }

        $.ajax({
            url: "{{ route('getCapsuleInventory') }}",
            type:"POST",
            dataType:'json',
            data:{
                _token:"{{csrf_token()}}",
                capsule_id: selectedValue,
                location_id: selectedLocationValue,
                janCodeId: janCodeId,
                type: type,
                action,
                value,
            },
            success:function(res){
                console.log(res);
                const clonedTable = $('.table_summary').clone().show();
                clonedTable.find('.location-cell').text(res.location_name);
                clonedTable.find('.action-cell').text(res.action.toUpperCase());
                clonedTable.find('.current-capsule-amount-cell').text(res.qty.toLocaleString());
                clonedTable.find('.adjusment-amount-cell').text(res.adjustment_qty.toLocaleString());
                clonedTable.find('.new-capsule-amount-cell').text(res.new_qty.toLocaleString());
                $('#type').val(res.type);
                const outerHTML = clonedTable.prop('outerHTML');
                Swal.fire({
                    title: "Are Sure You Want To Submit?",
                    html: outerHTML,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Submit',
                    returnFocus: false,
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#select_store_location').attr('disabled', false);
                        $('#real-submit-btn').click();
                    }
                });
            },
            error:function(res){
                alert('Failed')
            },
        })

    })

    $('input').on('keypress', function(e) {
        if (e.keyCode === 13) {
            e.preventDefault();
            if (!$('#save-btn').attr('disabled')) {
                $('#save-btn').click();
            }
        }
    })

    function formatNumber(input) {
        // Remove non-numeric characters and leading zeros
        input.value = input.value.replace(/\D/g, '').replace(/^0+/, '');
        // Add commas for every three digits
        input.value = input.value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    
</script>
@endpush