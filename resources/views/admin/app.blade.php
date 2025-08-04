<!DOCTYPE html>
<html
    lang="en"
    class="layout-navbar-fixed layout-menu-fixed layout-compact base-{{ ($isHorizontalMenu = $preferences->get('horizontal_menu')) ? 'horizontal' : 'vertical' }}"
    dir="ltr"
    data-skin="default"
    data-assets-path="{{ asset('assets') }}/"
    data-template="{{ $isHorizontalMenu ? 'horizontal' : 'vertical' }}-menu-template"
    data-bs-theme="light">
@include('admin.-partials.head')
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
                    @includeWhen(
                    ! language()->countVisible() && language()->getSettings('down_without_language') ||
                    $routeMatches(['languages', 'settings.language']),
                    'admin.-alerts.maintenance-without-lang'
                    )
                    @yield('content')
                </div>
                <!--/ Content -->
                @include('admin.-partials.footer')
                <div class="content-backdrop fade"></div>
            </div>
            <!--/ Content wrapper -->
        </div>
        <!--/ Layout page -->
    </div>
</div>
@include('admin.-partials.scripts')
@stack('body.bottom')
</body>
</html>
