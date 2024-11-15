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

    .form-button .btn-approve {
        padding: 9px 15px;
        background: #15a321;
        border: 1.5px solid #15a321;
        border-radius: 5px;
        color: white;
    }

    .form-button .btn-approve:hover {
        opacity: 0.7;
    }
    
    .form-button .btn-reject {
        padding: 9px 15px;
        background: #da2404;
        border: 1.5px solid #da2404;
        border-radius: 5px;
        color: white;
    }

    .form-button .btn-reject:hover {
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

    .swal2-popup {
        width: 500px !important; /* Set a larger width */
        height: 300px !important;
    }
    .swal2-title {
        font-size: 24px !important; /* Customize the title size */
    }
    .swal2-html-container {
        font-size: 16px !important; /* Customize the text size */
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
</style>
@endpush
@section('content')
<div class="panel panel-default form-content" style="overflow: hidden">
    <form  method="POST" action="{{route('postCollectTokenApproval')}}" id="collect_token_details">
        @csrf
        <div class="panel-heading header-title text-center">Collect Token Details</div>
        <div class="content-panel">
                <input type="hidden" name="collect_token_id" id="collect_token_id" value="{{$collected_tokens->id}}" readonly>
                <input type="hidden" name="action_type" id="action_type" value="">
                <input type="hidden" name="ref_number" value="{{$collected_tokens->reference_number}}" readonly>
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
                            @foreach ($collected_tokens->lines as $perLine)
                                @foreach ($perLine->inventory_capsule_lines as $capsuleLine)
                                    <tr>
                                        <td>
                                            {{$perLine->machineSerial->serial_number}}
                                            <input type="hidden" name="gasha_machines_id[]" value="{{$perLine->machineSerial->id}}" readonly>
                                            <input type="hidden" name="location_id[]" value="{{$perLine->machineSerial->location_id}}" readonly>
                                        </td>
                                        <td>
                                            {{$capsuleLine->getInventoryCapsule->item->digits_code}}
                                            <input type="hidden" name="jan_code[]" id="jan_code" value="{{$capsuleLine->getInventoryCapsule->item->digits_code}}" readonly>
                                        </td> 
                                        <td>{{$perLine->no_of_token}}</td>
                                        <td>{{$perLine->qty}}</td>
                                        <td>{{$perLine->variance}}</td>
                                        <td>{{$perLine->projected_capsule_sales}}</td>
                                        <td>
                                            {{$perLine->current_capsule_inventory}}
                                            <input type="hidden" name="inventory_capsule_lines_id[]" value="{{$capsuleLine->id}}" readonly>
                                        </td>
                                        <td>
                                            {{$perLine->actual_capsule_inventory}}
                                            <input type="hidden" name="actual_capsule_inventory[]" value="{{$perLine->actual_capsule_inventory}}" readonly>
                                        </td>
                                        <td>
                                            {{$perLine->actual_capsule_sales}}
                                            <input type="hidden" name="actual_capsule_sales[]" value="{{$perLine->actual_capsule_sales}}">
                                        </td>
                                        <td>
                                            <span class="variance-status
                                                @if (($perLine->variance_type) == "No Variance") 
                                                    no-variance-type
                                                @elseif (($perLine->variance_type) == "Short") 
                                                    short-type
                                                @elseif (($perLine->variance_type) == "Over") 
                                                    over-type
                                                @endif
                                            ">
                                                
                                                @if (($perLine->variance_type) == "No Variance") 
                                                    No Variance
                                                @elseif (($perLine->variance_type) == "Short") 
                                                    Short
                                                @elseif (($perLine->variance_type) == "Over")
                                                    Over
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
    </form>
    <div class="form-button panel-footer" style="margin-top: 15px;" >
        <a class="btn-submit pull-left" href="{{ CRUDBooster::mainpath() }}" style="background:#838383; border: 1px solid #838383">Back</a>
        <button class="btn-approve pull-right" id="btn-approve"><i class="fa fa-thumbs-up" aria-hidden="true"></i> Approve</button>
        <button class="btn-reject pull-right" id="btn-reject"  style="margin-right: 5px;"><i class="fa fa-thumbs-down" aria-hidden="true"></i> Reject</button>
    </div>

</div>
@endsection

@push('bottom')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
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
    
    $('#btn-approve').on('click', function () {
        $('#action_type').val('approve');
        Swal.fire({
            title: "Are you sure you want to Approve?",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#15a321',
            cancelButtonColor: '#838383',
            confirmButtonText: 'Approve',
            iconHtml: '<i class="fa fa-thumbs-up"></i>',
            iconColor: '#15a321',
            returnFocus: false,
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#collect_token_details').submit();
            }
        });
    });
    
    $('#btn-reject').on('click', function () {
        $('#action_type').val('reject');
        Swal.fire({
            title: "Are you sure you want to Reject?",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#da2404',
            cancelButtonColor: '#838383',
            confirmButtonText: 'Reject',
            iconHtml: '<i class="fa fa-thumbs-down"></i>',
            iconColor: '#da2404',
            returnFocus: false,
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#collect_token_details').submit(); 
            }
        });
    });
</script>
@endpush