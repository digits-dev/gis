{{-- Please always check the current plugins, css, script in content.blade.php--}}

{{-- Extend the dashboard layout --}}
@extends('pos-frontend.components.content')

{{-- Your Plugins --}}
@section('plugins')
@endsection

{{-- Your CSS --}}
@section('css')
<style>

    .statistic-content{
        display: flex;
         justify-content: center;
         align-items: center;
    }
    @media only screen and (max-width: 1000px) {
        .statistic-content {
            flex-wrap: wrap;
         }
        }


    .statistic-box{
        height: 150px;
        width: 100%;
        max-width: 340px;
        background-color: rgb(255, 255, 255);
        padding: 15px;
        border-radius: 5px;
        box-shadow: rgba(0, 0, 0, 0.1) 0rem 0.25rem 0.375rem -0.0625rem, rgba(0, 0, 0, 0.06) 0rem 0.125rem 0.25rem -0.0625rem;
        position: relative;
        display: flex;
        flex-direction: column;
        /* border-left: 5px solid rgb(243, 212, 9); */
    }
    .statistic-icon{
        position: absolute;
        top: -25px;
        height: 55px;
        width: 65px;
        box-shadow: rgba(0, 0, 0, 0.14) 0rem 0.25rem 1.25rem 0rem, rgba(64, 64, 64, 0.4) 0rem 0.4375rem 0.625rem -0.3125rem;
        display: grid;
        place-content: center;
        font-size: 25px;
        /* background-color: rgb(243, 212, 9); */
        border-radius: 5px;
        color: #fff;
    }
    .statistic-title{
        text-align: right;
        border-bottom: 1px solid #ddd;
        padding-bottom: 20px;
    }
    .statistic-more-info{
        height: 100%;
    }
    .statistic-more-info a:hover{
        opacity: 0.7;
    }

    .statistic-chart-content {
        position: relative;
        width: 100%;
        /* background: red; */
        height: 300px;
    }

    #myLineChart {
        position: absolute;
        max-height: 300px; 
        width: 100%;
        background-color: rgb(255, 255, 255);
        box-shadow: rgba(0, 0, 0, 0.1) 0rem 0.25rem 0.375rem -0.0625rem, rgba(0, 0, 0, 0.06) 0rem 0.125rem 0.25rem -0.0625rem;
        border-radius: 5px;
        padding: 10px;
    }

</style>
@endsection

@section('cash-float')

<div class="cash-float-section" {{ $missing_sod ? 'hidden' : '' }}>
    <div class="cash-float">
        <div class="cash-float-content">
            <form method="POST" autocomplete="off">
                @csrf
                <div class="cash-float-header bg-primary-c d-flex-al-c text-color-w">
                    <i class="fa fa-circle-o m-right-10"></i><p class="fs-20 fw-bold">Cash Float (SOD)</p>
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
                    <p class="fw-bold m-top-10">Current Date:</p>
                    <p class="m-top-10 currentDateTime" id="currentDateTime">Loading Time...</p>
                    <button class="bg-primary-c text-color-w fw-bold m-top-10 start-of-day" type="button" id="start_of_day">START OF DAY</button>
                    <button class="hide" type="submit" id="real-submit-btn"></button>
                    <input type="text" class="start_day" name="start_day" value="START" readonly hidden>               
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- Define the content to be included in the 'content' section --}}
@section('content')
    <div class="statistic-content">
        <div class="statistic-box  m-top-30 m-right-25" style="border-left: 5px solid rgb(243, 212, 9);">
            <div class="statistic-icon" style="background-color: rgb(243, 212, 9);">
                <i class="fa fa-database"></i>
            </div>
            <div class="statistic-title">
                <p class="fs-13 title-color text-color1">No. of tokens</p>
                <p class="fs-30 fw-bold text-color">200</p>
            </div>
            <div class="statistic-more-info d-flex-jcev">
                <a href="" class="d-flex-al-c text-color1" style="width: 100%;">
                    <p class="fs-13 m-right-10">More info</p>
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="statistic-box m-top-30 m-right-25" style="border-left: 5px solid rgb(15, 183, 52);">
            <div class="statistic-icon" style="background-color: rgb(15, 183, 52);">
                <i class="fa fa-circle-thin"></i>
            </div>
            <div class="statistic-title">
                <p class="fs-13 title-color text-color1">No of capsules.</p>
                <p class="fs-30 fw-bold text-color">200</p>
            </div>
            <div class="statistic-more-info d-flex-jcev">
                <a href="" class="d-flex-al-c text-color1" style="width: 100%;">
                    <p class="fs-13 m-right-10">More info</p>
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="statistic-box m-top-30 m-right-25" style="border-left: 5px solid rgb(35, 42, 63);">
            <div class="statistic-icon" style="background-color: rgb(35, 42, 63);">
                <i class="fa fa-archive"></i>
            </div>
            <div class="statistic-title">
                <p class="fs-13 title-color text-color1">No of machines.</p>
                <p class="fs-30 fw-bold text-color">200</p>
            </div>
            <div class="statistic-more-info d-flex-jcev">
                <a href="" class="d-flex-al-c text-color1" style="width: 100%;">
                    <p class="fs-13 m-right-10">More info</p>
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="statistic-box m-top-30 m-right-25" style="border-left: 5px solid rgb(198, 55, 60);">
            <div class="statistic-icon" style="background-color: rgb(198, 55, 60);">
                <i class="fa fa-circle"></i>
            </div>
            <div class="statistic-title">
                <p class="fs-13 title-color text-color1">No of items.</p>
                <p class="fs-30 fw-bold text-color">200</p>
            </div>
            <div class="statistic-more-info d-flex-jcev">
                <a href="" class="d-flex-al-c text-color1" style="width: 100%;">
                    <p class="fs-13 m-right-10">More info</p>
                    <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="statistic-chart-content m-top-50" >
        <canvas id="myLineChart"></canvas>
    </div>
@endsection

{{-- Your Script --}}
@section('script-js')
<script src="{{ asset('jsHelper/isNumber.js') }}"></script>
<script>

    $(document).ready(function(){
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
            $("#start_of_day").prop('disabled', true);
            $("#start_of_day").css('background-color', 'rgb(243, 142, 142)');
        } else{
            $("#start_of_day").prop('disabled', false);
            $("#start_of_day").css('background-color', 'rgb(254,62,62)');
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

    $('#start_of_day').on('click', function() {
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
            url: "{{ route('submitSOD') }}",
            data: formData,
            success: function(res) {
                console.log(res);
                if (res.has_sod_today) {
                    Swal.fire({
                        title: `Today's SOD has been set!`,
                        html: `SOD can be submitted only once a day.`,
                        icon: 'error',
                    });
                    $('.cash-float-section').hide();
                } else {
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
                }


            },
            error: function(err) {
                console.log(err);
                alert('An error occurred. Please try again later.');
            }
        });
    });

    function sales() {
        var ctx = document.getElementById('myLineChart').getContext('2d');
        
        var data = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Monthly Sales',
                data: [10, 15, 7, 20, 14],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderWidth: 2,
                fill: true,
                tension: 0.1,
            }]
        };

        var options = {
            responsive: true, // Make the chart responsive
            maintainAspectRatio: true, // Maintain the aspect ratio
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: options
        });
    }

    // Call the function to create the chart
    sales();




</script>
@endsection