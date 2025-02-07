@extends('crudbooster::admin_template')
@push('head')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>
<style>
    .print-details{
        display: flex;
        margin-left: 20px;
        margin-right: 20px;
        justify-content: space-between;
    }

    @page { 
        size: letter;
        margin-left: 0in;
        margin-right: 0in;
        margin-top: 0.5in; 
        margin-bottom: 1in;
    }

    @page :header {
        color: white;
        display: none;
    }

    @page :footer {
        color: white;
        display: none;
    }

    .remarks{
        display: flex;
        margin: 20px 20px;
        flex-direction: column;
    }

    .signatures{
        display: flex;
        margin: 20px 20px;
        justify-content: space-between;
    }

    .signature-names{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-items: center;
        width: 200px;
        border-top: 1px solid black;
    }

    .line {
        border-bottom: 1px solid #000000;
        width: 100%;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .container {
        display: flex;
        /* flex-wrap: wrap; */
        flex-direction: column;
    }

    

    .bay {
        /* flex: 0 0 calc(33.333% - 10px); */
        flex: 1;
        text-align: center;
        box-sizing: border-box;
        margin: 1px;
        height: 800px;
    }

    .container .bay:first-child table {
        height: 700px;
    }
    .container .bay:last-child table {
        height: 900px;
    }

    .container .bay table {
        height: 800px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    h3 {
       margin: 0 0 10px;
    }

    th, td {
        border: 1px solid #000;
        padding: 3px;
        text-align: center;
    }

    th {
        background-color: #f0f0f0;
    }

    /* FOR PRINTING */
    .print-data {
        padding: 0em;
        border: 0;
        border-width: 0;
    }

    .header-title {
        background: #3C8DBC !important;
        color: #fff !important;
        font-size: 16px;
        font-weight: 500;
        text-align: center;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }

    .content-panel {
        padding: 15px;
    }

    .inputs-container {
        display: flex;
        gap: 10px;
    }

    .input-container {
        display: flex;
        flex: 1;
        flex-direction: column;
    }

    /* SELECT 2 */

    .select2-container--default .select2-selection--multiple {
        border-color: #3498db !important;
        border-radius: 7px;
        padding: 6px 0 8px 10px;
        
    }

    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection__choice {
        background-color: #3498db !important;
        color: #ffffff !important;
        border: 1px solid #2980b9 !important;
    }

    .select2-container--default .select2-selection__choice:hover {
        background-color: #2980b9 !important;
        color: #ffffff !important;

    }


    .form-button .btn-submit {
        padding: 9px 20px;
        background: #3C8DBC;
        border: 1.5px solid #1d699c;
        border-radius: 10px;
        color: white;
    }

    .form-button .btn-submit:hover {
        opacity: 0.7;
    }


    /* FILTER */

    .form-content {
        display: flex;
        background: #fff;
        flex-direction: column;
        font-family: 'Poppins', sans-serif !important;
        border-radius: 5px;
    }

    input[type="text"] {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        border: 1px solid #3C8DBC; 
        outline-color: #3C8DBC
    }
    input[type="date"] {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        border: 1px solid #3C8DBC; 
        outline-color: #3C8DBC
    }

    input[type="text"]:disabled {
        background-color: #F3F3F3;
    }

    .spinner-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .spinner {
        width: 55px;
        height: 55px;
        border: 10px solid rgba(43, 253, 253, 0.312);
        border-left-color: #3C8DBC;
        border-radius: 50%;
        animation: spin 0.5s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 840px) {
        .inputs-container{
            display: flex;
            flex-direction: column;

        }
    }

      /* CUSTOM SELECT */

      .custom-select {
        position: relative;
        width: 100%;
    }

    select {
        width: 100%;
        padding: 9px;
        font-size: 14px;
        border: 1px solid #3C8DBC;
        border-radius: 4px;
        appearance: none;
        background-color: #fff;
        outline: none;
    }

    .custom-select::after {
        content: '▼';
        font-size: 12px;
        color: #3C8DBC;
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        transition: transform 0.3s ease;
    }

    .custom-select.open::after {
        transform: translateY(-50%) rotate(180deg);
    }

    select::-ms-expand {
        display: none;
    }

    option[disabled] {
        color: #bbb;
    }

    .swal2-popup {
        width: 500px !important; 
        height: 80% !important;
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
        font-size: 16px !important; 
        width: 80px !important;
        height: 80px !important;
    }

    table {
        
    
        
    }

    th {
        background-color: #3C8DBC;
        color: white;
        font-weight: bold;
    }

    .invisible-text {
        color: transparent !important;
        user-select: none !important;
    }

    @media print {
        body {
            color-adjust: exact;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .tcr-header {
            background-color: #fefd01 !important;
            color: #000000 !important;
        }

        .invisible-text {
            color: transparent !important;
        }

     

       
    }

    
</style>
@endpush
@section('content')

<form class="panel panel-default form-content no-print" style="overflow: hidden">
    <div class="panel-heading header-title">Token Collection Form - Filter</div>
    <div class="content-panel">
        <div class="inputs-container" >
            <div class="input-container">
                <div style="font-weight: 600">Store Name </div>
                <input type="text" style="border-radius: 5px;" id="location_name" value="{{$store_name->location_name ?? 'No Location Selected'}}" readonly>
            </div>

            <div class="input-container">
                <div style="font-weight: 600">Date <small id="date_required" style="display: none; color: rgba(255, 0, 0, 0.853);"> <i class="fa fa-exclamation-circle"></i> Required field! </small></div>
                <input id="date" type="date" name="date" style="border-radius: 5px;"  >
            </div>
            
        </div>

        <div style="margin-top: 10px;"><b style="color: red">Note:</b> Please make sure to <b style="color: red">COLLECT TOKENS</b> from <b style="color: red">ALL BAYS</b> before printing the form.</div>


    </div>
    <div class='panel-footer no-print'>
        <div class="form-button pull-left" style=" margin-top:10px;" >
            <a href="{{ CRUDBooster::mainpath() }}" id="btn-cancel" class="btn-submit" style="padding: 10px 20px; background:#838383; border: 1px solid #838383; margin-right: 5px;">Back</a>
        </div>
        <div class="pull-right"  style=" display:flex">
            @php
               $formatted_date_generated = date('Y-m-d', strtotime($report_generated_at));
               $formatted_date_now = date('Y-m-d', strtotime(now()));
            @endphp

            <div class="form-button" >
                <button class="btn-submit"  id="btn-reset" style="background:#e73131; border: 1px solid #d34040; margin-right: 5px;">Reset</button>
            </div>
            <div class="form-button" >
                <button class="btn-submit" id="btn-submit" style="margin-right:5px;display:none;">Generate Token Collection Form</button>
            </div>
            <div class="form-button" >
                @if($formatted_date_generated != $formatted_date_now)
                    <button class="btn-submit" id="btn-collect-token-report"  style="background:#208013; border: 1px solid #208013; display: show">Generate Token Collection Report</button>
                @endIf
            </div>
        </div>
    </div>
</form>

{{-- TOKEN COLLECTION FORM --}}

<div class='panel panel-default print-data' id="print-form" style="display:none">
    <div class='panel-body' id="print-section">
        <h4 class="text-center" style="margin:30px 0;"><b>TOKEN COLLECTION FORM</b></h4>
        <div class="print-details" id="print-details">
           {{-- APPEND DETAILS HERE --}}
        </div>
        <div class="container" id="container">
            {{-- APPEND TABLE HERE --}}
        </div>

        <div style=" margin: 20px;">
            <table style="width: 100%; table-layout: fixed; height:35px;">
                <tr >
                    <td style="padding:5px;" colspan="2"><b>Store: </b><span id="total-tokens-store"></span></td>
                    <td style="padding:5px;" colspan="2"><b>Date: </b><span id="total-tokens-date"></span></td>
                </tr>
                <tr >
                    <td style="padding:5px;" colspan="2"><b>Total Token Collected</b></td>
                    <td id="total-tokens" colspan="2"></td>
                </tr>
            </table>
        </div>
        
        <div class="remarks">
            <h5><b>Remarks: </b></h5>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>


        <div class="signatures">
            <h5><b>Collected by: </b></h5>
            <h5 style="margin-right: 125px;"><b>Received by: </b></h5>
        </div>
        <div class="signatures">
            <div class="collected-by" id="collected-by" style="display:flex; flex-direction:column; gap: 20px;">
                {{-- APPEND COLLECTORS HERE --}}
            </div>
            <div class="received-by" id="received-by">
                {{-- APPEND RECEIVER HERE --}}
            </div>
        </div>


    </div>

    <div class='panel-footer no-print'>
        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default no-print">{{ trans('message.form.back') }}</a>
        <div class="btn btn-info no-print pull-right" id="print-button" style="background-color: #3C8DBC;">Print</div>
    </div>
</div>

{{-- TOKEN COLLECTION REPORT --}}

<div class='panel panel-default print-data' id="token-collection-report-form" style="display:none">
    <div class='panel-body' id="print-section" style="padding: 10px;">
        <h4 class="text-center" style="margin:15px 0;"><b>TOKEN COLLECTION REPORT</b></h4>
        <div class="print-details" id="print-details">
            <h5><b>Store Name: </b><span id="collection-report-store">GASHAPON STORE</span></h5>
            <h5><b>Date: </b><span id="collection-report-date">2025-01-04</span></h5>
         </div>
        <div class="print-details" id="token-collect-form" style="display: flex; flex-direction: column; gap: 10px; justify-content: center;">
            <table style=" margin-right: 20px;">
                <thead class="tcr-header" style="background-color: #fefd01">
                    <tr style="font-size: 10px;">
                        <td colspan="1" style="padding: 10px;"><b>BAY</b></td>
                        <td colspan="1" style="margin: 10px;"><b>FROM</b></td>
                        <td colspan="1"><b>TO</b></td>
                        <td colspan="1"><b>COLLECTED BY</b></td>
                        <td colspan="1"><b>TOKENS COLLECTED</b></td>
                        <td colspan="1"><b>TOKENS RECEIVED BY THE CASHIER</b></td>
                        <td colspan="1"><b>VARIANCE</b></td>
                    </tr>
                </thead>
                <tbody id="collection-report-tbody">
                        {{-- APPEND HERE --}}

                </tbody>
                <tr style="font-size: 12px;">
                    <td colspan="4" style=" padding: 5px;"><b>TOTAL TOKENS COLLECTED</b></td>
                    <td colspan="2" style="border-right: 0px; font-weight:700;" id="totaltokens-report-table"></td>
                    <td style="border-left: 0px;"></td>
                </tr>
                </table>

            
            <div style="margin-top: 5px; display: flex; flex-direction: column; gap: 0px; font-size: 10px;">
                <div style="display: flex; flex-direction: row; align-items: center">    
                    <div style="width: 40%"><b>TOKEN SWAP FROM CASHIER REPORT:</b></div> 
                    <div style="width: 30%"><b>DATE:</b> <span style="align-items: center;" id="tswap-from-cashier-report-date"></span></div> 
                    <div style="width: 20%; display: flex; align-items: center; "><b>QTY:</b> 
                        <div style=" margin-left: 5px; border: 0.5px solid black; font-size: 14px; width: 100px; height: 25px; justify-content: center; display:flex; align-items: center" id="tswap-from-cashier-report-qty"></div>
                    </div>
                </div>
                <div style="display: flex; flex-direction: row; align-items: center; margin-top: 1px;">    
                    <div style="width: 40%"><b>MINUS: TOKEN COLLECTED TODAY</b></div> 
                    <div style="width: 30%"><b>DATE:</b> <span style="align-items: center;" id="token-collected-today-date"></span></div> 
                    <div style="width: 20%; display: flex; align-items: center;"><b>QTY:</b> 
                        <div style=" margin-left: 5px; border: 0.5px solid black; font-size: 14px; width: 100px; height: 25px; justify-content: center; display:flex; align-items: center" id="token-collected-today-qty"></div>
                    </div>
                </div>
                <div style="display: flex; flex-direction: row; align-items: center; margin-top: 1px;">    
                    <div style="width: 40%"><b>VARIANCE:</b></div> 
                    <div style="width: 30%"></div> 
                    <div style="width: 20%; display: flex; align-items: center;"><b class="invisible-text" style="color: transparent">QTY:</b> 
                        <div style=" margin-left: 5px; border: 0.5px solid black; font-size: 14px; width: 100px; height: 25px; justify-content: center; display:flex; align-items: center" id="variance1-qty"></div>
                    </div>
                </div>
            </div>

            <div style="margin-top: 5px; display: flex; flex-direction: column; font-size: 10px;">
                <div style="display: flex; flex-direction: row; align-items: center">    
                    <div style="width: 40%"><b>TOTAL TOKEN BEG. BALANCE:</b></div> 
                    <div style="width: 30%"><b>DATE:</b> <span style="align-items: center;" id="total-beggining-balance-date"></span></div> 
                    <div style="width: 20%; display: flex; align-items: center;"><b class="invisible-text" style="color: transparent">QTY:</b> 
                        <input type="text" id="system_beginning_balance" style="margin-left: 5px; border: 0.5px solid black; font-size: 14px; width: 100px; height: 25px; justify-content: center; display:flex; align-items: center; text-align:center" readonly>
                    </div>
                </div>
                <div style="display: flex; flex-direction: row; align-items: center; margin-top: 1px;">    
                    <div style="width: 40%"><b>CASHIER DRAWER BALANCE:</b></div> 
                    <div style="width: 30%; display: flex; align-items: center;"><b>QTY:</b> 
                        <input type="text" id="cashier_drawer_balance" value="1000" style="margin-left: 5px; border: 0.5px solid black; font-size: 14px; width: 100px; height: 25px; justify-content: center; display:flex; align-items: center; text-align:center" readonly>
                    </div>
                </div>
                <div style="display: flex; flex-direction: row; align-items: center; margin-top: 1px;">    
                    <div style="width: 40%"><b>TOTAL SEALED TOKENS:</b></div> 
                    <div style="width: 30%; display: flex; align-items: center;"><b>QTY:</b> 
                        <input type="text" id="sealed_tokens" value="1100" style="margin-left: 5px; border: 0.5px solid black; font-size: 14px; width: 100px; height: 25px; justify-content: center; display:flex; align-items: center; text-align:center" readonly>
                    </div>
                </div>
                <div style="display: flex; flex-direction: row; align-items: center; margin-top: 1px;">    
                    <div style="width: 40%"><b>TOTAL TOKEN COLLECTED:</b></div> 
                    <div style="width: 30%; display: flex; align-items: center;"><b>QTY:</b> 
                        <input type="text" id="total-token-collected2" style="margin-left: 5px; border: 0.5px solid black; font-size: 14px; width: 100px; height: 25px; justify-content: center; display:flex; align-items: center; text-align:center" readonly>
                    </div>
                </div>
                <div style="display: flex; flex-direction: row; align-items: center; margin-top: 1px;">    
                    <div style="width: 40%"><b>TOTAL TOKEN ON HAND:</b></div> 
                    <div style="width: 30%"><b>DATE:</b> <span style="align-items: center;" id="total-token-onhand-date"></span></div> 
                    <div style="width: 20%; display: flex; align-items: center;"><b>QTY:</b> 
                        <input type="text" id="total_token_on_hand" style="margin-left: 5px; border: 0.5px solid black; font-size: 14px; width: 100px; height: 25px; justify-content: center; display:flex; align-items: center; text-align:center" readonly>
                    </div>
                </div>
                <div style="display: flex; flex-direction: row; align-items: center; margin-top: 1px;">    
                    <div style="width: 40%"><b>VARIANCE:</b></div> 
                    <div style="width: 30%"></div> 
                    <div style="width: 20%; display: flex; align-items: center;"><b class="invisible-text" style="color: transparent">QTY:</b> 
                        <input type="text" id="variance_beginning_x_on_hand" style="margin-left: 5px; border: 0.5px solid black; font-size: 14px; width: 100px; height: 25px; justify-content: center; display:flex; align-items: center; text-align:center" readonly>
                    </div>
                </div>
            </div>

            <div style="margin-top: 5px; display: flex; flex-direction: column; font-size: 10px;">
                <div style="display: flex; flex-direction: row; align-items: center; margin-top: 1px;">    
                    <div style="width: 40%"><b>TOTAL TOKENS DELIVERED:</b></div> 
                    <div style="width: 30%"></div> 
                    <div style="width: 20%; display: flex; align-items: center;"><b class="invisible-text" style="color: transparent">QTY:</b> 
                        <input type="text" id="total-tokens-delivered" style="margin-left: 5px; border: 0.5px solid black; font-size: 14px; width: 100px; height: 25px; justify-content: center; display:flex; align-items: center; text-align:center" readonly>
                    </div>
                </div>
                <div style="display: flex; flex-direction: row; align-items: center; margin-top: 1px;">    
                    <div style="width: 40%"><b>MINUS: TOTAL TOKEN ON HAND</b></div> 
                    <div style="width: 30%"></div> 
                    <div style="width: 20%; display: flex; align-items: center;"><b class="invisible-text" style="color: transparent">QTY:</b> 
                        <input type="text" id="minus_total_token_on_hand" style="margin-left: 5px; border: 0.5px solid black; font-size: 14px; width: 100px; height: 25px; justify-content: center; display:flex; align-items: center; text-align:center" readonly>
                    </div>
                </div>
                <div style="display: flex; flex-direction: row; align-items: center; margin-top: 1px;">    
                    <div style="width: 40%"><b>VARIANCE:</b></div> 
                    <div style="width: 30%"></div> 
                    <div style="width: 20%; display: flex; align-items: center;"><b class="invisible-text" style="color: transparent">QTY:</b> 
                        <input type="text" id="minus_variance_delivered_x_on_hand" style="margin-left: 5px; border: 0.5px solid black; font-size: 14px; width: 100px; height: 25px; justify-content: center; display:flex; align-items: center; text-align:center" readonly>
                    </div>
                </div>
            
            </div>

            <div style="margin-top: 5px; display: flex; flex-direction: column; font-size: 10px;">
                <div style=" font-size: 12px;"><b>ISSUES FOUND: IF POSSIBLE, SUPPORTED BY A PICTURE OR ANY PROOF,</b></div> 
                <div>OTHERS: SPECIFY ISSUES ENCOUNTERED DURING THE TOKEN COLLECTION & TURN OVER:</div> 
                <div class="line" style="margin-top: 15px;"></div>
                <div class="line"></div>
                <div class="line"></div>
              
            </div>

            <div style="margin-top: 5px; display: flex; flex-direction: row; font-size: 10px;">
                <div style=" font-size: 12px; width: 50%">
                    <b>TEAM LEADER:</b>
                    <div style="margin-top: 10px; border-bottom: 1px solid black;  width: 300px;"></div>
                    <i style="font-size: 9px;">Signature over Printed Full Name</i>
                </div> 
                <div style=" font-size: 12px; width: 50%">
                    <b>CASHIER:</b>
                    <div style="margin-top: 10px; border-bottom: 1px solid black;  width: 300px;"></div>
                    <i style="font-size: 9px;">Signature over Printed Full Name</i>
                </div> 
            </div>
        
        </div>
        
    </div>

    <div class='panel-footer no-print'>
        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default no-print">{{ trans('message.form.back') }}</a>
        <div class="btn btn-info no-print pull-right" id="print-button-clt-report" style="background-color: #3C8DBC;">Print</div>
    </div>
</div>

<div class="spinner-overlay" id="spinner" style="display: none;">
    <div class="spinner">
    </div>
</div>


@endsection

@push('bottom')

<script>
    $('.content-header').hide();
    $(document).ready(function () {

        //disabled past dates
        // const today = new Date().toISOString().split('T')[0];
        // document.getElementById('date').setAttribute('min', today);

        @if($formatted_date_generated === $formatted_date_now)
            $('#btn-submit').show();
        @endIf

        let isSwalOpen = false;

        // Trigger the print action for both buttons
        $('#print-button').on('click', function(){
            isSwalOpen = false;
            window.print();
        });

        $('#print-button-clt-report').on('click', function() {
            isSwalOpen = true;
            window.print();
        });

        // Handle what happens after the print process
        window.onafterprint = function() {
            if (isSwalOpen) {
                Swal.fire({
                    icon: 'question',
                    title: 'Was the document printed?',
                    html: `If the form is printed, please confirm it by clicking <br> "Yes, it was printed". 
                            Then if it is not printed yet, please click "Not yet, print again" <b>NOTE:</b> 
                            this print works only once within the day, <br> please make sure that it is printed 
                            before confirming it is printed already. Thank You.`,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, it was printed',
                    cancelButtonText: 'Not yet, print again',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false
                }).then((result) => {
                    isSwalOpen = false;  

                    if (!result.isConfirmed) { 
                        setTimeout(() => {
                            $('#print-button-clt-report').trigger('click');
                        }, 500);
                    } else {
                        $('#spinner').show(); 

                        // Collect data to send in the request
                        const store_name = $('#location_name').val();
                        const beginning_bal = $('#system_beginning_balance').val();
                        const token_on_hand = $('#total_token_on_hand').val();
                        const variance = $('#variance_beginning_x_on_hand').val();
                        const generated_date = $('#date').val();

                        $.ajax({
                            url: '{{ route('postPrintedReport') }}',
                            method: 'POST',
                            data: {
                                store_name: store_name,
                                beginning_bal: beginning_bal,
                                token_on_hand: token_on_hand,
                                variance: variance,
                                generated_date: generated_date,
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                $('#spinner').hide();
                                $('#btn-submit').show();
                                $('#btn-collect-token-report').hide();
                                
                                // Success response handling
                                if (response.success) {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Thank You!",
                                        text: `${response.success}`,
                                        showCloseButton: false,
                                        confirmButtonText: `<i class="fa fa-thumbs-up"></i> Got it!`,
                                        confirmButtonColor: '#3C8DBC',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        allowEnterKey: false,
                                        timer: 3000, 
                                        timerProgressBar: true,
                                        didOpen: () => {
                                            Swal.showLoading(); 
                                        },
                                        willClose: () => {
                                            $('#btn-submit').show();
                                            $('#btn-submit').trigger('click');
                                            $('#token-collection-report-form').hide();
                                        }
                                    });
                                } else if(response.already_generated){
                                    Swal.fire({
                                        icon: "error",
                                        title: "Sorry!",
                                        text: `${response.already_generated}`,
                                        showCloseButton: false,
                                        confirmButtonText: `<i class="fa fa-thumbs-up"></i> Got it!`,
                                        confirmButtonColor: '#3C8DBC',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        allowEnterKey: false
                                    });
                                } else {
                                    // Error response handling
                                    Swal.fire({
                                        icon: "error",
                                        title: "Failed!",
                                        text: `${response.error}`,
                                        showCloseButton: false,
                                        confirmButtonText: `<i class="fa fa-thumbs-up"></i> Got it!`,
                                        confirmButtonColor: '#3C8DBC',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        allowEnterKey: false
                                    });
                                }
                            }
                        });
                    }
                });
            }
        };

        // Prevent page reload
        window.onbeforeunload = function(event) {
            if (isSwalOpen) {
                event.preventDefault();
                event.returnValue = '';
            } else  {
                event.preventDefault();
                event.returnValue = '';
            }
        };

        // Prevent right-click
        window.addEventListener('contextmenu', function(event) {
            event.preventDefault();
        });

    });

    $('#btn-reset').on('click', function(event){
        event.preventDefault();
        $('#date').val(null).trigger('change');
        $('#print-form').hide();
        $('#token-collection-report-form').hide();
        $('#date_required').hide();

    });

    $('#btn-submit').on('click', function(event) {
        event.preventDefault();
        let date = $('#date').val();


        if (date === null || date.length === 0) {
            $('#date_required').show();
            return;
        }
        else{
            $('#date_required').hide();
        }

        $('#spinner').show();

        $.ajax({
            url: '{{route("postPrint")}}',
            method: 'POST',
            data: {
                date: date,
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                if (response.missing_bays.length != 0){
                    $('#spinner').hide();
                    Swal.fire({
                        icon: "error",
                        title: "<strong class='text-warning'> Unable to Filter, </br> There are missing Bays</strong>",
                        showCloseButton: false,
                        confirmButtonText: `<i class="fa fa-thumbs-up"></i> Got it!`,
                        confirmButtonColor: '#3C8DBC',
                        html: `
                            <table style="font-size:12px; width: 100%; border-collapse: collapse; margin: 20px 0;">
                                <thead>
                                    <tr>
                                        <th style=" border: 1px solid #3C8DBC; padding: 12px; text-align: center;">Location</th>
                                        <th style=" border: 1px solid #3C8DBC; padding: 12px; text-align: center;">Bay</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style=" border: 1px solid #3C8DBC; padding: 12px; text-align: center;" rowspan="${response.missing_bays.length + 1}">${response.store_name}</td>
                                    </tr>
                                    ${response.missing_bays.map(item => `<tr><td style=" border: 1px solid #3C8DBC; padding: 12px; text-align: center;">${item.name}</td></tr>`).join('')}
                                </tbody>
                            </table>
                        `
                    });
                    return;
                }

                $('#token-collection-report-form').hide();
                $('#print-form').show();
                const container = $('#container');
                const printDetails = $('#print-details');
                const receivedBy = $('#received-by');
                const collectedBy = $('#collected-by');
                const totalTokens = $('#total-tokens');
                const totalTokensStore = $('#total-tokens-store');
                const totalTokensDate = $('#total-tokens-date');

                container.empty();
                printDetails.empty();
                receivedBy.empty();
                collectedBy.empty();
                totalTokens.empty();
                totalTokensStore.empty();
                totalTokensDate.empty();

                let details = `
                    <h5><b>Store Name: </b><span>${response.store_name}</span></h5>
                    <h5><b>Date: </b><span>${response.date}</span></h5>
                `;
                
                let receiver = `
                    <div class="signature-names">
                        <div style="margin-top:5px">${response.receiver.name}</div>
                        <div><b>${response.receiver.get_privilege.name}</b></div>
                    </div>
                `;

                let total_tokens = `${response.total_tokens}`
    
                printDetails.append(details);
                receivedBy.append(receiver);
                totalTokens.append(total_tokens);
                totalTokensStore.append(response.store_name);
                totalTokensDate.append(response.formatted_request_date);

                response.collectors.forEach(collector => {
                    let collectors = `
                        <div class="signature-names">
                            <div style="margin-top:5px">${collector.name}</div>
                            <div><b>${collector.privilege}</b></div>
                        </div>
                    `;
                    collectedBy.append(collectors);
                });

                response.collect_tokens.forEach(item => {  
                    
                    let bayTable = `
                        <div class="bay">
                            <table>
                                <thead>
                                    <tr>
                                        <td colspan="4" style="padding: 0;">
                                            <div style="display:flex; justify-content: center;">
                                                <p style="flex: 1; padding: 3px; margin: 0; border-right: 1px solid black;"><b>${item.get_bay.name}</b></p>
                                                <p style="flex: 1; padding: 3px; margin: 0; border-right: 1px solid black;"><b>Reference #: </b>${item.reference_number}</p>
                                                <p style="flex: 1; padding: 3px; margin: 0;"><b>Date: </b>${response.date}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" style="padding: 0;">
                                            <div style="display:flex; justify-content: center;">
                                                <p style="flex: 1; padding: 3px; margin: 0; border-right: 1px solid black;"><b>Collector: </b>${item.get_created_by.name}</p>
                                                <p style="flex: 1; padding: 3px; margin: 0;"><b>Receiver: </b>${item.get_received_by.name}</p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Machine #</b></td>
                                        <td><b>JAN #</b></td>
                                        <td><b>Item Description</b></td>
                                        <td><b>Qty</b></td>
                                    </tr>
                                </thead>
                                <tbody class="table-tbody" style="${item.lines.length > 30 ? 'font-size:10px !important;' : item.lines.length > 20 ? 'font-size:12px !important;' : ''}">
                                    ${item.lines
                                        .filter(machine => machine.jan_number || machine.inventory_capsule_lines[0]?.get_inventory_capsule.item.digits_code)
                                        .map(machine => `
                                            <tr>
                                                <td>${machine.machine_serial.serial_number}</td>
                                                <td>${machine.jan_number ?? machine.inventory_capsule_lines[0]?.get_inventory_capsule.item.digits_code}</td>
                                                <td>${machine.item_description ?? machine.inventory_capsule_lines[0]?.get_inventory_capsule.item.item_description}</td>
                                                <td>${machine.qty}</td>
                                            </tr>
                                        `).join('')}
                                    <tr style="height: 35px;">
                                        <td colspan="3"><b>Total</b></td>
                                        <td><b>${item.lines.reduce((sum, machine) => sum + machine.qty, 0)}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    `;
                    container.append(bayTable);
                   
                });
    
                $('#spinner').hide();
            },
            error: function(xhr, status, error) {
                alert('Error fetching data:', error);
                $('#spinner').hide();
            }
        });
    });

    $('#btn-collect-token-report').on('click', function(event) {
        event.preventDefault();
        let date = $('#date').val();

        if (date === null || date.length === 0) {
            $('#date_required').show();
            return;
        }
        else{
            $('#date_required').hide();
        }

        $('#spinner').show();
        
        $.ajax({
            url: '{{route("postPrint")}}',
            method: 'POST',
            data: {
                date: date,
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                
                if (response.missing_bays.length != 0){
                    $('#spinner').hide();
                    Swal.fire({
                        icon: "error",
                        title: "<strong class='text-warning'> Unable to Filter, </br> There are missing Bays</strong>",
                        showCloseButton: false,
                        confirmButtonText: `<i class="fa fa-thumbs-up"></i> Got it!`,
                        confirmButtonColor: '#3C8DBC',
                        html: `
                                <table style="font-size:12px; width: 100%; border-collapse: collapse; margin: 20px 0;">
                                    <thead>
                                        <tr>
                                            <th style=" border: 1px solid #3C8DBC; padding: 12px; text-align: center;">Location</th>
                                            <th style=" border: 1px solid #3C8DBC; padding: 12px; text-align: center;">Bay</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style=" border: 1px solid #3C8DBC; padding: 12px; text-align: center;" rowspan="${response.missing_bays.length + 1}">${response.store_name}</td>
                                        </tr>
                                        ${response.missing_bays.map(item => `<tr><td style=" border: 1px solid #3C8DBC; padding: 12px; text-align: center;">${item.name}</td></tr>`).join('')}
                                    </tbody>
                                </table>
                            `
                    });
                    return;   
                }
                
                $('#print-form').hide();
                $('#token-collection-report-form').show();

                // DETAILS AND TABLE
                const collectionReportDate = $('#collection-report-date');
                const collectionReportStore = $('#collection-report-store');
                const collectionReportTbody = $('#collection-report-tbody');
                const totalTokensReportTable = $('#totaltokens-report-table');
                collectionReportDate.empty();
                collectionReportStore.empty();
                collectionReportTbody.empty();
                totalTokensReportTable.empty();
                collectionReportDate.append(response.formatted_request_date);
                collectionReportStore.append(response.store_name);

                // OTHER DETAILS
                
                // TOKEN SWAP FROM CASHIER REPORT
                const tswapCashierReportDate = $('#tswap-from-cashier-report-date');
                const tswapCashierReportQty = $('#tswap-from-cashier-report-qty');
                tswapCashierReportDate.empty();
                tswapCashierReportQty.empty();
                tswapCashierReportDate.append(response.token_swap_from_cashier_report_date);
                tswapCashierReportQty.append(response.token_swap_from_cashier_report);

                // MINUS: TOKEN COLLECTED TODAY
                const minusTokenCollectedTodayDate = $('#token-collected-today-date');
                const minusTokenCollectedTodayQty = $('#token-collected-today-qty');
                minusTokenCollectedTodayDate.empty();
                minusTokenCollectedTodayQty.empty();
                minusTokenCollectedTodayDate.append(response.formatted_request_date);
                minusTokenCollectedTodayQty.append(response.total_tokens);

                // VARIANCE
                const variance1Qty = $('#variance1-qty');
                variance1Qty.empty();
                variance1Qty.append(Math.abs(response.token_swap_from_cashier_report - response.total_tokens));
                
                // TOTAL BEGINNING BALANCE DATE
                const totalBegginingBalanceDate = $('#total-beggining-balance-date');
                totalBegginingBalanceDate.empty();
                totalBegginingBalanceDate.append(response.token_swap_from_cashier_report_date);

                // TOTAL BEGINNING BALANCE
                const beginning_balance = $('#system_beginning_balance');
                beginning_balance.empty().val(response.token_invetory);

                // CASHIER DRAWER BALANCE
                const drawer_bal = $('#cashier_drawer_balance');
                drawer_bal.empty().val(response.token_drawer_sod);

                // SEALED TOKENS
                const sealed_tokens = $('#sealed_tokens');
                sealed_tokens.empty().val(response.token_sealed_sod);

                // TOTAL TOKEN COLLECTED
                const totalTokenCollected2 = $('#total-token-collected2');
                totalTokenCollected2.empty().val(response.total_tokens);

                // TOTAL TOKEN ON HAND DATE
                const totalTokenOnHandDate = $('#total-token-onhand-date');
                totalTokenOnHandDate.empty();
                totalTokenOnHandDate.append(response.formatted_request_date);

                // TOTAL TOKENS DELIVERED
                const totalTokensDelivered = $('#total-tokens-delivered');
                totalTokensDelivered.empty().val(response.total_tokens_delivered);

                $(document).ready(function() {
                    const beginningBalance = parseFloat($('#system_beginning_balance').val()) || 0;
                    const cashierDrawerBalance = parseFloat($('#cashier_drawer_balance').val()) || 0;
                    const sealedTokens = parseFloat($('#sealed_tokens').val()) || 0;
                    const tokenCollected = parseFloat($('#total-token-collected2').val()) || 0;
                    const tokenDelivered = parseFloat($('#total-tokens-delivered').val()) || 0;
                
                    // calculation
                    const totalOnHandValue = cashierDrawerBalance + sealedTokens + tokenCollected;
                    const minusVariance = tokenDelivered - totalOnHandValue; 
                    const beginnginXonHandVariance = beginningBalance - totalOnHandValue;

                    // set values results
                    $('#total_token_on_hand').val(totalOnHandValue.toFixed(0));
                    $('#minus_total_token_on_hand').val(totalOnHandValue.toFixed(0));
                    $('#minus_variance_delivered_x_on_hand').val(minusVariance.toFixed(0));
                    $('#variance_beginning_x_on_hand').val(beginnginXonHandVariance.toFixed(0));
                });

                // TABLE APPEND
                response.collect_tokens.forEach(item => {
                    const bayLetter = item.get_bay.name.split(' ').pop();
                    const collectedQty = item.collected_qty;
                    const receivedQty = item.received_qty;
                    const MachineFrom = parseInt(item.lines[0]?.machine_serial.serial_number.match(/\d+/)[0],10);
                    const MachineTo = parseInt(item.lines[item.lines.length - 1]?.machine_serial.serial_number.match(/\d+/)[0],10);
                    const MachineVariance = Math.abs(collectedQty - receivedQty);
   
                    let collectionReportBody = `
                        <tr style="font-size: 12px;">
                            <td>${bayLetter}</td>
                            <td>${MachineFrom}</td>
                            <td>${MachineTo}</td>
                            <td>${item.get_created_by.name}</td>
                            <td>${collectedQty}</td>
                            <td>${receivedQty}</td>
                            <td>${MachineVariance == 0 ? "" : MachineVariance}</td> 
                        </tr>
                    `;
                    collectionReportTbody.append(collectionReportBody);

                });
                totalTokensReportTable.append(response.total_tokens);
                   
                $('#spinner').hide();
            },
            error: function(xhr, status, error) {
                alert('Error fetching data:', error);
                $('#spinner').hide();
            }
        });
    });
</script>
@endpush