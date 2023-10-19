<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@section('content')
<style>

    .panel-content{
        height: 500px;
        display: flex;
        justify-content: center;
        align-content: center;
        align-items: center;
    }
    .panel-default{
        border-radius: 20px;
        width: 400px;
        margin-top: 100px;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
    }
    .panel-header{
        /* background-color: green;  */
        text-align:center;
        margin-bottom: 10px;
        font-size: 25px;
        padding: 20px;
    }
    .panel-footer{
        border-radius: 0px 0px 20px 20px;
        text-align: center;
    }
    .form-group{
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .form-group input{
        width: 250px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .panel-body{
        height: 400px;
    }
    .panel-img{
        text-align: center;
    }
    .panel-img img{
        width: 250px;
    }
</style>
    <!-- Your html goes here -->
    <div class="panel-content">
        <div class='panel panel-default'>
            <div class='panel-header'>
                <label>CAPSULE REFILL</label>
            </div>
            <div class='panel-body'>
            <form method='post' action='{{CRUDBooster::mainpath('add-save')}}'>
                <div class='form-group'>
                <label>Capsule Barcode</label>
                <input type='text' name='label1' required class='form-control'/>
                <label>To Gasha Machine</label>
                <input type='text' name='label1' required class='form-control'/>
                <label>Quantity</label>
                <input type='text' name='label1' required class='form-control'/>
                </div>
                <div class='panel-img'>
                    <img src="{{ asset('img/capsule-refill.png') }}">
                </div>
                                
            </form>
            </div>
            <div class='panel-footer'>
            <input type='submit' class='btn btn-primary' value='Save changes'/>
            </div>
        </div>
    </div>
@endsection