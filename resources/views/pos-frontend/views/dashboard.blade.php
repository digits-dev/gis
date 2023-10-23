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
        flex-wrap: wrap;
        width: 100%;
    }

    .statistic-box{
        height: 150px;
        width: 100%;
        max-width: 350px;
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

    .statistic-chart-content{
        background-color: rgb(255, 255, 255);
        padding: 15px;
        border-radius: 5px;
        box-shadow: rgba(0, 0, 0, 0.1) 0rem 0.25rem 0.375rem -0.0625rem, rgba(0, 0, 0, 0.06) 0rem 0.125rem 0.25rem -0.0625rem;
        /* width: 100%; */
        height: 350px;
    }
</style>
@endsection

@section('cash-float')
    <div class="cash-float-section hide">
        <div class="cash-float">
            <div class="cash-float-content">
                <div class="d-flex-al-c">
                    <i class="fa fa-circle-o m-right-10"></i><p class="fs-20">Cash Float (SOD)</p>
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
                                    <td><input type="text" style="height: 100%;" onkeypress="inputIsNumber()"></td>
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
                        <input type="text" class="input-design" placeholder="Total value" onkeypress="inputIsNumber()">
                    </div>
                    <div class="d-flex-al-c m-top-10">
                        <p class="max-w-75">Token qty</p>
                        <input type="text" class="input-design" placeholder="Token qty" onkeypress="inputIsNumber()">
                    </div>
                </div>
                <div class="d-flex-jcc-col m-top-30">
                    <p class="fw-bold m-top-10">Current Date:</p>
                    <p class="m-top-10" id="currentDateTime">Loading Time...</p>
                    <button class="bg-primary-c text-color-w fw-bold m-top-10 start-of-day" type="button" id="start_of_day">START OF DAY</button>
                </div>
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

        $('.cash-float-section').css('visibility', 'visible');

        $('#start_of_day').on('click', function(){
            $('.cash-float-section').hide();
        })
    });
    
    function sales() {
        
        var ctx = document.getElementById('myLineChart').getContext('2d');

        // Dynamically set the canvas width to 100%
        var canvas = document.getElementById('myLineChart');
        canvas.style.width = '100%';
        canvas.style.height = '100%';

        var data = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Monthly Sales',
                data: [10, 15, 7, 20, 14],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
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