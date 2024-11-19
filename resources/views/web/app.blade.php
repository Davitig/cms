<!DOCTYPE html>
<html lang="{{language()}}">
@include('web._partials.head')
<body>
<div id="root">
    @include('web._partials.header')
    <main id="main">
        <div id="content">
            @yield('content')
        </div>
        <!-- #content -->
    </main>
    <!-- #main -->
</div>
<!-- #root -->
@include('web._partials.footer')
@include('web._partials.scripts')
@stack('body.bottom')
@include('web._partials.trans_form')
</body>
</html>
