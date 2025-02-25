<li>
    <a href="{{ cms_url('/') }}">
        <i class="{{icon_type('dashboard')}}" title="Dashboard"></i>
        <span class="title">Home</span>
    </a>
</li>
<li>
    <a href="{{ $url = cms_route('cmsUsers.index') }}">
        <i class="{{icon_type('cmsUsers')}}" title="CMS Users"></i>
        <span class="title">CMS Users</span>
    </a>
</li>
<li>
    <a href="{{ $url = cms_route('cmsSettings.index') }}">
        <i class="fa fa-gear" title="CMS Settings"></i>
        <span class="title">CMS Settings</span>
    </a>
</li>
