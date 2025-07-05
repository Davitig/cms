<!doctype html>
<html
    lang="en"
    class="layout-navbar-fixed layout-menu-fixed layout-compact base-{{ ($isHorizontalMenu = $preferences->get('horizontal_menu')) ? 'horizontal' : 'vertical' }}"
    dir="ltr"
    data-skin="default"
    data-assets-path="{{ asset('assets') }}/"
    data-template="{{ $isHorizontalMenu ? 'horizontal' : 'vertical' }}-menu-template"
    data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="Content Management System">
    <meta name="robots" content="noindex, nofollow">
    <meta name="version" content="{{cms_config('version')}}">
    <title>@stack('head.title')</title>
    <link rel="shortcut icon" href="{{ asset('assets/default/img/favicon.ico') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}">
    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
    <!-- endbuild -->
    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--? Config: Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <!-- Default -->
    <link rel="stylesheet" href="{{ asset('assets/default/css/custom.css') }}">
</head>
<body>
<!-- Layout wrapper -->
<div class="layout-wrapper {{ $isHorizontalMenu ? 'layout-navbar-full layout-horizontal layout-without-menu' : 'layout-content-navbar' }}">
    <div class="layout-container">
        @if ($isHorizontalMenu)
            @include('admin.-partials.navbar')
        @else
            @include('admin.-partials.menu')
        @endif
        <!-- Layout page -->
        <div class="layout-page">
            @if (! $isHorizontalMenu)
                @include('admin.-partials.navbar')
            @endif
            <!-- Content wrapper -->
            <div class="content-wrapper">
                @if ($isHorizontalMenu)
                    @include('admin.-partials.menu')
                @endif
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
                    @yield('content')
                </div>
                <!-- / Content -->
                @include('admin.-partials.footer')
                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>
</div>
<!-- Core JS -->
<script src="{{ asset('assets/default/libs/jquery/jquery-3.7.1.min.js') }}"></script>
<!-- build:js assets/vendor/js/theme.js -->
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->
<!-- Main JS -->
<script src="{{ asset('assets/js/main.js') }}"></script>
<!-- Default -->
<script src="{{ asset('assets/default/js/custom.js') }}"></script>
@stack('body.bottom')
</body>
</html>
