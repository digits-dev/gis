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
    .main-container {
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: "Open Sans", sans-serif;
    }
    .container {
        width: 460px;
        background-color: #F6F6F5;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px,
        rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
        border-radius: 30px;
        padding: 20px 30px;
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom:20px 
    }
    .useraccounts_add_header{
        display: flex;
        justify-content: center;
        margin-bottom:20px;
        flex-direction: column;
    }
    .change_account_password{
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        color: #de4646;
    }
    .chang_password_description{
        font-size: 12px;
        text-align: center;
    }
    .change_password_pic{
        display: flex;
        justify-content: center;
        height: 250px;
        width: auto;
    }
    .useraccounts_add_body{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }
    .label_input{
        width: 100%;
        display: flex;
        align-content: center;
        justify-content: center;

    }
    .label_input input{
        border: 2px solid #de4646;
        background-color: white;
        padding: 2px;
        width: 90%;
        height: 30px;
    }
    .addaccount_btn{
    cursor: pointer;
    width: 150px;
    height: 40px;
    font-size: 20px;
    background-color: #da1616;
    border: none;
    color: white;
    border-radius: 10px;
    transition: background-color 0.3s;
    margin-top: 10px

    }
</style>
@endsection

{{-- Define the content to be included in the 'content' section --}}
@section('content')
<div>
    @if (session('error'))
        <span style="color: red; display:block;">{{ session('error') }}</span>
    @endif
    @if (session('success'))
        <span style="color: green; display:block;">{{ session('success') }}</span>
    @endif
    @if ($errors->has('new_password'))
        <span style="color: red; display:block;">{{ $errors->first('new_password') }}</span>
    @endif
    @if ($errors->has('confirmation_password'))
        <span style="color: red; display:block;">{{ $errors->first('confirmation_password') }}</span>
    @endif
</div>
<div class="main-container">
    <div class="container">
        <div class ="settings_content">
            <form method="POST" action="{{ route('update_password', auth()->user()->id) }}">
                @csrf
                <div class="useraccounts_add_header">
                    <div class="change_account_password">Change Account Password?</div>
                    <div class="change_password_pic">
                        <img src="{{ asset('img/change_password.png') }}" alt="">
                    </div>
                    <div class="chang_password_description">If you wish to change the account password, kindly fill in the current password, new password, and re-type new password.</div>
                </div>
                <div class="useraccounts_add_body">
                    <div class="label_input">
                        <input type="password" name="current_password" required style="" placeholder="Current Password">
                    </div>
                    <div class="label_input">
                        <input type="password" name="new_password" required style="" placeholder="New Password">
                    </div>
                    <div class="label_input">
                        <input type="password" name="confirmation_password" required style=""placeholder="Confirm New Password">
                    </div>
                    <div style="text-align:center">
                    <button type="submit" class="addaccount_btn" >Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div> 
</div>
@endsection

{{-- Your Script --}}
@section('script-js')


@endsection