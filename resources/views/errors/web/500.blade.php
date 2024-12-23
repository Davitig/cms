<!DOCTYPE html>
<html lang="{{language()}}">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="500 Internal Server Error">
    <title>500 Internal Server Error</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" media="all" href="{{asset('assets/css/main.css')}}">
    <script src="{{asset('assets/libs/js/jquery-1.11.3.min.js')}}"></script>
</head>
<body>
<div id="root">
    <header id="header">
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <!-- .navbar-header -->
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="{{web_url('/', [], is_multilanguage(true))}}">{{trans('general.home')}}</a>
                        </li>
                    </ul>
                    @include('web._partials.lang')
                </div>
                <!-- #navbar -->
            </div>
            <!-- .container -->
        </nav>
        <!-- .navbar -->
    </header>
    <!-- #header -->
    <main id="main">
        <div id="content">
            <div class="container">
                <div id="error">
                    <div id="message" class="text-center">
                        <h1>500</h1>
                        <h2>Internal Server Error</h2>
                    </div>
                    <!-- #message -->
                </div>
                <!-- #error -->
            </div>
        </div>
        <!-- #content -->
    </main>
    <!-- #main -->
</div>
<!-- #root -->
<footer id="footer">
    <div class="container">
        <div class="copyright text-center">&copy; {{date('Y')}}</div>
        <!-- .copyright -->
    </div>
    <!-- .container -->
</footer>
<!-- #footer -->
@include('web._partials.scripts')
</body>
</html>
