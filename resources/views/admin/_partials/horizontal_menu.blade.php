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
            <li>
                <form method="post" action="{{cms_route('lockscreen.put')}}" id="set-lockscreen">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="put">
                    <button type="submit" class="btn btn-link" title="Lockscreen">
                        <i class="fa fa-lock"></i>
                    </button>
                </form>
            </li>
            <li>
                <a href="{{web_url('/')}}" target="_blank"><i class="fa fa-desktop"></i></a>
            </li>
            <li class="dropdown user-profile">
                <a href="#" data-toggle="dropdown">
                    <img src="{{ auth('cms')->user()->photo }}" alt="user-image" class="img-circle img-inline userpic-32" width="28">
                    <span>
                        {{auth('cms')->user()->first_name}} {{auth('cms')->user()->last_name}}
                        <i class="fa fa-angle-down"></i>
                    </span>
                </a>
                <ul class="dropdown-menu user-profile-menu list-unstyled">
                    <li>
                        <a href="{{cms_route('cmsUsers.show', [$userId = auth('cms')->id()])}}">
                            <i class="{{icon_type('cmsUsers')}}"></i>
                            Profile
                        </a>
                    </li>
                    <li>
                        <a href="{{cms_route('cmsUsers.edit', [$userId])}}">
                            <i class="fa fa-edit"></i>
                            Edit
                        </a>
                    </li>
                    <li>
                        <a href="#help">
                            <i class="fa fa-info"></i>
                            Help
                        </a>
                    </li>
                    <li class="last">
                        <form action="{{cms_route('logout')}}" method="post">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <button>
                                <i class="fa fa-sign-out"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
