<nav class="navbar user-info-navbar" role="navigation"><!-- User Info, Notifications and Menu Bar -->
    <!-- Left links for user info navbar -->
    <ul class="user-info-menu left-links list-inline list-unstyled">
        <li class="hidden-sm hidden-xs">
            <a href="#" data-toggle="sidebar">
                <i class="fa fa-bars"></i>
            </a>
        </li>
        @include('admin._partials.user_menu')
        @include('admin._partials.user_lang')
    </ul>
    <!-- Right links for user info navbar -->
    <ul class="user-info-menu right-links list-inline list-unstyled">
        @include('admin._partials.user_profile')
    </ul>
</nav>
