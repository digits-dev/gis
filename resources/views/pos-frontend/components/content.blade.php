<!DOCTYPE html>
<html lang="en">
<head>
    @include('pos-frontend.plugins.pos-frontend-plugin')
    @yield('plugins')
    @yield('title')
    @yield('css')
</head>
<body>
    <section class="main_section">
        {{-- Sidebar and Content Script --}}
        @include('pos-frontend.components.sidebar')
        @include('pos-frontend.components.topbar')
    </section>

    {{-- Frontend script --}}
    @include('pos-frontend.plugins.pos-frontend-script')
    {{-- Your Script  --}}
    @yield('script-js')
</body>
</html>
