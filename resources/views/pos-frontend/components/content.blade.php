<!DOCTYPE html>
<html lang="en">
<head>
    @include('pos-frontend.plugins.pos-frontend-plugin')
    @yield('plugins')
    <title>Gashapon</title>
    @yield('css')
</head>
<body>
@auth    
<section class="main_section">
    @yield('cash-float')
    @yield('cash-float-end')
    {{-- Sidebar and Content Script --}}
    @include('pos-frontend.components.sidebar')
    @include('pos-frontend.components.topbar')
</section>

{{-- Frontend script --}}
@include('pos-frontend.plugins.pos-frontend-script')

{{-- Your Script  --}}
@yield('script-js')
@endauth
</body>
</html>
