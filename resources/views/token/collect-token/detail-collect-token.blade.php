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
                    <div style="font-weight: 600">Total Quantity</div>
                    <input type="text" style="border-radius: 5px;" value="{{$collected_tokens->collected_qty}}" disabled>
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
            <div class="inputs-container">
                <div class="input-container">
                    <div style="font-weight: 600" >Created By</div>
                    <input type="text" style="border-radius: 5px;" value="{{$collected_tokens->getCreatedBy->name}}" disabled>
                </div>

                <div class="input-container">
                    <div style="font-weight: 600" >Date Created</div>
                    <input type="text" style="border-radius: 5px;" value="{{$collected_tokens->created_at}}" disabled>
                </div>

            </div>

            @if(!empty($collected_tokens->confirmed_by))
                <div class="inputs-container" style="margin-top: 10px;">
                    <div class="input-container">
                        <div style="font-weight: 600">Confirmed By</div>
                        <input type="text" style="border-radius: 5px;" value="{{$collected_tokens->getConfirmedBy->name}}" disabled>
                    </div>
                    <div class="input-container">
                        <div style="font-weight: 600">Date Confirmed</div>
                        <input type="text" style="border-radius: 5px;" value="{{$collected_tokens->confirmed_at}}" disabled>
                    </div>
                </div>
            @endif
            
            @if(!empty($collected_tokens->approved_by))
                <div class="inputs-container" style="margin-top: 10px;">
                    <div class="input-container">
                        <div style="font-weight: 600">Approved By</div>
                        <input type="text" style="border-radius: 5px;" value="{{$collected_tokens->getApprovedBy->name}}" disabled>
                    </div>
                    <div class="input-container">
                        <div style="font-weight: 600">Date Approved</div>
                        <input type="text" style="border-radius: 5px;" value="{{$collected_tokens->approved_at}}" disabled>
                    </div>
                </div>
            @endif

            @if(!empty($collected_tokens->received_by))
                <div class="inputs-container" style="margin-top: 10px;">
                    <div class="input-container">
                        <div style="font-weight: 600">Received By</div>
                        <input type="text" style="border-radius: 5px;" value="{{$collected_tokens->getReceivedBy->name}}" disabled>
                    </div>
                    <div class="input-container">
                        <div style="font-weight: 600">Date Received</div>
                        <input type="text" style="border-radius: 5px;" value="{{$collected_tokens->received_at}}" disabled>
                    </div>
                </div>
            @endif

            <div class="table-wrapper custom-scroll-x">
                <table>
                    <thead>
                        <tr>
                            <th>Machine #</th>
                            <th>JAN #</th>
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
                                    <td><span class="no_of_token">{{$perLine->no_of_token}}</span></td>
                                    <td><span class="tokenCollected">{{$perLine->qty}}</span></td>
                                    <td><span class="variance">{{$perLine->variance}}</span></td>
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
@endsection

@push('bottom')
<script>
    $('.content-header').hide();
</script>
@endpush