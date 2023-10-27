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
<script src="{{ asset('jsHelper/withoutLeadingZeros.js') }}"></script>
@endsection

{{-- Your CSS --}}
@section('css')
<style>
    .cash-float-content {
        display: none;
    }

    .total_value, .cash_value_CASH {
        background: #ddd;
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
                                            <td><input type="text" style="height: 100%;" name="cash_value_{{ $mode_of_payment->payment_description }}" class="cash_value_{{ $mode_of_payment->payment_description }} mode_of_payments" onkeypress="withoutLeadingZeros()" required {{ $mode_of_payment->payment_description == 'CASH' ? 'readonly' : ''}}></td>
                                            {{-- <td><input type="text" style="height: 100%;" class="cash_value_{{ $mode_of_payment->payment_description }}" onkeypress="inputIsNumber()" ></td> --}}
                                        @else
                                            <td><input actual_value="{{ $float_entries[$i-1]->value }}" type="text" style="height: 100%;" name="cash_value_{{ $float_entries[$i-1]->description }}" class="cash_value_{{ $float_entries[$i-1]->description }} cash_values" onkeypress="withoutLeadingZeros()" required ></td>
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
                        <input type="text" class="input-design total_token" name="total_token" placeholder="Token qty" style="width:165px;" onkeypress="withoutLeadingZeros()" required>
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
            html:'Make EOD for: <strong>{{ $entry_date }}</strong>',
            allowOutsideClick: false,
        })
    @endif

    $("#end_of_day").prop('disabled', true);
    $("#end_of_day").css('background-color', '#3333');
    const modeOfPayments = $('.mode_of_payments').get();
    const floatEntries = $('.cash_values').get();

    function updateTotalCash() {
        let totalCash = 0;
        floatEntries.forEach(entry => {
            const inputValue = Number($(entry).val().replace(/\D/g, ''));
            const actualValue = Number($(entry).attr('actual_value'));
            const product = inputValue * actualValue;
            totalCash += product;
        });

        $('.cash_value_CASH').val(Number(totalCash.toFixed(2)).toLocaleString());
        updateTotalValue();
    }

    function updateTotalValue() {
        let totalValue = 0;
        modeOfPayments.forEach(modeOfPayment => {
            const value = $(modeOfPayment).val().replace(/[^0-9.]/g, '');
            totalValue += Number(value);
        });

        $('.total_value').val(Number(totalValue.toFixed(2)).toLocaleString());
    }

    $('input').on('input', function(event) {
        if ($('.total_value').val() <= 0 || $('.total_token').val()  <= 0) {
            $("#end_of_day").prop('disabled', true);
            $("#end_of_day").css('background-color', '#3333');
        } else{
            $("#end_of_day").prop('disabled', false);
            $("#end_of_day").css('background-color', '#333333');
        }
        updateTotalCash();
    });

    $(document).ready(function(){
        $(".cash-float-content").fadeIn(1000);
        $(".cash_value_CASH").attr("readonly", true);
        $(".total_value").attr("readonly", true);
    });

    $('input').on('keypress', function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            const swalSubmitBtn = $('#start_of_day');
            const isDisabled = swalSubmitBtn.attr('disabled');
            if (!isDisabled) {
                swalSubmitBtn.click();
            }
        }
    })

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