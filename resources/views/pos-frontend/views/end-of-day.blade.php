{{-- Please always check the current plugins, css, script in content.blade.php--}}

{{-- Extend the dashboard layout --}}
@extends('pos-frontend.components.content')

{{-- Title of the page --}}
@section('title')
    <title>Dashboard</title>
@endsection

{{-- Your Plugins --}}
@section('plugins')
<script src="{{ asset('plugins/sweetalert.js') }}"></script>

@endsection

{{-- Your CSS --}}
@section('css')
<style>
    .cash-float-content {
        display: none;
    }
</style>
@endsection

{{-- Define the content to be included in the 'content' section --}}

@section('cash-float-end')
<div class="cash-float-section">
    <div class="cash-float">
        <div class="cash-float-content">
            <form method="POST" autocomplete="off">
                @csrf
                <div class="cash-float-header bg-text-color d-flex-al-c text-color-w bg-primary-c">
                    <i class="fa fa-circle-o m-right-10"></i><p class="fs-20 fw-bold">Cash Float (EOD)</p>
                </div>
                <div class="eod-table">
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
                                            <td><input type="text" style="height: 100%;" name="cash_value_{{ $mode_of_payment->payment_description }}" class="cash_value_{{ $mode_of_payment->payment_description }}" oninput="validateInput(this);" required></td>
                                            {{-- <td><input type="text" style="height: 100%;" class="cash_value_{{ $mode_of_payment->payment_description }}" onkeypress="inputIsNumber()" ></td> --}}
                                        @else
                                            <td><input type="text" style="height: 100%;" name="cash_value_{{ $float_entries[$i-1]->description }}" class="cash_value_{{ $float_entries[$i-1]->description }}" oninput="numberOnly(this);" required ></td>
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
                        <input type="text" class="input-design total_value" name="total_value" style="width:165px;" placeholder="Total value">
                        {{-- <input type="text" class="input-design total_value" placeholder="Total value" onkeypress="inputIsNumber()"> --}}
                    </div>
                    <div class="d-flex-al-c m-top-10">
                        <p class="max-w-75">Token qty</p>
                        <input type="text" class="input-design total_token" name="total_token" placeholder="Token qty" style="width:165px;" oninput="validateInput(this);" required>
                        {{-- <input type="text" class="input-design" placeholder="Token qty" onkeypress="inputIsNumber()"> --}}
                    </div>
                </div>
                <div class="d-flex-jcc-col m-bottom-15">
                    <p class="fw-bold m-top-10">Date: {{ $entry_date }}</p>
                    <button class="bg-text-color text-color-w fw-bold m-top-10 start-of-day" type="button" id="end_of_day">END OF DAY</button>
                    <button class="hide" type="submit" id="real-submit-btn"></button>
                    <input type="text" class="end_day" name="end_day" value="END" readonly hidden>               
                    <input type="text" class="hide" name="entry_date" value="{{ $entry_date }}">
                    <p class="m-top-5 c-danger">*You may not login to the system after end of day</p>
                </div>
            </form>
        </div>
    </div>
    <a href="/pos_dashboard" class="back-to-dashboard">Back to dashboard</a>
</div>

@endsection

{{-- Your Script --}}
@section('script-js')
<script src="{{ asset('jsHelper/isNumber.js') }}"></script>
<script>
      @if(session('is_missing'))
    Swal.fire({
    icon:'warning',
    title: 'Missing END OF DAY!',
    html:'Make EOD for: <strong>{{ $entry_date }}</strong>'
})

