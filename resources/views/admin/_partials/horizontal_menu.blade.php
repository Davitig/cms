<nav class="navbar horizontal-menu{{$cmsSettings->get('layout_boxed') ? '' : ' navbar-fixed-top'}} {{$cmsSettings->get('horizontal_menu_minimal')}}"><!-- set fixed position by adding class "navbar-fixed-top" -->
    <div class="navbar-inner">
        <!-- Navbar Brand -->
        <div class="navbar-brand">
            <a href="{{ cms_url('/') }}" class="logo">
                <span class="name">CMS</span>
            </a>
            <a href="#" data-toggle="settings-pane" data-animate="true">
                <i class="fa fa-gear"></i>
            </a>
        </div>
        <!-- Mobile Toggles Links -->
        <div class="nav navbar-mobile">
            <!-- This will toggle the mobile menu and will be visible only on mobile devices -->
            <div class="mobile-menu-toggle">
                <!-- This will open the popup with user profile settings, you can use for any purpose, just be creative -->
                <a href="#" data-toggle="settings-pane" data-animate="true">
                    <i class="fa fa-gear"></i>
                </a>
                <!-- data-toggle="mobile-menu-horizontal" will show horizontal menu links only -->
                <!-- data-toggle="mobile-menu" will show sidebar menu links only -->
                <!-- data-toggle="mobile-menu-both" will show sidebar and horizontal menu links -->
                <a href="#" data-toggle="mobile-menu-horizontal">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
        </div>
        <div class="navbar-mobile-clear"></div>
        <!-- main menu -->
        <ul class="navbar-nav {{$cmsSettings->get('horizontal_menu_click')}}">
            @include('admin._partials.menu')
        </ul>
        <!-- notifications and other links -->
        <ul class="nav nav-userinfo navbar-right">
            @include('admin._partials.user_lang')
            @include('admin._partials.user_menu')
            @include('admin._partials.user_profile')
        </ul>
    </div>
</nav>
