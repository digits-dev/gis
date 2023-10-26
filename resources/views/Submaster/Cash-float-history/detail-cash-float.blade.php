<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@push('head')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
{{-- Sweet Alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>

    :root{
        --primary-color: #fe3e3e;
        --secondary-color: #f5a623;
        --text-color: #333333;
        --title-color: #222222;
        --text-color1: #4b4848;
        --danger-color: red;
    }

    .bg-primary-c{
        background-color: var(--primary-color);
    }

    .bg-text-color{
        background-color: var(--text-color);
    }

    .c-danger{
        color: var(--danger-color);
    }

    .title-color{
        color: var(--title-color);
    }

    .text-color{
        color: var(--text-color);
    }

    .text-color1{
        color: var(--text-color1)
    }

    .text-color-w{
        color: white;
    }

    .hide{
        display: none;
    }

    .show{
        display: block;
    }

    .t-center{
        text-align: center;
    }

    .m-top-3{
        margin-top: 3px;
    }

    .m-top-5{
        margin-top: 5px;
    }

    .m-top-10{
        margin-top: 10px;
    }

    .m-top-15{
        margin-top: 15px;
    }

    .m-top-20{
        margin-top: 20px;
    }

    .m-top-30{
        margin-top: 30px;
    }

    .m-top-50{
        margin-top: 50px;
    }

    .m-right-5{
        margin-right: 5px;
    }

    .m-right-10{
        margin-right: 10px;
    }

    .m-right-25{
        margin-right: 25px;
    }

    .m-right-35{
        margin-right: 35px;
    }

    .m-bottom-15{
        margin-bottom: 15px;
    }

    .p-top-5{
        padding-top: 5px;
    }

    .p-top-10{
        padding-top: 10px;
    }

    .p-top-15{
        padding-top: 15px;
    }

    .p-top-20{
        padding-top: 20px;
    }

    .p-top-50{
        padding-top: 50px;
    }

    .p-top-bot-10{
        padding: 10px 0;
    }

    .fs-40{
        font-size: 40px;
    }

    .fs-30{
        font-size: 30px;
    }

    .fs-25{
        font-size: 25px;
    }

    .fs-20{
        font-size: 20px;
    }

    .fs-15{
        font-size: 15px;
    }

    .fs-13{
        font-size: 13px;
    }

    .fw-bold{
        font-weight: bold;
    }

    .max-w-75{
        min-width: 100px;
    }

    .d-flex-jcc-col{
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
    }

    .d-flex-jcsb{
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .d-flex-jcev{
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .d-flex-al-c{
        display: flex;
        align-items: center;
    }
    .select2-selection__choice{
            font-size:14px !important;
            color:black !important;
    }
    .select2-selection__rendered {
        line-height: 31px !important;
    }
    .select2-container .select2-selection--single {
        height: 35px !important;
    }
    .select2-selection__arrow {
        height: 34px !important;
    }

    .swal2-popup {
        font-size: 1.5rem !important;
    }

    .swal2-popup .swal2-title {
        font-size: 2.5rem !important;
    }

    .swal2-popup .swal2-content {
        font-size: 1.8rem !important;
    }

    .swal2-popup {
        margin: auto !important;
        position: absolute !important;
        left: 0 !important;
        right: 0 !important;
        top: 10 !important;
        transform: translateY(-50%) !important;
    }

.cash-float{
    width: 100%;
    background-color: #ffffff;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
    border-radius: 10px;
    border-bottom-left-radius: 10px;
    border-bottom-left-radius: 10px;
}

.cash-float-header{
    padding: 20px;
}

.eod-table{
    overflow-x: auto;
}

.eod-table table{
    min-width: 900px;
}

.eod-table, .eod-v-q{
    padding: 15px 20px;
}

.eod-table table input{
    /* border: 1px solid black; */
    width: 100%;
    text-align: center;
    padding: 0 5px;
}

.eod-table table th{
    border: 1px solid #bcb8b8;
    text-align: center;
}

.eod-table table td{
    text-align: center;
    border: 1px solid #bcb8b8;
    height: 35px;
}

input:disabled{
    background-color: #dddcdc;
    height: 100%;
    width: 100%;
}

.input-design{
    border: 1px solid #bcb8b8;
    text-align: center;
    height: 35px;
}

.cash-float-content{
    position: relative;
}

</style>
@endpush
@section('content')
    <p class="noprint"><a title='Return' href='{{g("return_url")}}'><i class='fa fa-chevron-circle-left '></i> &nbsp; {{trans("crudbooster.form_back_to_list",['module'=>CRUDBooster::getCurrentModule()->name])}}</a></p>       
    <div class='panel panel-default'>
        <div class='panel-heading'>Detail Cash Float</div>
        <form method='post' action='{{CRUDBooster::mainpath('add-save')}}'>
        @csrf
        <div class='panel-body'>
                <div class="row">
                    <div class="col-md-3">
                        <label class="control-label" for="">Location</label>
                        <select class="form-control select2-s" id="test" name="location_id">
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" {{ $row->locations_id == $location->id ? 'selected':'' }}>{{ $location->location_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="">Float Type</label>
                        <select class="form-control select2-s" id="test" name="float_id">
                            @foreach ($float_types as $float_type)
                                <option value="{{ $float_type->id }}">{{ $float_type->description }}</option>
                                <option value="{{ $float_type->id }}" {{ $row->float_types_id == $location->id ? 'selected':'' }}>{{ $float_type->description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <br>

                <div class="cash-float-section" id="view_history_tab">
                    <form method="POST">
                        @csrf
                        <div class="cash-float">
                            <div class="cash-float-content">
                                <form method="POST">
                                    @csrf
                                    <div class="cash-float-header bg-text-color d-flex-al-c text-color-w">
                                        <p class="fs-20 c-danger fw-bold text-color-w" id="float_type_description"></p>
                                    </div>
                                    <div class="eod-table m-top-20">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Mode of Payment</th>
                                                    <th>Value</th>
                                                    @foreach ($float_entries as $float_entry)
                                                    <th>
                                                        {{ $float_entry->description }}
                                                    </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($mode_of_payments as $mode_of_payment)
                                                <tr>
                                                    <td>{{ $mode_of_payment->payment_description }}</td>
                                                    @for ($i=0; $i<count($float_entries)+1; $i++)
                                                        @if ($i == 0 || $mode_of_payment->payment_description == 'CASH')
                                                            @if ($i == 0)
                                                                <td><input type="text" style="height: 100%;" name="cash_value_{{ $mode_of_payment->payment_description }}" class="cash_value_{{ $mode_of_payment->payment_description }}" readonly></td>
                                                                {{-- <td><input type="text" style="height: 100%;" class="cash_value_{{ $mode_of_payment->payment_description }}" readonly onkeypress="inputIsNumber()" ></td> --}}
                                                            @else
                                                                <td><input type="text" style="height: 100%;" name="cash_value_{{ $float_entries[$i-1]->description }}" class="cash_value_{{ $float_entries[$i-1]->description }}" readonly></td>
                                                                {{-- <td><input type="text" style="height: 100%;" class="cash_value_{{ $float_entries[$i-1]->description }}" onkeypress="inputIsNumber()"></td> --}}
                                                            @endif
                                                        @else
                                                        <td><input type="text" disabled></td>
                                                        @endif
                                                    @endfor
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="eod-v-q">
                                        <div class="d-flex-al-c">
                                            <p class="max-w-75">Total Value</p>
                                            <input type="text" class="input-design total_value" name="total_value" style="height: 35px; width:165px;" placeholder="Total value" readonly>
                                            {{-- <input type="text" class="input-design total_value" placeholder="Total value" onkeypress="inputIsNumber()"> --}}
                                        </div>
                                        <div class="d-flex-al-c m-top-10">
                                            <p class="max-w-75">Token qty</p>
                                            <input type="text" class="input-design total_token" name="total_token" placeholder="Token qty" style="height: 35px; width:165px;" oninput="numberOnly(this);" readonly>
                                            {{-- <input type="text" class="input-design" placeholder="Token qty" onkeypress="inputIsNumber()"> --}}
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class='panel-footer'>
                <a href="{{ CRUDBooster::mainpath() }}" class="btn btn-default">{{ trans('message.form.cancel') }}</a>
                {{-- <input type='button' class='btn btn-primary' id="submit" value='Submit'/> --}}
                <input type='submit' class='hide btn btn-primary' id="submit_orig" value='Submit'/>
            </div>
        </form>
    </div>
@endsection
@push('bottom')
    <script>

        $('.select2-s').select2({
            "width":"100%"
        });

        $('input, select').prop('disabled', true);

        const rowId = "{{ $row->id }}";
        const url_link = "{{ route('view_float_history', 'rowID') }}"; 

        const url = url_link.replace('rowID', rowId);


        $.ajax({
            url:url,
            dataType: 'json',
            type: 'GET',
            success: function(response){
                console.log(response);
                // Iterate through the response.cash_float_history_lines array
                $.each(response.cash_float_history_lines, function(index, line) {
                    $('.cash_value_' + line.entry_description).val(line.line_qty);

                    if (line.entry_description === "TOKEN") {
                        $('.total_token').val(line.line_qty);
                    }
                });

                $.each(response.cash_float_history_lines, function(index, line) {
                    $('.cash_value_' + line.payment_description).val(line.line_value);

                });
                const float_type_description = response.cash_float_history.description;
                $('#float_type_description').text(float_type_description + ' OF THE DAY');
                $('.total_value').val(response.cash_float_history.cash_value);
                $('#view_history_tab').attr('hidden', false);

            },
            error: function(error){
                console.log(error);
            }
        })

    </script>
@endpush