@endif
    $(document).ready(function(){
        $(".cash-float-content").fadeIn(1000);
        $(".cash_value_CASH").attr("readonly", true);
        $(".total_value").attr("readonly", true);
    });

    function updateCashValue (){
        const BDOValue = ($(".cash_value_BDO").val()) || 0;
        const BPIValue = ($(".cash_value_BPI").val()) || 0;
        const GCASHValue = ($(".cash_value_GCASH").val()) || 0;
        const PAYMAYAValue = ($(".cash_value_PAYMAYA").val()) || 0;
        const P1000Value = ($(".cash_value_P1000").val()) || 0;
        const P500Value = ($(".cash_value_P500").val()) || 0;
        const P200Value = ($(".cash_value_P200").val()) || 0;
        const P100Value = ($(".cash_value_P100").val()) || 0;
        const P50Value = ($(".cash_value_P50").val()) || 0;
        const P20Value = ($(".cash_value_P20").val()) || 0;
        const P10Value = ($(".cash_value_P10").val()) || 0;
        const P5Value = ($(".cash_value_P5").val()) || 0;
        const P1Value = ($(".cash_value_P1").val()) || 0;
        const C25Value = ($(".cash_value_25C").val()) || 0;
        const C10Value = ($(".cash_value_10C").val()) || 0;
        const C5Value = ($(".cash_value_5C").val()) || 0;
        const C1Value = ($(".cash_value_1C").val()) || 0;
        const cashValue = (P1000Value * 1000) + (P500Value * 500) + (P200Value * 200) 
        + (P100Value * 100) + (P50Value * 50) + (P20Value * 20) + (P10Value * 10)
        + (P5Value * 5) + (P1Value * 1) + (C25Value * 0.25)+ (C10Value * 0.10)
        + (C5Value * 0.05) + (C1Value * 0.01);
        const formattedCashValue = cashValue.toFixed(2);
        const newFormattedCashValue = formatCashValue(formattedCashValue);
        $(".cash_value_CASH").val(newFormattedCashValue);

        const totalValue = (removeComma(cashValue) + removeComma(BDOValue) + removeComma(BPIValue) + removeComma(GCASHValue) + removeComma(PAYMAYAValue));
        // console.log(BDOValue, removeComma(BDOValue));
        const formattedTotalValue = totalValue.toFixed(2);
        const newFormattedTotalValue = formatCashValue(formattedTotalValue);
        $(".total_value").val(newFormattedTotalValue);

        if ($('.total_value').val() <= 0 || $('.total_token').val()  <= 0) {
            $("#end_of_day").prop('disabled', true);
            $("#end_of_day").css('background-color', 'rgb(114, 114, 114)');
        } else{
            $("#end_of_day").prop('disabled', false);
            $("#end_of_day").css('background-color', 'rgb(51,51,51)');

        }
    }
    $(".cash_value_BDO, .cash_value_BPI, .cash_value_GCASH, .cash_value_PAYMAYA, .cash_value_P1000, .cash_value_P500, .cash_value_P200, .cash_value_P100,.cash_value_P50, .cash_value_P20, .cash_value_P10, .cash_value_P5, .cash_value_P1, .cash_value_25C, .cash_value_10C, .cash_value_5C, .cash_value_1C, .total_token ").on("input", updateCashValue);
    updateCashValue();


    function numberOnly(numberElement){
        numberElement.value = numberElement.value.replace(/[^0-9]/g,'');
    }
    
    function removeComma(number) {
        return Number(`${number}`.replaceAll(',', ''));
    }

    function validateInput(inputElement) {
        let value = inputElement.value;
        value = value.replace(/[^0-9.]/g, '');
        let decimalCount = value.split('.').length - 1;
        if (decimalCount > 1) {
            value = value.substring(0, value.lastIndexOf('.'));
        }
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        const [whole, decimal] = value.split('.');
        if (decimal) {
            value = `${whole}.${decimal.slice(0, 2)}`;
        }
        if (value.charAt(0) === '0' && value.length > 1) {
            value = value.substring(1);
        }
        inputElement.value = value;
    }

    function validateInputToken(inputElement) {
        let value = inputElement.value;
        value = value.replace(/[^0-9]/g, '');
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        inputElement.value = value;
    }
    function formatCashValue(cashValue) {
        const parts = cashValue.toString().split(".");
        const wholePart = parts[0];
        const decimalPart = parts[1] || '';
        const formattedWholePart = wholePart.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        const formattedCashValue = decimalPart === '' ? formattedWholePart : `${formattedWholePart}.${decimalPart}`;
        return formattedCashValue;
    }

    $('#end_of_day').on('click', function() {
        Swal.fire({
            title: "Are Sure You Want To Submit?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit',
            returnFocus: false,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#real-submit-btn').click();
            }
        });
    });

    $('form').on('submit', function(event) {
        event.preventDefault();
        const formData = $('form').serialize();
        $.ajax({
            type: 'POST',
            url: "{{ route('submitEOD') }}",
            data: formData,
            success: function(res) {
                if (res.has_sod === false) {
                    Swal.fire({
                        title: "POS (EOD) successfully recorded",
                        html: 'This will redirect you to SOD.',
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok',
                        allowOutsideClick: false,
                        returnFocus: false,
                    }).then((result) => {
                        console.log(res);
                        location.href = "{{ url('pos_dashboard') }}";
                    });
                }else{
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("logout_end_session") }}',
                        success: function(data){
                            Swal.fire({
                                title: "POS (EOD) successfully recorded",
                                html: 'This will log-out your account.',
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok',
                                allowOutsideClick: false,
                                returnFocus: false,
                            }).then((result) => {
                                console.log(res);
                                location.href = "{{ route('logout_eod') }}";
                            });
                        },
                        error: function(error){
                            console.log(error);
                        }
                    });
                }
            },
            error: function(err) {
                console.log(err);
                Swal.fire({
                    title: "An error occurred. Please try again later.",
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ok',
                    returnFocus: false,
                })
            }
        });
    });

</script>
@endsection