<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}">
        <title>Gashapon POS Login Page</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link rel="stylesheet" href="{{ asset('css/pos-frontend.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/crudbooster/assets/adminlte/font-awesome/css/font-awesome.min.css') }}">
        {{-- SWEET ALERT --}}
        <script src="{{ asset('plugins/sweetalert.js') }}"></script>
        <style type="text/css">
            .checkbox-wrapper-12 {
                position: relative;
            }
            .checkbox-wrapper-12 > svg {
                position: absolute;
                top: -130%;
                left: -170%;
                width: 110px;
                pointer-events: none;
            }
            .checkbox-wrapper-12 * {
                box-sizing: border-box;
            }
            .checkbox-wrapper-12 input[type="checkbox"] {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                -webkit-tap-highlight-color: transparent;
                cursor: pointer;
                margin: 0;
            }
            .checkbox-wrapper-12 input[type="checkbox"]:focus {
                outline: 0;
            }
            .checkbox-wrapper-12 .cbx {
                width: 18px;
                height: 18px;
                top: calc(50vh - 12px);
                left: calc(50vw - 12px);
            }
            .checkbox-wrapper-12 .cbx input {
                position: absolute;
                top: 0;
                left: 0;
                width: 18px;
                height: 18px;
                border: 2px solid #bfbfc0;
                border-radius: 50%;
            }
            .checkbox-wrapper-12 .cbx label {
                width: 18px;
                height: 18px;
                background: none;
                border-radius: 50%;
                position: absolute;
                top: 0;
                left: 0;
                -webkit-filter: url("#goo-12");
                filter: url("#goo-12");
                transform: trasnlate3d(0, 0, 0);
                pointer-events: none;
            }
            .checkbox-wrapper-12 .cbx svg {
                position: absolute;
                top: 4.5px;
                left: 3.6px;
                z-index: 1;
                pointer-events: none;
            }
            .checkbox-wrapper-12 .cbx svg path {
                stroke: #fff;
                stroke-width: 3;
                stroke-linecap: round;
                stroke-linejoin: round;
                stroke-dasharray: 19;
                stroke-dashoffset: 19;
                transition: stroke-dashoffset 0.3s ease;
                transition-delay: 0.2s;
            }
            .checkbox-wrapper-12 .cbx input:checked + label {
                animation: splash-12 0.6s ease forwards;
            }
            .checkbox-wrapper-12 .cbx input:checked + label + svg path {
                stroke-dashoffset: 0;
            }
            @-moz-keyframes splash-12 {
                40% {
                background: #e73838;
                box-shadow: 0 -18px 0 -8px #e73838, 16px -8px 0 -8px #e73838, 16px 8px 0 -8px #e73838, 0 18px 0 -8px #e73838, -16px 8px 0 -8px #e73838, -16px -8px 0 -8px #e73838;
                }
                100% {
                background: #e73838;
                box-shadow: 0 -36px 0 -10px transparent, 32px -16px 0 -10px transparent, 32px 16px 0 -10px transparent, 0 36px 0 -10px transparent, -32px 16px 0 -10px transparent, -32px -16px 0 -10px transparent;
                }
            }
            @-webkit-keyframes splash-12 {
                40% {
                background: #e73838;
                box-shadow: 0 -18px 0 -8px #e73838, 16px -8px 0 -8px #e73838, 16px 8px 0 -8px #e73838, 0 18px 0 -8px #e73838, -16px 8px 0 -8px #e73838, -16px -8px 0 -8px #e73838;
                }
                100% {
                background: #e73838;
                box-shadow: 0 -36px 0 -10px transparent, 32px -16px 0 -10px transparent, 32px 16px 0 -10px transparent, 0 36px 0 -10px transparent, -32px 16px 0 -10px transparent, -32px -16px 0 -10px transparent;
                }
            }
            @-o-keyframes splash-12 {
                40% {
                background: #e73838;
                box-shadow: 0 -18px 0 -8px #e73838, 16px -8px 0 -8px #e73838, 16px 8px 0 -8px #e73838, 0 18px 0 -8px #e73838, -16px 8px 0 -8px #e73838, -16px -8px 0 -8px #e73838;
                }
                100% {
                background: #e73838;
                box-shadow: 0 -36px 0 -10px transparent, 32px -16px 0 -10px transparent, 32px 16px 0 -10px transparent, 0 36px 0 -10px transparent, -32px 16px 0 -10px transparent, -32px -16px 0 -10px transparent;
                }
            }
            @keyframes splash-12 {
                40% {
                background: #e73838;
                box-shadow: 0 -18px 0 -8px #e73838, 16px -8px 0 -8px #e73838, 16px 8px 0 -8px #e73838, 0 18px 0 -8px #e73838, -16px 8px 0 -8px #e73838, -16px -8px 0 -8px #e73838;
                }
                100% {
                background: #e73838;
                box-shadow: 0 -36px 0 -10px transparent, 32px -16px 0 -10px transparent, 32px 16px 0 -10px transparent, 0 36px 0 -10px transparent, -32px 16px 0 -10px transparent, -32px -16px 0 -10px transparent;
                }
            }
    
        </style>
    </head>
    <body style="height: 100% !important">
        <section class="login">
            <form action="{{ route('login') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="login-box">
                    <div class="store-logo-box">
                        <div class="store-logo-box-content">
                            <div class="store-logo-brand">
                                <img src="{{ asset('img/gashapon_logo.png') }}" alt="">
                            </div>
                            <div class="store-logo-description danger-color">
                                <p class="fs-40 fw-bold">Inventory System</p>
                                <p class="fs-30 fw-bold">Frontend</p>
                            </div>
                            <div class="store-logo-link-to-frontend">
                                <a href="/admin/login">Link to Backend</a>
                            </div>
                            <div class="store-logo-official">
                                <img src="{{ asset('img/gashapon_official.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="store-login-box store-login-box-bgc-f">
                        <div class="store-login-content">
                            <div class="store-login-title">
                                <p class="fs-30 fw-bold">Login</p>
                            </div>
                            <div class="store-login-inputs">
                                <input type="email" id="username" placeholder="Email" name="email" required>
                                <label for="username">
                                    <i class="fa fa-user"></i>
                                </label>
                            </div>
                            <div class="store-login-inputs">
                                <input type="password" id="lock" placeholder="Password" name="password" required>
                                <label for="lock">
                                    <i class="fa fa-lock"></i>
                                </label>
                            </div>
                            <div class="checkbox-container" style="display: flex; gap: 5px; align-items: center; justify-content: flex-end; margin-left: 220px; margin-bottom: 10px;">
                                <div class="checkbox-wrapper-12">
                                    <div class="cbx">
                                        <input id="checkbox-show-pass" type="checkbox"/>
                                        <label for="checkbox-show-pass"></label>
                                        <svg width="10" height="9" viewbox="0 0 15 14" fill="none">
                                        <path d="M2 8.36364L6.23077 12L13 2"></path>
                                        </svg>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
                                        <defs>
                                        <filter id="goo-12">
                                            <fegaussianblur in="SourceGraphic" stddeviation="4" result="blur"></fegaussianblur>
                                            <fecolormatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 22 -7" result="goo-12"></fecolormatrix>
                                            <feblend in="SourceGraphic" in2="goo-12"></feblend>
                                        </filter>
                                        </defs>
                                    </svg>
                                </div>
                                <p style="font-size: 13px;">Show Password</p>
                            </div>
                            <div>
                                <button type="submit" class="login-btn fw-bold">Login</button>
                            </div>
                            <div class="fw-bold title-color fs-15">
                                <p>Forgot the password? <a href="#" id="forgot_pass" class="primary-color cursor-p">Click here</a></p>
                            </div>
                            <div class="fw-bold title-color fs-15 success-color">
                                @if(Session::has('logged_out_success'))
                                    <br>
                                    <p>{{ Session::get('logged_out_success') }}</p>
                                @endif
                            </div>
                            <div class="fw-bold title-color fs-15 danger-color">
                                @error('error')
                                    <br>
                                    <p style="text-align: center; padding: 0 20px;">{{ $message }}</p> <!-- Display the error message -->
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
        <script>
            window.onpageshow = function(event) {
            if (event.persisted) {
                    location.reload();
                }
            };
            $('#forgot_pass').click(function(event) {
                Swal.fire({
                    type: 'info',
                    text: 'Please contact ISD department!',
                    title:'Forgot Password',
                    icon: 'info',
                    confirmButtonColor: '#3c8dbc',
                });
            });

            $('#checkbox-show-pass').on('change', function() {
                const passwordField = $('#lock');

                if ($(this).is(':checked')){
                    passwordField.attr('type', 'text'); // Show password
                } else {
                    passwordField.attr('type', 'password'); // Hide password
                }
            });
        </script>
    </body>
</html>