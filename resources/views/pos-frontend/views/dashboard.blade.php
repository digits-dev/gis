{{-- Please always check the current plugins, css, script in content.blade.php--}}

{{-- Extend the dashboard layout --}}
@extends('pos-frontend.components.content')

{{-- Your Plugins --}}
@section('plugins')
<script src="{{ asset('jsHelper/withoutLeadingZeros.js') }}"></script>
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
    .statistic-title p {
        margin-left: 75px;
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

    .total_value, .cash_value_CASH {
        background: #ddd;
    }

    .statistic-box{
        display: none;
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
                                            <td><input type="text" style="height: 100%;" name="cash_value_{{ $mode_of_payment->id }}" class="cash_value_{{ $mode_of_payment->payment_description }} mode_of_payments" onkeypress="withoutLeadingZeros()" required {{ $mode_of_payment->payment_description == 'CASH' ? 'readonly' : ''}}></td>
                                            {{-- <td><input type="text" style="height: 100%;" class="cash_value_{{ $mode_of_payment->payment_description }}" onkeypress="withoutLeadingZeros()" ></td> --}}
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
                    <p class="fw-bold m-top-10">Current Date:</p>
                    <p class="m-top-10 currentDateTime" id="currentDateTime">Loading Time...</p>
                    <button class="bg-primary-c text-color-w fw-bold m-top-10 start-of-day" type="button" id="start_of_day" disabled>START OF DAY</button>
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
                <p class="fs-13 title-color text-color1">Token Quantity</p>
                <p class="fs-30 fw-bold text-color">{{ $no_of_tokens ?? 0 }}</p>
            </div>
            <div class="statistic-more-info d-flex-jcev">
                {{-- <a href="#" class="d-flex-al-c text-color1" style="width: 100%;">
                    <p class="fs-13 m-right-10">More info</p>
                    <i class="fa fa-arrow-circle-right"></i>
                </a> --}}
            </div>
        </div>
        <div class="statistic-box m-top-30 m-right-25" style="border-left: 5px solid rgb(15, 183, 52);">
            <div class="statistic-icon" style="background-color: rgb(15, 183, 52);">
                <i class="fa fa-circle-thin"></i>
            </div>
            <div class="statistic-title">
                <p class="fs-13 title-color text-color1">Capsules Quantity - Stockroom</p>
                <p class="fs-30 fw-bold text-color">{{ $no_of_capsules_in_stock_room ?? 0  }}</p>
            </div>
            <div class="statistic-more-info d-flex-jcev">
                {{-- <a href="#" class="d-flex-al-c text-color1" style="width: 100%;">
                    <p class="fs-13 m-right-10">More info</p>
                    <i class="fa fa-arrow-circle-right"></i>
                </a> --}}
            </div>
        </div>
        <div class="statistic-box m-top-30 m-right-25" style="border-left: 5px solid rgb(198, 55, 60);">
            <div class="statistic-icon" style="background-color: rgb(198, 55, 60);">
                <i class="fa fa-circle-thin"></i>
            </div>
            <div class="statistic-title">
                <p class="fs-13 title-color text-color1">Capsules Quantity - Machine</p>
                <p class="fs-30 fw-bold text-color">{{ $no_of_capsules_in_machine ?? 0 }}</p>
            </div>
            <div class="statistic-more-info d-flex-jcev">
                {{-- <a href="#" class="d-flex-al-c text-color1" style="width: 100%;">
                    <p class="fs-13 m-right-10">More info</p>
                    <i class="fa fa-arrow-circle-right"></i>
                </a> --}}
            </div>
        </div>
        <div class="statistic-box m-top-30 m-right-25" style="border-left: 5px solid rgb(35, 42, 63);">
            <div class="statistic-icon" style="background-color: rgb(35, 42, 63);">
                <i class="fa fa-archive"></i>
            </div>
            <div class="statistic-title">
                <p class="fs-13 title-color text-color1">No of machines</p>
                <p class="fs-30 fw-bold text-color">{{ $no_of_gm ?? 0 }}</p>
            </div>
            <div class="statistic-more-info d-flex-jcev">
                {{-- <a href="#" class="d-flex-al-c text-color1" style="width: 100%;">
                    <p class="fs-13 m-right-10">More info</p>
                    <i class="fa fa-arrow-circle-right"></i>
                </a> --}}
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

    if("{{ $missing_sod }}"){
        $('.statistic-box').show();
    }

    $("#start_of_day").prop('disabled', true);
    $("#start_of_day").css('background-color', 'rgb(243, 142, 142)');
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
            $("#start_of_day").prop('disabled', true);
            $("#start_of_day").css('background-color', 'rgb(243, 142, 142)');
        } else{
            $("#start_of_day").prop('disabled', false);
            $("#start_of_day").css('background-color', 'rgb(254,62,62)');
        }
        updateTotalCash();
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
    });

    $('#start_of_day').on('click', function() {
        Swal.fire({
            title: "Are Sure You Want To Submit?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit',
            returnFocus: false,
            reverseButtons: true,
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
                
                // Animate each statistic box
                $(".statistic-box").each(function(index) {
                    $(this).delay(500 * index).fadeIn(500, function() {
                        // After fading in, animate sliding from the left
                        $(this).animate({
                            left: "0px"
                        }, 500);
                    });
                });

            },
            error: function(err) {
                console.log(err);
                alert('An error occurred. Please try again later.');
            }
        });
    });
</script>
<script>

    const monthlySwaps = {!! json_encode($monthly_swap) !!}
    console.log(monthlySwaps);
    let months = [];
    let values = [];
    monthlySwaps.forEach(month => {
        const date = new Date(2000, month.month - 1, 1); // Use any year, 2000 in this case
        const options = { month: 'long' };
        const monthName = date.toLocaleDateString(undefined, options);
        months.push(`${monthName} ${month.year}`);
        values.push(month.token_value);
    })

    console.log(months);

    function sales() {
        var ctx = document.getElementById('myLineChart').getContext('2d');
        
        var data = {
            labels: months,
            datasets: [{
                label: 'Monthly Swap',
                data: values,
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