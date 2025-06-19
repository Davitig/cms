<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Content Management System">
    @stack('head.title')
    <link rel="shortcut icon" href="{{ asset('assets/libs/images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/fonts/fontawesome-6.7.2/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/xenon-core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/xenon-components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/custom.css') }}">
    <script src="{{ asset('assets/libs/js/jquery-1.11.1.min.js') }}"></script>
</head>
<body class="page-body">
<div id="container"><div class="settings-pane">
        <a href="#" data-toggle="settings-pane" data-animate="true">
            &times;
        </a>
        <div class="settings-pane-inner">
            <div class="row">
                <div class="col-md-4">
                    <div class="user-info">
                        <div class="user-image">
                            <a href="{{ cms_route('cmsUsers.show', [auth('cms')->id()]) }}">
                                <img src="{{ cms_route('cmsUsers.photo', [auth('cms')->id()]) }}" width="130" height="130" class="img-circle" alt="User Photo">
                            </a>
                        </div>
                        <div class="user-details">
                            <h3>
                                <a href="{{ cms_route('cmsUsers.show', [auth('cms')->id()]) }}">
                                    {{ auth('cms')->user()->first_name }}
                                </a>
                                <!-- Available statuses: is-online, is-idle, is-busy and is-offline -->
                                <span class="user-status is-online"></span>
                            </h3>
                            <div class="user-links">
                                <a href="{{cms_route('cmsUsers.edit', [auth('cms')->id()])}}" class="btn btn-primary">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 link-blocks-env">
                    <div class="links-block left-sep">
                        <h4>
                            <span>Notifications</span>
                        </h4>
                    </div>
                    <div class="links-block left-sep">
                        <h4>
                            <span>Help Desk</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-container">
        <!-- Add "fixed" class to make the sidebar fixed always to the browser viewport. -->
        <!-- Adding class "toggle-others" will keep only one menu item open at a time. -->
        <!-- Adding class "collapsed" collapse sidebar root elements and show only icons. -->
        <div class="sidebar-menu toggle-others fixed">
            <div class="sidebar-menu-inner">
                <header class="logo-env">
                    <!-- logo -->
                    <div class="logo">
                        <a href="{{ cms_url('/') }}">
                            <div class="logo-expanded">
                                <div class="name">CMS</div>
                            </div>
                            <div class="logo-collapsed">
                                <div class="name">CMS</div>
                            </div>
                        </a>
                    </div>
                    <!-- This will toggle the mobile menu and will be visible only on mobile devices -->
                    <div class="mobile-menu-toggle visible-xs">
                        <a href="#" data-toggle="mobile-menu">
                            <i class="fa fa-bars"></i>
                        </a>
                    </div>
                    <!-- This will open the popup with user profile settings, you can use for any purpose, just be creative -->
                    <div class="settings-icon">
                        <a href="#" data-toggle="settings-pane" data-animate="true">
                            <i class="fa fa-gear"></i>
                        </a>
                    </div>
                </header>
                <ul id="main-menu" class="main-menu">
                    <!-- add class "multiple-expanded" to allow multiple submenus to open -->
                    <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
                    @include('errors.admin.menu')
                </ul>
            </div>
        </div>
        <div class="main-content">
            <nav class="navbar user-info-navbar" role="navigation"><!-- User Info, Notifications and Menu Bar -->
                <ul class="user-info-menu left-links list-inline list-unstyled">
                    <li class="hidden-sm hidden-xs">
                        <a href="#" data-toggle="sidebar">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                    <li class="dropdown hover-line language-switcher">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset('assets/libs/images/flags/'.language()->active().'.png') }}" width="30" height="20" alt="{{language()->getActive('full_name')}}">
                        </a>
                        <ul class="dropdown-menu languages">
                            @foreach (language()->all() as $key => $value)
                                <li data-id="{{$value['id']}}">
                                    <a href="{{url($value['path'])}}">
                                        <img src="{{ asset('assets/libs/images/flags/'.$key.'.png') }}" width="30" height="20" alt="{{$value['full_name']}}">
                                        {{ $value['full_name'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                <!-- Right links for user info navbar -->
                <ul class="user-info-menu right-links list-inline list-unstyled">
                    <li>
                        <a href="{{web_url('/')}}" target="_blank" title="Go to website">
                            <i class="fa fa-globe"></i>
                        </a>
                    </li>
                    <li class="dropdown user-profile">
                        <a href="{{ cms_route('cmsUsers.edit', [auth('cms')->id()]) }}" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ cms_route('cmsUsers.photo', [auth('cms')->id()]) }}" alt="User Photo" class="img-circle img-inline userpic-32"
                                 width="28">
                            <span>
                                {{ auth('cms')->user()->first_name }}
                                <i class="fa fa-angle-down"></i>
                            </span>
                        </a>
                        <ul class="dropdown-menu user-profile-menu list-unstyled">
                            <li>
                                <a href="{{cms_route('cmsUsers.index')}}">
                                    <i class="{{icon_type('cmsUsers')}}"></i>
                                    CMS Users
                                </a>
                            </li>
                            <li>
                                <a href="{{cms_route('cmsUsers.edit', [auth('cms')->id()])}}">
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
            @yield('content')
            <!-- Main Footer -->
            <!-- Choose between footer styles: "footer-type-1" or "footer-type-2" -->
            <!-- Add class "sticky" to  always stick the footer to the end of page (if page contents is small) -->
            <!-- Or class "fixed" to  always fix the footer to the end of page -->
            <footer class="main-footer sticky">
                <div class="footer-inner">
                    <!-- Add your copyright text here -->
                    <div class="footer-text">
                        &copy; {{date('Y')}} - {{config('app.name')}} - Version: {{cms_config('version')}}
                    </div>
                    <!-- Go to Top Link, just add rel="go-top" to any link to add this functionality -->
                    <div class="go-up">
                        <a href="#" rel="go-top">
                            <i class="fa fa-angle-up"></i>
                        </a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>
<script src="{{ asset('assets/libs/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/libs/js/TweenMax.min.js') }}"></script>
<script src="{{ asset('assets/libs/js/resizeable.js') }}"></script>
<script src="{{ asset('assets/libs/js/joinable.js') }}"></script>
<script src="{{ asset('assets/libs/js/xenon-api.js') }}"></script>
<script src="{{ asset('assets/libs/js/xenon-toggles.js') }}"></script>
<script src="{{ asset('assets/libs/js/xenon-custom.js') }}"></script>
</body>
</html>
