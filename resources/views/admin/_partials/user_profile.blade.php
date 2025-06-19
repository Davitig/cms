<li>
    <form method="post" action="{{cms_route('lockscreen.lock')}}" id="set-lockscreen">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button type="submit" class="btn btn-link" title="Lockscreen">
            <i class="fa fa-lock"></i>
        </button>
    </form>
</li>
<li>
    <a href="{{web_url('/')}}" id="web-url" target="_blank" title="Go to website">
        <i class="fa fa-globe"></i>
    </a>
</li>
<li class="dropdown user-profile">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img src="{{ cms_route('cmsUsers.photo', [auth('cms')->id()]) }}" alt="User Photo" class="img-circle img-inline userpic-32" width="30" height="30">
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
