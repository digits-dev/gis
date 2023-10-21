{{-- Please always check the current plugins, css, script in content.blade.php--}}

{{-- Extend the dashboard layout --}}
@extends('pos-frontend.components.content')

{{-- Title of the page --}}
@section('title')
    <title>Dashboard</title>
@endsection

{{-- Your Plugins --}}
@section('plugins')
@endsection

{{-- Your CSS --}}
@section('css')
<style>

    .cash-float-section{
        position: absolute;
        height: 100%;
        width: 100%;
        background-color: rgba(0, 0, 0, 0.679); 
        z-index: 1;
        transition: 5s ease-in;
        visibility: hidden;
    }

    .cash-float{
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%,-50%);
        height: 600px;
        width: 1200px;
        background-color: #ffffff;
        box-shadow: rgb(255, 255, 255) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset; 
        border-radius: 10px;
        padding: 20px;
        transition: 5s ease-in;
    }

    .eod-table table input{
        /* border: 1px solid black; */
        width: 100%;
        text-align: center;
        padding: 0 5px;
    }

    .eod-table table th{
        border: 1px solid #bcb8b8;
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

    #start_of_day{
        padding: 10px 70px;
        cursor: pointer;
        box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    }

    #start_of_day:hover{
        opacity: 0.7;
    }
</style>
@endsection

@section('cash-float')
    <div class="cash-float-section">
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
                    <button class="bg-primary-c text-color-w fw-bold m-top-10" type="button" id="start_of_day">START OF DAY</button>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Define the content to be included in the 'content' section --}}
@section('content')
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
</script>
@endsection