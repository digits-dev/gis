@extends('crudbooster::admin_template')
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
        border: 1px solid #ddd;
        padding: 12px;
        text-align: center;
    }

    th:nth-child(10){
        position: sticky;
        right: 0; 
        background-color: #3C8DBC; 
        z-index: 2;
        box-shadow: -1px 0 2px rgba(0, 0, 0, 0.1);
    }

    td:nth-child(10){
        position: sticky;
        right: 0; 
        background-color: white; 
        z-index: 2; /* Ensures it stays above other content */
        box-shadow: -1px 0 2px rgba(0, 0, 0, 0.1);
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
        flex-direction: column;
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

</style>
@endpush
@section('content')
<form class="panel panel-default form-content" method="POST" action="{{route('postCashierTurnover')}}" id="collect_token_details">
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
                    <div style="font-weight: 600">Location</div>
                    <input type="text" style="border-radius: 5px;" value="{{$detail->getLocation->location_name}}" disabled>
                </div>
            </div>
            <div class="inputs-container">
                <div class="input-container">
                    <div style="font-weight: 600">Total Quantity</div>
                    <input type="text" style="border-radius: 5px;" value="{{$detail->collected_qty}}" disabled>
                </div>

                <div class="input-container">
                    <div style="font-weight: 600" >Date Created</div>
                    <input type="text" style="border-radius: 5px;" value="{{$detail->created_at}}" disabled>
                </div>
            </div>
            
            <div class="table-wrapper custom-scroll-x">
                <table>
                    <thead>
                        <tr>
                            <th>Machine #</th>
                            <th>JAN #</th>
                            <th>No of Token</th>
                            <th>Token Collected</th>
                            <th>Variance</th>
                            <th>Projected Capsule Sales</th>
                            <th>Current Capsule Inventory</th>
                            <th>Actual Capsule Inventory</th>
                            <th>Actual Capsule Sales</th>
                            <th>Variance Type</th>
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
                                        <span class="no_of_token">{{$perLine->no_of_token}}</span>
                                        <input type="hidden" name="no_of_token[]" value="{{$perLine->no_of_token}}" readonly>
                                    </td>
                                    <td>
                                        <span class="tokenCollected">{{$perLine->qty}}</span>
                                        <input type="hidden" name="tokenCollected[]" value="{{$perLine->qty}}" readonly>
                                    </td>
                                    <td>
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
                                    <td>
                                        @if ($perLine->variance != 0)
                                            <input type="text" placeholder="Enter Quantity" class="ActualCapsuleInventory" name="actualCapsuleInventory[]" style="text-align: center; border-radius: 7px;" oninput="this.value = this.value.replace(/[^0-9]/g, '');" autocomplete="off" required>
                                        @elseif ($perLine->variance == 0)
                                            <input type="text" name="actualCapsuleInventory[]" id="actualCapsuleInventory" style="text-align: center; border: none; outline:none; background:transparent;" value="{{$capsuleLine->qty}}" readonly>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="actualCapsuleSales">
                                            @if ($perLine->variance == 0)
                                                {{$projectedCapsuleSales}}  
                                            @endif
                                        </span>
                                        <input type="hidden" class="actualCapsuleSales" name="actualCapsuleSales[]" value="{{$projectedCapsuleSales}}" readonly>
                                    </td>
                                    <td>
                                        @php
                                            $currentMachineInventory = $capsuleLine->qty; 
                                        @endphp
                                        <span class="variance-status
                                            @if (($currentMachineInventory - $projectedCapsuleSales) == $actualCapsuleInventory || $variance == 0) 
                                                no-variance-type
                                            @elseif (($currentMachineInventory - $projectedCapsuleSales) == $actualCapsuleInventory && $variance > 0) 
                                                short-type
                                            @elseif (($currentMachineInventory - $projectedCapsuleSales) != $actualCapsuleInventory && $variance > 0) 
                                                over-type
                                            @endif
                                        ">
                                            
                                            @if (($currentMachineInventory - $projectedCapsuleSales) == $actualCapsuleInventory || $variance == 0) 
                                                No Variance
                                            @elseif (($currentMachineInventory - $projectedCapsuleSales) == $actualCapsuleInventory && $variance > 0) 
                                                Short
                                            @elseif (($currentMachineInventory - $projectedCapsuleSales) != $actualCapsuleInventory && $variance > 0) 
                                                Over
                                            @endif
                                        </span>
                                        <input type="hidden" class="variance-status" name="variance_type[]" value="
                                            @if (($currentMachineInventory - $projectedCapsuleSales) == $actualCapsuleInventory || $variance == 0) 
                                                No Variance
                                            @elseif (($currentMachineInventory - $projectedCapsuleSales) == $actualCapsuleInventory && $variance > 0) 
                                                Short
                                            @elseif (($currentMachineInventory - $projectedCapsuleSales) != $actualCapsuleInventory && $variance > 0) 
                                                Over
                                            @endif" readonly>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        
            <div class="form-button" style="margin-top: 15px;" >
                <a class="btn-submit pull-left" href="{{ CRUDBooster::mainpath() }}" style="background:#838383; border: 1px solid #838383">Cancel</a>
                <button type="submit" class="btn-submit pull-right" id="btn-submit">Confirm</button>
            </div>
        @endforeach
    </div>
</form>

    {{-- CHAT --}}
    <div class="chat-button" id="chat-button" style="display: show">
        <i class="fa fa-comment-o" aria-hidden="true" style="font-weight: 700; font-size:18px; margin-right:5px;"></i>
        <span style="font-weight: 600; font-size:18px;">Chat</span>
    </div>

    <div class="chat-container" id="chat-container" style="display: none">
        <div class="top-chat-container">
            <div style="font-size: 18px; font-weight:600">Messages</div>
            <i class="fa fa-times" aria-hidden="true" style="font-size: 18px; cursor: pointer;" id="chat-close"></i>
        </div>
        <div class="body-chat-container">
            @foreach ($collected_tokens as $CTHeader)
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
                                <div style="font-size: 12px; margin-bottom: 2px; color:#6d6a6a;">{{$perMessage->getUser->name}} <span> | Cashier</span></div>
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
                <textarea rows="1" id="auto-resize-textarea" placeholder="Enter your message..."></textarea>
            </div>
            <div class="chat-send">
                <i class="fa fa-paper-plane" aria-hidden="true" style="color:white; font-size:16px;"></i>
            </div>
        </div>
    </div>

@endsection

@push('bottom')
<script>
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

    $('.ActualCapsuleInventory').on('input', function() {
        let actualCapsuleInventory = parseFloat($(this).val()); 
        if (isNaN(actualCapsuleInventory)) actualCapsuleInventory = ''; 
        
        const no_of_token = parseFloat($(this).closest('tr').find('.no_of_token').text());
        const token_collected = parseFloat($(this).closest('tr').find('.tokenCollected').text());
        const defaultVariance = parseFloat($(this).closest('tr').find('.defaultVariance').val()); 

        const currentMachineInventory = parseFloat($(this).closest('tr').find('.currentMachineInventory').text()); 
        const variance = parseFloat($(this).closest('tr').find('.variance').text()); 
        const projectedCapsuleSales = parseFloat($(this).closest('tr').find('.projectedCapsuleSales').text()); 

        let actualCapsuleSales = '';
        if (actualCapsuleInventory !== '') {
            actualCapsuleSales = currentMachineInventory - actualCapsuleInventory; 
        }

        if (variance != 0 && actualCapsuleInventory !== '') {
            $(this).closest('tr').find('.actualCapsuleSales').text(actualCapsuleSales);
            $(this).closest('tr').find('.actualCapsuleSales').val(actualCapsuleSales);
        } else {
            $(this).closest('tr').find('.actualCapsuleSales').text('');
        }

        let statusText = "";
        $(this).closest('tr').find('.variance-status').removeClass('no-variance-type short-type over-type');

        if ((currentMachineInventory - projectedCapsuleSales) == actualCapsuleInventory && variance == 0) {
            statusText = "No Variance";
            $(this).closest('tr').find('.variance-status').addClass('no-variance-type');
        } else if ((currentMachineInventory - projectedCapsuleSales) == actualCapsuleInventory && variance > 0) {
            statusText = "Short";
            $(this).closest('tr').find('.variance').text(defaultVariance);
            $(this).closest('tr').find('.variance-status').addClass('short-type');
            $(this).closest('tr').find('.variance').parent().css({'background': ''});
            $(this).closest('tr').find('.actualCapsuleSales').parent().css({'background': 'lightgreen'});

        } else if ((currentMachineInventory - projectedCapsuleSales) != actualCapsuleInventory && variance > 0) {
            statusText = "Over";
            $(this).closest('tr').find('.variance-status').addClass('over-type');
            
            if (actualCapsuleInventory !== '') {
                const newVariance = Math.abs((actualCapsuleSales * no_of_token) - token_collected);
                $(this).closest('tr').find('.variance').text(newVariance);
                $(this).closest('tr').find('.variance').val(newVariance);
                $(this).closest('tr').find('.variance').parent().css({'background': 'lightgreen'});
                $(this).closest('tr').find('.actualCapsuleSales').parent().css({'background': 'lightgreen'});
            } else if (actualCapsuleInventory == ""){
                $(this).closest('tr').find('.variance').text(defaultVariance);
                $(this).closest('tr').find('.variance').parent().css({'background': ''});
                $(this).closest('tr').find('.actualCapsuleSales').parent().css({'background': ''});

            }
            
        }
        // Set the status text
        $(this).closest('tr').find('.variance-status').text(statusText);
        $(this).closest('tr').find('.variance-status').val(statusText);
    });

</script>
@endpush