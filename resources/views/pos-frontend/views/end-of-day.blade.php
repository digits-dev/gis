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
            <form method="POST">
                @csrf
                <div class="d-flex-al-c">
                    <i class="fa fa-circle-o m-right-10"></i><p class="fs-20">Cash Float (EOD)</p>
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
                <div class="m-top-20">
                    <div class="d-flex-al-c">
                        <p class="max-w-75">Total Value</p>
                        <input type="text" class="input-design total_value" name="total_value" style="height: 35px; width:165px;" placeholder="Total value">
                        {{-- <input type="text" class="input-design total_value" placeholder="Total value" onkeypress="inputIsNumber()"> --}}
                    </div>
                    <div class="d-flex-al-c m-top-10">
                        <p class="max-w-75">Token qty</p>
                        <input type="text" class="input-design" name="total_token" placeholder="Token qty" oninput="numberOnly(this);" required>
                        {{-- <input type="text" class="input-design" placeholder="Token qty" onkeypress="inputIsNumber()"> --}}
                    </div>
                </div>
                <div class="d-flex-jcc-col m-top-30">
                    <p class="fw-bold m-top-10">Date: {{ $entry_date }}</p>
                    <button class="bg-primary-c text-color-w fw-bold m-top-10 start-of-day" type="button" id="end_of_day">END OF DAY</button>
                    <button class="hide" type="submit" id="real-submit-btn"></button>
                    <input type="text" class="end_day" name="end_day" value="END" readonly hidden>               
                    <input type="text" class="hide" name="entry_date" value="{{ $entry_date }}">
                    <p class="m-top-5 c-danger">*You can only "View" the system after end of day</p>
                </div>
            </form>
        </div>
    </div>
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
        $(".cash_value_CASH").val(formattedCashValue);

        const totalValue = (removeComma(cashValue) + removeComma(BDOValue) + removeComma(BPIValue) + removeComma(GCASHValue) + removeComma(PAYMAYAValue));
        // console.log(BDOValue, removeComma(BDOValue));
        const formattedTotalValue = totalValue.toFixed(2);
        $(".total_value").val(formattedTotalValue);
    }
    $(".cash_value_BDO, .cash_value_BPI, .cash_value_GCASH, .cash_value_PAYMAYA, .cash_value_P1000, .cash_value_P500, .cash_value_P200, .cash_value_P100,.cash_value_P50, .cash_value_P20, .cash_value_P10, .cash_value_P5, .cash_value_P1, .cash_value_25C, .cash_value_10C, .cash_value_5C, .cash_value_1C ").on("input", updateCashValue);
    updateCashValue();


    function numberOnly(numberElement){
        numberElement.value = numberElement.value.replace(/[^0-9]/g,'');
    }
    function removeComma(number) {
        return Number(`${number}`.replaceAll(',', ''));
    }
    function validateInput(inputElement) {
        let value = inputElement.value;
        // Remove any non-numeric and non-decimal characters
        value = value.replace(/[^0-9.]/g, '');
        // Ensure there is only one decimal point
        let decimalCount = value.split('.').length - 1;
        if (decimalCount > 1) {
            // If there is more than one decimal point, remove the extra ones
            value = value.substring(0, value.lastIndexOf('.'));
        }
        // Format the value with commas for every 3 digits
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        inputElement.value = value;
    }
    function validateInputToken(inputElement) {
        let value = inputElement.value;
        // Remove any non-numeric and non-decimal characters
        value = value.replace(/[^0-9]/g, '');
        // Format the value with commas for every 3 digits
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        inputElement.value = value;
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
                console.log(res);
                if (res.has_sod === false) {
                    location.href = "{{ url('pos_dashboard') }}";
                }
                $('.cash-float-section').hide();
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    background: '#f1f2fa',
                    color: 'black',
                    customClass: {
                    toast: 'bottom-0 end-0',
                    },
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Successfully Submitted!',
                });
            },
            error: function(err) {
                console.log(err);
                alert('An error occurred. Please try again later.');
            }
        });
    });

</script>
@endsection