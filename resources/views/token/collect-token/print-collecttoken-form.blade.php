@extends('crudbooster::admin_template')
@push('head')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .print-details{
        display: flex;
        margin: 20px 20px;
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
        flex-wrap: wrap;
    }

    .bay {
        flex: 1 1 calc(25% - 10px); 
        text-align: center;
        box-sizing: border-box;
        margin: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    h3 {
       margin: 0 0 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #000;
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: #f0f0f0;
    }

    tr:last-child {
        font-weight: bold;
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
    
</style>
@endpush
@section('content')

<form class="panel panel-default form-content no-print" style="overflow: hidden">
    <div class="panel-heading header-title">Token Collection Form - Filter</div>
    <div class="content-panel">
        <div class="inputs-container" >
            <div class="input-container">
                <div style="font-weight: 600">Store Name </div>
                <input type="text" style="border-radius: 5px;" value="{{$store_name->location_name ?? 'No Location Selected'}}" disabled>
            </div>

            <div class="input-container">
                <div style="font-weight: 600">Receiver <small id="receiver_required" style="display: none; color: rgba(255, 0, 0, 0.853);"> <i class="fa fa-exclamation-circle"></i> Required field! </small></div>
                <div class="custom-select" id="customSelectLocation">
                    <select name="receiver" id="receiver" required>
                        <option value="" disabled selected>Select Receiver</option>
                        @foreach ($receiver as $user)
                            <option value="{{ $user->id }}">{{$user->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
        </div>
        <div class="inputs-container" style="margin-top: 10px;">
            <div class="input-container">
                <div style="font-weight: 600">Reference Number/s <small id="ref_required" style="display: none; color: rgba(255, 0, 0, 0.853);"> <i class="fa fa-exclamation-circle"></i> Required field! </small> </div>
                <select class="js-example-basic-multiple" id="reference_numbers" name="reference_numbers[]" multiple="multiple">
                    @foreach ($reference_numbers as $refs)
                        <option value="{{ $refs->id }}">{{ $refs->reference_number }} - {{$refs->getBay->name}}</option>
                    @endforeach
                </select> 
            </div>
        </div>
        <div style="margin-top: 10px;"><b style="color: red">Note:</b> Only transactions have the status <b style="color: red">COLLECTED</b> will appear here.</div>
    </div>
    <div class='panel-footer no-print'>
        <div class="form-button pull-left" style=" margin-top:10px;" >
            <a href="{{ CRUDBooster::mainpath() }}" id="btn-cancel" class="btn-submit" style="padding: 10px 20px; background:#838383; border: 1px solid #838383; margin-right: 5px;">Back</a>
        </div>
        <div class="pull-right"  style=" display:flex">
            <div class="form-button" >
                <button class="btn-submit"  id="btn-reset" style="background:#e73131; border: 1px solid #d34040; margin-right: 5px;">Reset</button>
            </div>
            <div class="form-button" >
                <button class="btn-submit" id="btn-submit">Filter</button>
            </div>
        </div>
    </div>
</form>

<div class='panel panel-default print-data' id="print-form" style="display:none">
    <div class='panel-body' id="print-section">
        <h4 class="text-center" style="margin:30px 0;"><b>TOKEN COLLECTION FORM</b></h4>
        <div class="print-details" id="print-details">
           {{-- APPEND HERE DETAILS --}}
        </div>
        <div class="container" id="container">
            {{-- APPEND HERE --}}
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

<div class="spinner-overlay" id="spinner" style="display: none;">
    <div class="spinner">
    </div>
</div>


@endsection

@push('bottom')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $('.content-header').hide();
    $(document).ready(function () {
        $('#print-button').on('click', function(){
            window.print();
        });

        $('#reference_numbers').select2({
            placeholder: "Select Reference Number/s",
        });
    });

    $('#btn-reset').on('click', function(event){
            event.preventDefault();
            $('#reference_numbers').val(null).trigger('change');
            $('#print-form').hide();
            $('#receiver').val(null);
            $('#ref_required').hide();
            $('#receiver_required').hide();

        });

    $('#btn-submit').on('click', function(event) {
        event.preventDefault();
        let references = $('#reference_numbers').val();
        let receiver = $('#receiver').val();

        if (receiver === null || receiver.length === 0) {
            $('#receiver_required').show();
            return;
        }
        else{
            $('#receiver_required').hide();
        }
        if (references === null || references.length === 0) {
            $('#ref_required').show();
            return;
        }
        else{
            $('#ref_required').hide();
            
        }

        $('#spinner').show();

        $.ajax({
            url: '{{route("postPrint")}}',
            method: 'POST',
            data: {
                references: references,
                receiver: receiver,
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                $('#print-form').show();

                const container = $('#container');
                const printDetails = $('#print-details');
                const receivedBy = $('#received-by');
                const collectedBy = $('#collected-by');

                container.empty();
                printDetails.empty();
                receivedBy.empty();
                collectedBy.empty();

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
                
                

                printDetails.append(details);
                receivedBy.append(receiver);

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
                                        <td colspan="4">${item.get_bay.name}</td>
                                    </tr>
                                
                                    <tr>
                                        <td colspan="4"><b>Collector: </b>${item.get_created_by.name}</td>
                                    </tr>
                                    <tr>
                                        <td>Machine #</td>
                                        <td>JAN #</td>
                                        <td>Item Description</td>
                                        <td>Qty</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${item.lines.map(machine => `
                                        <tr>
                                            <td>${machine.machine_serial.serial_number}</td>
                                            <td>${machine.inventory_capsule_lines[0]?.get_inventory_capsule.item.digits_code}</td>
                                            <td>${machine.inventory_capsule_lines[0]?.get_inventory_capsule.item.item_description}</td>
                                            <td>${machine.qty}</td>
                                        </tr>
                                    `).join('')}
                                    <tr>
                                        <td colspan="3">Total</td>
                                        <td>${item.lines.reduce((sum, machine) => sum + machine.qty, 0)}</td>
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
</script>
@endpush