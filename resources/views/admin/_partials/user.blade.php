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
            <a href="{{web_url('/')}}" target="_blank" title="Go to website"><i class="fa fa-desktop"></i></a>
        </li>
        <li class="dropdown user-profile">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="{{ auth('cms')->user()->photo }}" alt="user-image" class="img-circle img-inline userpic-32"
                     width="28">
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
</nav>
