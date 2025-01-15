@extends('crudbooster::admin_template')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
@push('head')
<style type="text/css">
    .form-content {
        display: flex;
        background: #fff;
        flex-direction: column;
        font-family: 'Poppins', sans-serif !important;
        border-radius: 10px;
    }

    .header-title {
        background: #3C8DBC !important;
        color: #fff !important;
        font-size: 16px;
        font-weight: 500;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .content-panel {
        padding: 15px;
        
    }

    .inputs-container{
        display: flex;
        gap: 10px;
    }

    .input-container{
        flex: 1;
    }

    .form-button .btn-submit {
        padding: 9px 15px;
        background: #3C8DBC;
        border: 1.5px solid #1d699c;
        border-radius: 5px;
        color: white;
    }

    .form-button .btn-submit:hover {
        opacity: 0.7;
    }

    /* TABLE */

    .table-wrapper {
        overflow-x: auto;
        margin-bottom: 10px;

    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0 10px 0;
        font-size: 12px;
    }

    th, td {
        border: 1px solid #3C8DBC;
        padding: 12px;
        text-align: center;
    }


    th {
        background-color: #3C8DBC;
        color: white;
        font-weight: bold;
        white-space: nowrap;
        width: auto; 
        
    }

      /* Make the specific headers sticky */
    th.sticky-header {
        position: sticky;
        top: 0;
        z-index: 1; /* Ensures they stay above other content */
        background-color: #3C8DBC; /* Match the table header color */
    }

    /* Add this style for smooth scrolling and avoid overlap */
    .custom-scroll-x {
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    input[type="text"] {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        border: 1px solid #3C8DBC; 
        outline-color: #3C8DBC
    }

    input[type="text"]:disabled {
        background-color: #F3F3F3;
    }

    textarea {
        width: 100%;
        padding: 12px;
        box-sizing: border-box;
        border: 1px solid #3C8DBC; 
        border-radius: 4px;
        outline-color: #3C8DBC;
        font-size: 14px;
        resize: vertical;
    }

    .no-variance-type {
        background: linear-gradient(to right, #53FF14, #2C8624);
        border: none;
        color: white;
        padding: 10px 20px;
        white-space: nowrap;
        border-radius: 10px;
        font-weight: bold;
    }

    .short-type {
        background: linear-gradient(to right, #FF1414, #780E0E);
        border: none;
        color: white;
        padding: 10px 20px;
        white-space: nowrap;
        border-radius: 10px;
        font-weight: bold;
    }
    
    .over-type {
        background: linear-gradient(to right, #FA922A, #AD5600);
        border: none;
        color: white;
        padding: 10px 20px;
        white-space: nowrap;
        border-radius: 10px;
        font-weight: bold;
    }

    /* X SCROLLBAR */

    .custom-scroll-x {
    overflow-x: auto; 
    overflow-y: hidden;
    white-space: nowrap;
    }

    .custom-scroll-x::-webkit-scrollbar {
        height: 8px; 
    }

    .custom-scroll-x::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .custom-scroll-x::-webkit-scrollbar-thumb {
        background-color: #4db2ec;
        border-radius: 10px;
        border: 2px solid #f1f1f1; 
    }

    .custom-scroll-x::-webkit-scrollbar-thumb:hover {
    
    }


    .swal2-popup {
        font-size: unset !important;
    }

    /* The backdrop (gray transparent background) */
    .loading-backdrop {
        position: fixed;    /* Fixed to cover the whole page */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Dark semi-transparent background */
        display: none;      /* Initially hidden */
        justify-content: center;  /* Horizontally center the card */
        align-items: center;     /* Vertically center the card */
        z-index: 9999;      /* Ensure it's on top of other content */
        display: flex;      /* Flexbox for centering */
    }

    /* Loading card styles */
    .loading-card {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);  /* Slight shadow for card effect */
        text-align: center;
        width: 200px; /* Small fixed width for the card */
    }

    /* Spinner styles */
    .spinner {
        border: 5px solid lightgrey; /* Light border */
        border-top: 5px solid #3498db; /* Blue color for the spinner */
        border-radius: 50%;
        width: 55px;
        height: 55px;
        margin: 0 auto 10px;  /* Centered with margin below */
        animation: spin2 0.7s linear infinite;  /* Rotation animation */
    }

    /* Animation for the spinner */
    @keyframes spin2 {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .highlight {
        background-color: yellow !important;
    }

</style>
@endpush
@section('content')
<div class="panel panel-default form-content">
<form id="confirm-details" method="POST" action="{{route('postSuperAdminEdit')}}" id="collect_token_details">
    @csrf
    <div class="panel-heading header-title text-center">Collect Token Details</div>
    <div class="content-panel">
        @foreach ($collected_tokens as $detail)
            <input type="hidden" name="collected_token_header_id" id="collected_token_header_id" value="{{$detail->id}}" readonly>
            <div class="inputs-container" style="margin-bottom: 10px;">
                <div class="input-container">
                    <div style="font-weight: 600">Reference Number</div>
                    <input type="text" style="border-radius: 5px;" value="{{$detail->reference_number}}" disabled>
                </div>
                <div class="input-container">
                    <div style="font-weight: 600" >Date Created</div>
                    <input type="text" style="border-radius: 5px;" value="{{$detail->created_at}}" disabled>
                </div>
                
            </div>
            <div class="inputs-container" style="margin-bottom: 10px;">
                <div class="input-container">
                    <div style="font-weight: 600">Location</div>
                    <input type="text" style="border-radius: 5px;" value="{{$detail->getLocation->location_name}}" disabled>
                </div>
                <div class="input-container">
                    <div style="font-weight: 600">Bay</div>
                    <input type="text" style="border-radius: 5px;" value="{{$detail->getBay->name}}" disabled>
                </div>
                
            </div>

            <div class="inputs-container" style="margin-bottom: 10px;">
                <div class="input-container">
                    <div style="font-weight: 600">Collected Qty</div>
                    <input type="text" name="header_collected_qty" style="border-radius: 5px;" value="{{$detail->collected_qty}}">
                </div>
                <div class="input-container">
                    <div style="font-weight: 600">Received Qty</div>
                    <input type="text" name="header_received_qty" style="border-radius: 5px;" value="{{$detail->received_qty}}">
                </div>
                <div class="input-container">
                    <div style="font-weight: 600">Variance</div>
                        <select style="border-radius: 5px;width: 100%;padding: 8px;box-sizing: border-box;border: 1px solid #3C8DBC;outline-color: #3C8DBC" name="header_variace">
                            <option value="Yes" {{ $detail->variance == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ $detail->variance == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                        
                </div>
                
            </div>
            
            <div class="table-wrapper custom-scroll-x">
                <table id="confirm_collecttoken_tbl">
                    <thead>
                        <tr>
                            <th>Machine #</th>
                            <th>JAN #</th>
                            <th>Item Description</th>
                            <th>No of Token</th>
                            <th>Token Collected</th>
                            <th>Variance</th>
                            <th>Projected Capsule Sales</th>
                            <th>Current Capsule Inventory</th>
                            <th>Actual Capsule Inventory</th>
                            <th>Actual Capsule Sales</th>
                            <th>Variance Type</th>
                            <th>-</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail->lines as $perLine)
                            @foreach ($perLine->inventory_capsule_lines as $capsuleLine)
                                <tr>
                                    <td>
                                        <span class="serial_number">{{$perLine->machineSerial->serial_number}}</span>
                                    </td>
                                    <td>
                                        <span class="jan#">{{$capsuleLine->getInventoryCapsule->item->digits_code}}</span>
                                    </td> 
                                    <td>
                                        <span class="jan#">{{$capsuleLine->getInventoryCapsule->item->item_description}}</span>
                                    </td> 
                                    <td>
                                        <span class="no_of_token">{{$perLine->no_of_token}}</span>
                                        <input type="text" value="{{$perLine->no_of_token}}" name="collect_token_lines_no_of_token[]" class="collect_token_lines_no_of_token" style="border-radius: 7px; text-align:center;display:none;">
                                        <input type="hidden" value="{{$perLine->id}}" name="collect_token_lines_id[]" class="collect_token_lines_no_of_token" style="border-radius: 7px; text-align:center;display:none;" readonly>
                                    </td>
                                    <td>
                                        <span class="tokenCollected">{{$perLine->qty}}</span>
                                        <input type="text" value="{{$perLine->qty}}" name="collect_token_lines_collected_qty[]" class="collect_token_lines_collected_qty" style="border-radius: 7px; text-align:center;display:none;">
                                    </td>
                                    <td>
                                        <span class="variance">{{$perLine->variance}}</span>
                                        <input type="text" value="{{$perLine->variance}}" name="collect_token_lines_variance[]" class="collect_token_lines_variance" style="border-radius: 7px; text-align:center;display:none;">
                                    </td>
                                    <td>
                                        <span class="projectedCapsuleSales">{{$perLine->projected_capsule_sales}}</span>
                                        <input type="text" value="{{$perLine->projected_capsule_sales}}" name="collect_token_lines_projected_capsule_sales[]" class="collect_token_lines_projected_capsule_sales" style="border-radius: 7px; text-align:center;display:none;">
                                    </td>
                                    <td>
                                        <span class="currentMachineInventory">{{$perLine->current_capsule_inventory}}</span>
                                        <input type="text" value="{{$perLine->current_capsule_inventory}}" name="collect_token_lines_current_capsule_inventory[]" class="collect_token_lines_current_capsule_inventory" style="border-radius: 7px; text-align:center;display:none;">
                                    </td>
                                    <td>
                                        <span class="ActualCapsuleInventory">{{$perLine->actual_capsule_inventory}}</span>
                                        <input type="text" value="{{$perLine->actual_capsule_inventory}}" name="collect_token_lines_actual_capsule_inventory[]" class="collect_token_lines_actual_capsule_inventory" style="border-radius: 7px; text-align:center;display:none;">
                                    </td>
                                    <td>
                                        <span class="actualCapsuleSales">{{$perLine->actual_capsule_sales}}</span>
                                        <input type="text" value="{{$perLine->actual_capsule_sales}}" name="collect_token_lines_actual_capsule_sales[]" class="collect_token_lines_actual_capsule_sales" style="border-radius: 7px; text-align:center;display:none;">
                                    </td>
                                    <td>
                                        <span class="variance_type
                                            @if ($perLine->variance_type == 'No Variance') 
                                                no-variance-type
                                            @elseif ($perLine->variance_type == 'Short') 
                                                short-type
                                            @elseif ($perLine->variance_type == 'Over') 
                                                over-type
                                            @endif
                                        "
                                        >{{$perLine->variance_type}}</span>
                                        <select style="border-radius: 5px;width: 100%;padding: 8px;box-sizing: border-box;border: 1px solid #3C8DBC;outline-color: #3C8DBC; display:none;" name="collect_token_lines_variance_type[]" class="collect_token_lines_variance_type">
                                            <option value="No Variance" {{ $perLine->variance_type == 'No Variance' ? 'selected' : '' }}>No Variance</option>
                                            <option value="Short" {{ $perLine->variance_type == 'Short' ? 'selected' : '' }}>Short</option>
                                            <option value="Over" {{ $perLine->variance_type == 'Over' ? 'selected' : '' }}>Over</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success edit-btn" style="border-radius: 20%"> 
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot style="font-weight:700">
                        <tr>
                            <td colspan="4"><b>Total</b></td>
                            <td class="total_token_collected"></td>
                            <td class="total_variance"></td>
                            <td class="total_projected_capsule_sale"></td>
                            <td class="total_current_capsule_inventory"></td>
                            <td class="total_actual_capsule_inventory"></td>
                            <td class="total_actual_capsule_sales"></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        
            @endforeach
        </div>
    </form>
    <div class="form-button panel-footer" style="margin-top: 15px;" >
        <a class="btn-submit pull-left" href="{{ CRUDBooster::mainpath() }}" style="background:#838383; border: 1px solid #838383">Cancel</a>
        <button type="submit" class="btn-submit pull-right" id="btn-confirm-details">Save Update</button>
    </div>
</div>

    <div id="loadingBackdrop" class="loading-backdrop" style="display: none">
        <div class="spinner"></div>
    </div>

@endsection

@push('bottom')
<script>
    $('.content-header').hide();
    $(document).ready(function() {
        $(function(){
            $('body').addClass("sidebar-collapse");
        });

        $('#auto-resize-textarea').on('input', function() {
            $(this).css('height', 'auto'); // Reset height
            $(this).css('height', this.scrollHeight + 'px'); // Set to scroll height
        });
    });

    $('#btn-confirm-details').on('click', function(e) {
        e.preventDefault(); 
        const form = document.getElementById('confirm-details');

            Swal.fire({
            title: "Are you sure you want to save edit collect token?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3C8DBC',
            cancelButtonColor: '#838383',
            confirmButtonText: 'Confirm',
            iconColor: '#3C8DBC',
            returnFocus: false,
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#loadingBackdrop').show();
                form.submit(); 
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {

        function updateTotals() {
            let totalTokenCollected = 0;
            let totalVariance = 0;
            let totalProjectedCapsuleSales = 0;
            let totalCurrentCapsuleInventory = 0;
            let totalActualCapsuleInventory = 0;
            let totalActualCapsuleSales = 0;

            let rows = document.querySelectorAll('table tbody tr');
            
            rows.forEach(row => {
                let tokenCollected = parseFloat(row.querySelector('.collect_token_lines_collected_qty')?.value || 0);
                let variance = parseFloat(row.querySelector('.collect_token_lines_variance')?.value || 0);
                let projectedCapsuleSales = parseFloat(row.querySelector('.collect_token_lines_projected_capsule_sales')?.value || 0);
                let currentMachineInventory = parseFloat(row.querySelector('.collect_token_lines_current_capsule_inventory')?.value || 0);
                let actualCapsuleInventory = parseFloat(row.querySelector('.collect_token_lines_actual_capsule_inventory')?.value || 0);
                let actualCapsuleSales = parseFloat(row.querySelector('.collect_token_lines_actual_capsule_sales')?.value || 0);

                // If actualCapsuleSales is 0, use projectedCapsuleSales for calculation
                if (variance === 0 && actualCapsuleSales === 0) {
                    actualCapsuleSales = projectedCapsuleSales;
                }

                totalTokenCollected += tokenCollected;
                totalVariance += variance;
                totalProjectedCapsuleSales += projectedCapsuleSales;
                totalCurrentCapsuleInventory += currentMachineInventory;
                totalActualCapsuleInventory += actualCapsuleInventory;
                totalActualCapsuleSales += actualCapsuleSales;
            });

            // Update the footer with the totals
            document.querySelector('.total_token_collected').textContent = totalTokenCollected.toFixed();
            document.querySelector('.total_variance').textContent = totalVariance.toFixed();
            document.querySelector('.total_projected_capsule_sale').textContent = totalProjectedCapsuleSales.toFixed();
            document.querySelector('.total_current_capsule_inventory').textContent = totalCurrentCapsuleInventory.toFixed();
            document.querySelector('.total_actual_capsule_inventory').textContent = totalActualCapsuleInventory.toFixed();
            document.querySelector('.total_actual_capsule_sales').textContent = totalActualCapsuleSales.toFixed();
        }

        updateTotals();

        let debounceTimer;
        function debouncedUpdateTotals() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(updateTotals, 100); 
        }

        document.querySelectorAll('.collect_token_lines_collected_qty').forEach(input => {
            input.addEventListener('input', debouncedUpdateTotals);
        });
        document.querySelectorAll('.collect_token_lines_variance').forEach(input => {
            input.addEventListener('input', debouncedUpdateTotals);
        });
        document.querySelectorAll('.collect_token_lines_projected_capsule_sales').forEach(input => {
            input.addEventListener('input', debouncedUpdateTotals);
        });
        document.querySelectorAll('.collect_token_lines_current_capsule_inventory').forEach(input => {
            input.addEventListener('input', debouncedUpdateTotals);
        });
        document.querySelectorAll('.collect_token_lines_actual_capsule_inventory').forEach(input => {
            input.addEventListener('input', debouncedUpdateTotals);
        });
        document.querySelectorAll('.collect_token_lines_actual_capsule_sales').forEach(input => {
            input.addEventListener('input', debouncedUpdateTotals);
        });
    });

    $(document).on('click', '.edit-btn', function () {
        let $row = $(this).closest('tr');

        if ($row.hasClass('highlight')) {
            $row.removeClass('highlight').css('background-color', 'transparent');
            $row.find('.collect_token_lines_no_of_token').hide();
            $row.find('.collect_token_lines_collected_qty').hide();
            $row.find('.collect_token_lines_variance').hide();
            $row.find('.collect_token_lines_projected_capsule_sales').hide();
            $row.find('.collect_token_lines_current_capsule_inventory').hide();
            $row.find('.collect_token_lines_actual_capsule_inventory').hide();
            $row.find('.collect_token_lines_actual_capsule_sales').hide();
            $row.find('.collect_token_lines_variance_type').hide();

            $row.find('.no_of_token').show();
            $row.find('.tokenCollected').show();
            $row.find('.variance').show();
            $row.find('.projectedCapsuleSales').show();
            $row.find('.currentMachineInventory').show();
            $row.find('.ActualCapsuleInventory').show();
            $row.find('.actualCapsuleSales').show();
            $row.find('.variance_type').show();

            $('.edit-btn').prop('disabled', false);
        } else {
            $('tr').removeClass('highlight');
            $row.addClass('highlight').css('background-color', 'yellowgreen');

            toggleInputVisibility($row.find('.collect_token_lines_no_of_token'));
            toggleInputVisibility($row.find('.collect_token_lines_collected_qty'));
            toggleInputVisibility($row.find('.collect_token_lines_variance'));
            toggleInputVisibility($row.find('.collect_token_lines_projected_capsule_sales'));
            toggleInputVisibility($row.find('.collect_token_lines_current_capsule_inventory'));
            toggleInputVisibility($row.find('.collect_token_lines_actual_capsule_inventory'));
            toggleInputVisibility($row.find('.collect_token_lines_actual_capsule_sales'));
            toggleInputVisibility($row.find('.collect_token_lines_variance_type'));

            $row.find('.no_of_token').hide();
            $row.find('.tokenCollected').hide();
            $row.find('.variance').hide();
            $row.find('.projectedCapsuleSales').hide();
            $row.find('.currentMachineInventory').hide();
            $row.find('.ActualCapsuleInventory').hide();
            $row.find('.actualCapsuleSales').hide();
            $row.find('.variance_type').hide();

            $('.edit-btn').not(this).prop('disabled', false);
        }
    });

    function toggleInputVisibility($input) {
        let inputValue = $input.val();
        if (inputValue === null || inputValue === "") {
            $input.prop('disabled', true).hide();
        } else {
            $input.prop('disabled', false).show();
        }
    }

</script>
@endpush