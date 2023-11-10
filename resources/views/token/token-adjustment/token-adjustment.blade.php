@extends('crudbooster::admin_template')
@push('head')
    <link rel="stylesheet" href="{{ asset('css/token-adjustment.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="{{ asset('plugins/sweetalert.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/select2-custom.css') }}">
    <style>

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
                <td>Current Token Amount</td>
                <td class="current-token-amount-cell"></td>
            </tr>
            <tr>
                <td>Adjustment Amount</td>
                <td class="adjusment-amount-cell"></td>
            </tr>
            <tr>
                <td class="new-token-cell">New Token Amount</td>
                <td class="new-token-amount-cell"></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="panel-content">
    <div class='panel panel-default'>
        <form action="{{ route('submitAmount') }}" method="POST" autocomplete="off">
            @csrf
            <input type="text" name="action" hidden>
            <div class='panel-header'>
                <label>ADJUST TOKEN BALANCE</label>
            </div>
            <div class='panel-body'>
                <div class="select_store_div">
                    <label>Select Store Location:</label>
                    <select class="js-example-basic-single s-single" name="locations_id" id="select_store_location" >
                        <option selected disabled>None Selected</option>
                        @foreach ( $locations as $location )
                            <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="display_qty_div">
                    <label>Current Token Amount:</label>
                    <input type="text" id ="current_token_amount" readonly>
                </div>
                <div class="add_deduct_div" style="display: none">
                    <a class="action_btn add_token_btn" id="add_token_btn">Add Token</a>
                    <a class="action_btn deduct_token_btn" id="deduct_token_btn">Deduct Token</a>
                </div>
                <div class="add_token_div" style="display: none" >
                    <hr style="border: 1px solid #5b89a3">
                    <div class="input_qty_div">
                        <label>Add Token Amount:</label>
                        <input type="text" name="adjustment_qty_add" id="add_token_input" _action="add" oninput="formatNumber(this)">
                    </div>
                    <div class="display_qty_div">
                        <label>New Token Amount:</label>
                        <input type="text" id="new_token_add" readonly>
                    </div>
                    <div class="reason_input_div">
                        <label>Reason:</label>
                        <textarea name="reason_add" id="textarea_add" cols="50" rows="10"></textarea>
                    </div>
                </div>
                <div class="deduct_token_div" style="display: none" >
                    <hr style="border: 1px solid #5b89a3">
                    <div class="input_qty_div">
                        <label>Deduct Token Amount:</label>
                        <input type="text" name="adjustment_qty_deduct" id="deduct_token_input" _action="deduct" oninput="formatNumber(this)" >
                    </div>
                    <div class="display_qty_div">
                        <label>New Token Amount:</label>
                        <input type="text" id="new_token_deduct" readonly>
                    </div>
                    <div class="reason_input_div">
                        <label>Reason:</label>
                        <textarea name="reason_deduct" id="textarea_deduct" cols="50" rows="10"></textarea>
                    </div>
                </div>
            </div>
            <div class='panel-footer' style="display: none" >
                <button type="submit" id="real-submit-btn" style="display: none;">Real submit</button>
                <button class="token_adj_save" id="save-btn" type="button" disabled>Save</button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('bottom')
<script>
    $('#select_store_location').select2();

    $(document).ready(function(){
        $('#select_store_location').on('change',function() {
            const selectedValue = $(this).val();
            console.log(selectedValue);
            $.ajax({
                url:"{{ route('viewAmount')}}",
                type:"POST",
                dataType:'json',
                data:{
                    _token:"{{csrf_token()}}",
                    location_id: selectedValue,
                },
                success:function(res){
                    // console.log(res);
                    populateAmount(res);
                },
                error:function(res){
                    alert('Failed')
                },
            });
        });

        $(".add_token_btn").click(function(e){
            $('input[name="action"]').val('add');
            $('#textarea_deduct').attr('required', false);
            $('#textarea_add').attr('required', true);
            $(".deduct_token_div").fadeOut({
                done: function() {
                    $(".add_token_div").fadeIn();
                    $(".panel-footer").show();
                }
            });
        });

        $(".deduct_token_btn").click(function(e){
            $('input[name="action"]').val('deduct');
            $('#textarea_add').attr('required', false);
            $('#textarea_deduct').attr('required', true);
            $(".add_token_div").fadeOut({
                done: function() {
                    $(".deduct_token_div").fadeIn();
                    $(".panel-footer").show();
                }
            });
        });
    });

    function populateAmount(res){
        $(".add_deduct_div").fadeOut({
            done: function() {
                console.log(res.qty)
                const locationQty = res.qty;
                // Format locationQty with commas
                $('#current_token_amount').val(locationQty ? locationQty.toLocaleString() : 0);
                $('#new_token_add, #new_token_deduct').val(locationQty ? locationQty.toLocaleString() : 0);
            }
        });
        $('.add_deduct_div').slideDown();
        $('.add_token_div').slideUp();
        $('.deduct_token_div').slideUp();
        $('.panel-footer').slideUp();
        $('.action_btn').removeClass('active_button');
    }

    $('#add_token_input, #deduct_token_input').on('input', function(e) {
        const action = $(this).attr('_action');
        const currentTokenAmount = $('#current_token_amount').val().replace(/\D/g, '');
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
        $(`#new_token_${action}`).val(newValue.toLocaleString());
    })

    $('.action_btn').on('click', function() {
        $('.action_btn').removeClass('active_button');
        $(this).addClass('active_button');
        clearInput();
    });

    function clearInput() { 
        $('#add_token_input, #deduct_token_input, #textarea_add, #textarea_deduct').val('');
        const currentTokenAmount = $('#current_token_amount').val();
        $('#new_token_add, #new_token_deduct').val(currentTokenAmount);
    }

    $('#save-btn').on('click', function() {
        const selectedValue = $('#select_store_location').val();
        const action = $('input[name="action"]').val();
        let value = 0;
        if (action == 'add') {
            value = $('#add_token_input').val().replace(/\D/g, '');
        } else {
            value = $('#deduct_token_input').val().replace(/\D/g, '');
        }

        $.ajax({
            url: "{{ route('getTokenInventory') }}",
            type:"POST",
            dataType:'json',
            data:{
                _token:"{{csrf_token()}}",
                location_id: selectedValue,
                action,
                value,
            },
            success:function(res){
                console.log(res);
                const clonedTable = $('.table_summary').clone().show();
                clonedTable.find('.location-cell').text(res.location_name);
                clonedTable.find('.action-cell').text(res.action.toUpperCase());
                clonedTable.find('.current-token-amount-cell').text(res.qty.toLocaleString());
                clonedTable.find('.adjusment-amount-cell').text(res.adjustment_qty.toLocaleString());
                clonedTable.find('.new-token-amount-cell').text(res.new_qty.toLocaleString());
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