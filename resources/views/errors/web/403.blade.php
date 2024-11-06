<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="403 Forbidden">
    <title>403 Forbidden</title>
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Ubuntu:400,300'>
    <link rel="stylesheet" href="{{ asset('assets/libs/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/error.css') }}">
</head>
<body>
    <div id="error">
        <div id="message" class="text-center">
            <h1>403</h1>
            <h2>Forbidden, <span>This action is unauthorized!</span></h2>
        </div>
        <!-- #message -->
    @if (! request()->filled('iframe'))
        <div id="btn" class="text-center">
            <a href="{{redirect()->intended()->getTargetUrl()}}" class="text-uppercase">Go Back</a>
        </div>
        <!-- #btn -->
    @endif
    </div>
    <!-- #error -->
</body>
</html>
