<!DOCTYPE html>
<html lang="{{language()->active()}}">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="robots" content="noindex, nofollow">
    <title>@stack('head.title')</title>
    <link rel="shortcut icon" href="{{ asset('assets/default/img/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/default/libs/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <script src="{{ asset('assets/default/libs/jquery/jquery-3.7.1.min.js') }}"></script>
</head>
<body>
<div id="app">
    <header id="header" class="border-bottom">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    {{-- <a class="navbar-brand" href="#">Logo</a> --}}
                    <div class="collapse navbar-collapse">
                        @include('web.-partials.pages')
                        @include('web.-partials.lang')
                    </div>
                </div>
                <!-- .container-fluid -->
            </nav>
            <!-- .navbar -->
        </div>
        <!-- .container -->
    </header>
    <!-- #header -->
    <main id="main">
        <div class="container">
            @yield('content')
        </div>
        <!-- .container -->
    </main>
    <!-- #main -->
</div>
<!-- #app -->
<footer id="footer" class="pt-4">
    <div class="container">
        <div class="copyright text-center">&copy; {{date('Y')}}</div>
        <!-- .copyright -->
    </div>
    <!-- .container -->
</footer>
<!-- #footer -->
</body>
</html>
