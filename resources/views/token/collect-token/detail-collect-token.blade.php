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

    .red{
        background-color: #f8d7da;
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

</style>
@endpush
@section('content')
<div class="panel panel-default form-content" style="overflow: hidden" id="collect_token_details">
    <div class="panel-heading header-title text-center">Collect Token Details</div>
    <div class="content-panel">
        <input type="hidden" name="collectedTokenHeader_id" id="collectedTokenHeader_id" value="{{$collected_tokens->id}}" readonly>
            <div class="inputs-container" style="margin-bottom: 10px;">
                <div class="input-container">
                    <div style="font-weight: 600">Reference Number</div>
                    <input type="text" style="border-radius: 5px;" value="{{$collected_tokens->reference_number}}" disabled>
                </div>

                <div class="input-container">
                    <div style="font-weight: 600" >Date Created</div>
                    <input type="text" style="border-radius: 5px;" value="{{$collected_tokens->created_at}}" disabled>
                </div>
                
            </div>
            <div class="inputs-container" style="margin-bottom: 10px;">
                <div class="input-container">
                    <div style="font-weight: 600">Location</div>
                    <input type="text" style="border-radius: 5px;" value="{{$collected_tokens->getLocation->location_name}}" disabled>
                </div>
                <div class="input-container">
                    <div style="font-weight: 600">Bay</div>
                    <input type="text" style="border-radius: 5px;" value="{{$collected_tokens->getBay->name}}" disabled>
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
                            <th>Capsule Sales</th>
                            <th>Machine Inventory</th>
                            <th>Variance Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($collected_tokens->lines as $perLine)
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
                                    <td><span class="serial_number">{{$perLine->machineSerial->serial_number}}</span></td>
                                    <td><span class="jan#">{{$capsuleLine->getInventoryCapsule->item->digits_code}}</span></td> 
                                    <td><span class="jan#">{{$capsuleLine->getInventoryCapsule->item->item_description}}</span></td> 
                                    <td><span class="no_of_token">{{$perLine->no_of_token}}</span></td>
                                    <td><span class="tokenCollected">{{$perLine->qty}}</span></td>
                                    <td class="@if($perLine->variance != 0) red @endif"><span class="variance">{{$perLine->variance}}</span></td>
                                    <td>
                                        @if(empty($collected_tokens->confirmed_by))
                                            <span class="projectedCapsuleSales">{{$projectedCapsuleSales}}</span>
                                        @elseif(!empty($collected_tokens->confirmed_by))
                                            <span class="projectedCapsuleSales">{{$perLine->actual_capsule_sales}}</span>
                                        @endif
                                    </td>
                                    <td><span class="currentMachineInventory">{{$capsuleLine->qty}}</span></td>
                                    <td>
                                        @if(empty($collected_tokens->confirmed_by))
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

                                        @elseif(!empty($collected_tokens->confirmed_by))
                                            <span class="variance-status
                                                @if ($perLine->variance_type == "No Variance") 
                                                    no-variance-type
                                                @elseif ($perLine->variance_type == "Short") 
                                                    short-type
                                                @elseif ($perLine->variance_type == "Over") 
                                                    over-type
                                                @endif
                                            ">
                                                {{$perLine->variance_type}}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-button panel-footer" style="margin-top: 15px;" >
            <a class="btn-submit pull-left" href="{{ CRUDBooster::mainpath() }}" style="background:#838383; border: 1px solid #838383">Back</a>
        </div>
</div>

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
            <input type="hidden" name="message_collect_token_id" id="message_collect_token_id" value="{{$collected_tokens->id}}" readonly>
            @foreach ($collected_tokens->collectTokenMessages as $perMessage)
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
        $('#customSelectLocation select').on('mousedown', function() {
            $('#customSelectLocation').toggleClass('open');
        });
        $('#customSelectLocation select').on('change', function() {
            $('#customSelectLocation').removeClass('open');
        });
        $('#customSelectLocation select').on('blur', function() {
            $('#customSelectLocation').removeClass('open');
        });

        $('#customSelectBay select').on('mousedown', function() {
            $('#customSelectBay').toggleClass('open');
        });
        $('#customSelectBay select').on('change', function() {
            $('#customSelectBay').removeClass('open');
        });
        $('#customSelectBay select').on('blur', function() {
            $('#customSelectBay').removeClass('open');
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
</script>
@endpush