@extends('crudbooster::admin_template')
@push('head')
<style>
    .print-details{
        display: flex;
        margin: 20px 20px;
        justify-content: space-between;
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
        flex: 1 1 calc(25% - 20px); 
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
    
</style>
@endpush
@section('content')
<div class='panel panel-default print-data'>
    <div class='panel-body' id="print-section">
        <h4 class="text-center" style="margin:30px 0;"><b>TOKEN COLLECTION FORM</b></h4>
        <div class="print-details">
            <h5><b>Store Name: </b><span>Sample Store</span></h5>
            <h5><b>Date: </b><span>10-10-2024 12:21:32</span></h5>
        </div>
        <div class="container">
            @foreach (range(1, 8) as $item)
                <div class="bay">
                    <table>
                        <thead>
                            <tr>
                                <td colspan="2">Bay 1</td> 
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <td>Machine #</td>
                                <td>Qty</td>
                            </tr>
                        </thead>
                        <tr><td>PH00300</td><td>2</td></tr>
                        <tr><td>PH00301</td><td>2</td></tr>
                        <tr><td>PH00302</td><td>2</td></tr>
                        <tr><td>PH00303</td><td>2</td></tr>
                        <tr><td>PH00304</td><td>2</td></tr>
                        <tr><td>PH00305</td><td>2</td></tr>
                        <tr><td>PH00306</td><td>2</td></tr>
                        <tr><td>PH00307</td><td>2</td></tr>
                        <tr><td>PH00307</td><td>2</td></tr>
                        <tr><td>PH00307</td><td>2</td></tr>
                        <tr><td>Total</td><td>16</td></tr>
                    </table>
                </div>
            @endforeach
            

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
            <div class="collected-by" style="display:flex; flex-direction:column; gap: 20px;">
                @foreach (range(1,3) as $item)
                    <div class="signature-names">
                        <div style="margin-top:5px">John Doe</div>
                        <div><b>CSA</b></div>
                    </div>
                @endforeach
            </div>
            <div class="received-by">
                <div class="signature-names">
                    <div style="margin-top:5px">Jane Doe</div>
                    <div><b>Cashier</b></div>
               </div>
            </div>
        </div>


    </div>

    <div class='panel-footer no-print'>
        <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default no-print">{{ trans('message.form.back') }}</a>
        <div class="btn btn-info no-print pull-right" id="print-button" style="background-color: #3C8DBC;">Print</div>
    </div>
</div>


@endsection

@push('bottom')
<script>
    $(document).ready(function () {
        $('#print-button').on('click', function(){
            window.print();
        });
    });
</script>
@endpush