<li>
    <a href="{{ cms_url('/') }}">
        <i class="{{icon_type('dashboard')}}" title="Dashboard"></i>
        <span class="title">Home</span>
    </a>
</li>
<li>
    <a href="{{ $url = cms_route('cmsUsers.index') }}">
        <i class="fa fa-users" title="User Groups"></i>
        <span class="title">User Groups</span>
    </a>
    <ul>
        <li>
            <a href="{{ $url }}">
                <i class="{{icon_type('cmsUsers')}}" title="CMS Users"></i>
                <span class="title">CMS Users</span>
            </a>
        </li>
    </ul>
</li>
<li>
    <a href="{{ $url = cms_route('settings.index') }}">
        <i class="fa fa-gears" title="Settings"></i>
        <span class="title">Settings</span>
    </a>
    <ul>
        <li>
            <a href="{{ $url }}">
                <i class="fa fa-gear" title="Admin Settings"></i>
                <span class="title">CMS Settings</span>
            </a>
        </li>
    </ul>
</li>
