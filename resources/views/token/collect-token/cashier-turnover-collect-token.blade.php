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

    .form-button .btn-void {
        padding: 9px 15px;
        margin-right: 10px;
        background: #FF5733;
        border: 1.5px solid #FF5733;
        border-radius: 5px;
        color: white;
    }

    .form-button .btn-void:hover {
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
    
    /* CHATBOX */

    .chat-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #3C8DBC;
        z-index: 20;
        cursor: pointer;
        padding: 10px 15px;
        color: white;
        font-size: 16px;
        border-radius: 20px;
        user-select: none;
      
    }
    .chat-button:hover{
        background-color: #53bbf7;
    }

    .chat-container{
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 20;
        border-radius: 5px;
        overflow: hidden;
        background-color: white;
        width: 350px;
        height: 400px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.25);
        display: flex;
        flex-direction: column;
    }

    .top-chat-container {
        display: flex;
        padding: 0 20px;
        user-select: none;
        align-items: center;
        justify-content: space-between;
        flex: 0 0 13%;
        color: white;
        background-color: #3C8DBC;
    }

    .body-chat-container {
        flex: 1;
        padding: 0 5px 5px 5px;
        overflow: auto;
        display: flex;
        flex-direction: column-reverse;
        gap: 5px
    }

    
    .chat-content-left{
        align-self: flex-start;
        display: flex;
        align-items: flex-end;
    }
    .left-chat-details{
        margin-left: 5px;
    }
    
    .chat-content-left-text{
        
        background-color: #dddddd;
        padding: 7px;
        max-width: 170px;
        border-radius: 10px;
    }

    .chat-content-right{
        align-self: flex-end;
        margin-left: 5px;
        background-color: #dddddd;
        padding: 7px;
        max-width: 170px;
        border-radius: 10px;
    }

    .body-chat-container::-webkit-scrollbar {
        width: 8px;
    }

    .body-chat-container::-webkit-scrollbar-track {
        background: #f0f0f0;
        border-radius: 4px;
    }

    .body-chat-container::-webkit-scrollbar-thumb {
        background-color: #3C8DBC; 
        border-radius: 4px; 
        border: 2px solid #f0f0f0; 
    }

    .body-chat-container::-webkit-scrollbar-thumb:hover {
        background-color: #2e6a8e;
    }

    .bottom-chat-container {
        border-top: 1px solid #bbb;
        padding: 10px 10px 5px 10px;
        display: flex;
        align-items: flex-end;
        
    }

    .bottom-chat-container textarea {
        width: 100%;
        padding: 10px;
        box-sizing: border-box;
        border: 1px solid #3C8DBC; 
        border-radius: 4px;
        outline-color: #3C8DBC;
        font-size: 14px;
        overflow-y: auto;
        resize: none;
        margin: 0;
        max-height: 120px;
    }

    .bottom-chat-container textarea::-webkit-scrollbar {
        width: 0;
    }

    .chat-textarea-div{
        width: 100%;
    }

    .chat-send {
        display: flex;
        background-color: #3C8DBC;
        cursor: pointer;
        align-items: center;
        border-radius: 50px;
        margin-left: 10px;
        padding: 10px;
        margin-bottom: 7px;
    }

    .chat-send:hover{
        background-color: #53bbf7;
    }

    .swal2-popup {
        width: 500px !important; 
        /* height: 300px !important; */
    }
    .swal2-title {
        font-size: 24px !important; 
    }
    .swal2-html-container {
        font-size: 16px !important; 
        overflow: hidden !important;
    }

    .swal2-confirm {
        font-size: 16px !important;
        padding: 10px 20px !important;
        border-radius: 5px !important;
        color: white !important;
    }
    .swal2-cancel {
        font-size: 16px !important;
        padding: 10px 20px !important;
        border-radius: 5px !important;
        color: white !important;
    }

    .swal2-icon {
        font-size: 16px !important; /* Customize the icon size */
        width: 80px !important;
        height: 80px !important;
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

</style>
@endpush
@section('content')
<div class="panel panel-default form-content">
<form id="confirm-details" method="POST" action="{{route('postCashierTurnover')}}" id="collect_token_details">
    @csrf
    <div class="panel-heading header-title text-center">Collect Token Details</div>
    <div class="content-panel">
        @foreach ($collected_tokens as $detail)
            <input type="hidden" name="collectedTokenHeader_id" id="collectedTokenHeader_id" value="{{$detail->id}}" readonly>
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
                    <input type="text" id="bay_header" style="border-radius: 5px;" value="{{$detail->getBay->name}}" disabled>
                </div>
                
            </div>
            
            <div class="table-wrapper custom-scroll-x">
                <table>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail->lines as $perLine)
                            @foreach ($perLine->inventory_capsule_lines as $capsuleLine)
                                @php
                                    $NoOfToken = $perLine->no_of_token; 
                                    $tokenCollected = $perLine->qty; 

                                    $divisionResult = $tokenCollected / $NoOfToken;
                                    $projectedCapsuleSales = ceil($divisionResult);
                                    
                                    $actualCapsuleInventory = $capsuleLine->qty;
                                    $currentMachineInventory = $capsuleLine->qty;
                                    $variance = $perLine->variance;
                                @endphp
                                <tr>
                                    <td>
                                        <span class="serial_number">{{$perLine->machineSerial->serial_number}}</span>
                                        <input type="hidden" name="serial_number[]" value="{{$perLine->machineSerial->serial_number}}" readonly>
                                        <input type="hidden" name="lines_ids[]" value="{{$perLine->id}}" readonly>
                                    </td>
                                    <td>
                                        <span class="jan#">{{$capsuleLine->getInventoryCapsule->item->digits_code}}</span>
                                        <input type="hidden" name="jan#[]" value="{{$capsuleLine->getInventoryCapsule->item->digits_code}}" readonly>
                                    </td> 
                                    <td>
                                        <span class="jan#">{{$capsuleLine->getInventoryCapsule->item->item_description}}</span>
                                    </td> 
                                    <td>
                                        <span class="no_of_token">{{$perLine->no_of_token}}</span>
                                        <input type="hidden" name="no_of_token[]" value="{{$perLine->no_of_token}}" readonly>
                                    </td>
                                    <td>
                                        <span class="tokenCollected">{{$perLine->qty}}</span>
                                        <input type="hidden" name="tokenCollected[]" value="{{$perLine->qty}}" readonly>
                                    </td>
                                    <td 
                                        @if($perLine->variance != 0)
                                            style="background-color: #f8d7da"
                                        @endif
                                    >
                                        <span class="variance">{{$perLine->variance}}</span>
                                        <input type="hidden" class="defaultVariance" value="{{$perLine->variance}}" readonly>
                                        <input type="hidden" class="variance" name="variance[]" value="{{$perLine->variance}}" readonly>
                                    </td>
                                    <td>
                                        <span class="projectedCapsuleSales">{{$projectedCapsuleSales}}</span>
                                        <input type="hidden" name="projectedCapsuleSales[]" value="{{$projectedCapsuleSales}}" readonly>
                                    </td>
                                    <td>
                                        <span class="currentMachineInventory">{{$capsuleLine->qty}}</span>
                                        <input type="hidden" name="currentMachineInventory[]" value="{{$capsuleLine->qty}}" readonly>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"><b>Total</b></td>
                            <td class="total_token_collected"></td>
                            <td class="total_variance"></td>
                            <td class="total_projected_capsule_sale"></td>
                            <td class="total_current_capsule_inventory"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        
            @endforeach

            <div style="margin-top: 10px;"><b>Note:</b> <b style="color: red">UNDECLARED</b> or <b style="color: red">MISDECLARED</b> Tokens found after Token Collection should be kept inside the Gasha machine and declared the next day.</div>

            </div>
    </form>
    <div class="form-button panel-footer" style="margin-top: 15px;" >
        <a class="btn-submit pull-left" href="{{ CRUDBooster::mainpath() }}" style="background:#838383; border: 1px solid #838383">Cancel</a>
        <button type="submit" class="btn-submit pull-right" id="btn-confirm-details">Collect Token</button>
        <form method="POST" action="{{route('post.void.collectToken')}}" id="void_cashier_turnover_collect_token">
            @csrf
            <input type="hidden" name="collected_token_header_id" id="collected_token_header_id" value="{{$detail->id}}" readonly>
            @php
                $created_date = date('Y-m-d', strtotime($detail->created_at));
            @endphp

            @if ($created_date == date('Y-m-d', strtotime(now())))
                <button type="submit" class="btn-void pull-right" id="void_btn"> <i class="fa fa-times"></i> Void Collect Token</button>
            @endif
        </form>
    </div>
