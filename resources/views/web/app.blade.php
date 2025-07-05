<!DOCTYPE html>
<html lang="{{language()->active()}}">
@include('web.-partials.head')
<body>
<div id="app">
    @include('web.-partials.header')
    <main id="main">
        @yield('content')
    </main>
    <!-- #main -->
</div>
<!-- #app -->
@include('web.-partials.footer')
@include('web.-partials.scripts')
@stack('body.bottom')
@include('web.-partials.trans-form')
</body>
</html>
