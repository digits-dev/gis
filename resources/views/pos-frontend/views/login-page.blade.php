<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}">
        <title>Gashapon POS Login Page</title>
        <link rel="stylesheet" href="{{ asset('css/pos-frontend.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/crudbooster/assets/adminlte/font-awesome/css/font-awesome.min.css') }}">
    </head>
    <body>
        <section class="login">
            <form action="{{ route('login') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="login-box">
                    <div class="store-logo-box">
                        <div class="store-logo-box-content">
                            <div class="store-logo-brand">
                                <img src="{{ asset('img/gashapon_logo.png') }}" alt="">
                            </div>
                            <div class="store-logo-link-to-frontend">
                                <a href="/admin/login">Link to Backend</a>
                            </div>
                            <div class="store-logo-description danger-color">
                                <p class="fs-40 fw-bold">Inventory System</p>
                                <p class="fs-30 fw-bold">Frontend</p>
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
                            <div>
                                <button type="submit" class="login-btn fw-bold">Login</button>
                            </div>
                            <div class="fw-bold title-color fs-15">
                                <p>Forgot the password? <a href="/admin/forgot" class="primary-color cursor-p">Click here</a></p>
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
    </body>
</html>