</div>

    {{-- CHAT --}}
    <div class="chat-button" id="chat-button" style="display: show">
        <i class="fa fa-edit" aria-hidden="true" style="font-weight: 700; font-size:18px; margin-right:5px;"></i>
        <span style="font-weight: 600; font-size:18px;">Remarks</span>
    </div>

    <div class="chat-container" id="chat-container" style="display: none">
        <div class="top-chat-container">
            <div style="font-size: 18px; font-weight:600">Remarks</div>
            <i class="fa fa-times" aria-hidden="true" style="font-size: 18px; cursor: pointer;" id="chat-close"></i>
        </div>
        <div class="body-chat-container">
            @foreach ($collected_tokens as $CTHeader)
                <input type="hidden" name="message_collect_token_id" id="message_collect_token_id" value="{{$CTHeader->id}}" readonly>
                @foreach ($CTHeader->collectTokenMessages as $perMessage)
                    @if ($perMessage->created_by == CRUDBooster::myId())
                        <div class="chat-content-right">
                            <p style="margin: 0; padding:0; word-wrap: break-word; word-break: break-all;">{{$perMessage->message}}</p>
                        </div>
                    @else
                        <div class="chat-content-left">
                            <div class="profile" style="background-color: #e04923; width:fit-content; height:fit-content; border-radius:50px; overflow:hidden;">
                                <img src="{{ Storage::exists($perMessage->getUser->photo) 
                                        ? url('/'.$perMessage->getUser->photo)
                                        : url('/'. 'img/user.png') }}" 
                                style="width: 40px;" alt="...">

                            </div>
                            <div class="left-chat-details">
                                <div style="font-size: 12px; margin-bottom: 2px; color:#6d6a6a;">{{$perMessage->getUser->name}} <span> | {{$perMessage->getUser->getPrivilege->name}}</span></div>
                                <div class="chat-content-left-text">
                                    <p style="margin: 0; padding:0; word-wrap: break-word; word-break: break-all;">{{$perMessage->message}}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
        <div class="bottom-chat-container">
            <div class="chat-textarea-div">
                <textarea rows="1" id="auto-resize-textarea" class="new_remarks" placeholder="Enter your message..."></textarea>
            </div>
            <div class="chat-send">
                <i class="fa fa-paper-plane" id="send_new_remarks" aria-hidden="true" style="color:white; font-size:16px;"></i>
            </div>
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

    $('#chat-button').on('click', function() {
        $(this).hide();
        $('#chat-container').show();
    });

    $('#chat-close').on('click', function() {
        $('#chat-container').hide();
        $('#chat-button').show();
    });


    $(document).ready(function(){
        const no_of_token = $('.no_of_token').text();
    })

    $('#send_new_remarks').on('click', function() {
        submitMessage();
    });

    $('.new_remarks').on('keydown', function(event) {
        if (event.keyCode === 13 && !event.shiftKey) {
            event.preventDefault();
            submitMessage();
        }
    });

    function submitMessage(){
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const MessageCollectTokenId = $('#message_collect_token_id').val();
        const NewRemarks = $('.new_remarks').val();
        
        if (NewRemarks.trim() === '') {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please enter your message!"
            });
            return;
        }

        $.ajax({
            url:'{{route("postNewRemarks")}}',
            method: 'POST',
            data: {
                MessageCollectTokenId: MessageCollectTokenId,
                NewRemarks: NewRemarks,
                _token: csrfToken,
            },
            success: function(response) {
                console.log(response);
                const newMessage = `
                    <div class="chat-content-right">
                        <p style="margin: 0; padding: 0; word-wrap: break-word; word-break: break-all;">${response.message}</p>
                    </div>
                `;
                $('.body-chat-container').prepend(newMessage);
                $('.body-chat-container').scrollTop($('.body-chat-container')[0].scrollHeight);
                $('.new_remarks').val("");
                
            },
            error: function() {
                alert('Error Request!');
            }
            
        });
    }

    $('#btn-confirm-details').on('click', function(e) {
        e.preventDefault(); 
        const form = document.getElementById('confirm-details');

        if (form.checkValidity()) {
            Swal.fire({
                title: "Are you sure you want to Collected Token?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3C8DBC',
                cancelButtonColor: '#838383',
                confirmButtonText: 'Collect',
                iconColor: '#3C8DBC',
                returnFocus: false,
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#loadingBackdrop').show();
                    form.submit(); 
                }
            });
        } else {
            form.reportValidity();
        }
    });

    $('#void_btn').on('click', function(e) {
        e.preventDefault(); 
        const form = document.getElementById('void_cashier_turnover_collect_token');
        const bay_header = $('#bay_header').val();

        if (form.checkValidity()) {
            Swal.fire({
                title: `<h3>Are you sure you want to <b>VOID</b> ${bay_header} <br> Collect Token? </h3>`,
                html: `
                        <p>
                            <b style="color:darkorange"> <i class="fa fa-exclamation-circle"></i> NOTE:</b> Before Voiding Collect Token transaction please make sure that
                            your are advised to do it, this is only an emergency action to take if
                            there's an isuue that needed to be solve. If none <br> please don't VOID the transaction.
                        </p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FF5733',
                cancelButtonColor: '#838383',
                confirmButtonText: 'Void',
                iconColor: '#FF5733',
                returnFocus: false,
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#loadingBackdrop').show();
                    form.submit(); 
                }
            });
        } else {
            form.reportValidity();
        }
    });

    // compute subtotals
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
                // Get values from the table columns
                let tokenCollected = parseFloat(row.querySelector('.tokenCollected')?.textContent || 0);
                let variance = parseFloat(row.querySelector('.variance')?.textContent || 0);
                let projectedCapsuleSales = parseFloat(row.querySelector('.projectedCapsuleSales')?.textContent || 0);
                let currentMachineInventory = parseFloat(row.querySelector('.currentMachineInventory')?.textContent || 0);
                let actualCapsuleInventory = parseFloat(row.querySelector('.ActualCapsuleInventory')?.value || 0);
                let actualCapsuleSales = parseFloat(row.querySelector('.actualCapsuleSales')?.value || 0);

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

        // Recalculate totals when ActualCapsuleInventory is updated
        document.querySelectorAll('.ActualCapsuleInventory').forEach(input => {
            input.addEventListener('input', updateTotals);
        });
    });
</script>
@endpush