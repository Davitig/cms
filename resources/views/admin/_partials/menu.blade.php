<li>
    <a href="{{ cms_url('/') }}"{!! $routeMatches(['dashboard']) ? ' class="active"' : '' !!}>
        <i class="{{icon_type('dashboard')}}" title="Dashboard"></i>
        <span class="title">Home</span>
    </a>
</li>
@if ($userRouteAccess('menus.index', 'pages.index'))
    <li{!! $routeMatches(['menus', 'pages', 'pages.files']) ? ' class="has-sub expanded"' : '' !!}>
        <a href="{{ $url = cms_route('menus.index') }}">
            <i class="fa fa-sitemap" title="Site Map"></i>
            <span class="title">Site Map</span>
        </a>
        <ul>
            @if (! empty($menus) && $userRouteAccess('pages.index'))
                @foreach ($menus as $item)
                    <li>
                        <a href="{{ cms_route('pages.index', [$item->id]) }}"{!! $routeMatches([
                            'pages', 'pages.files' => $activeMenuId ?? null
                        ], ['menu' => $item->id]) ? ' class="active"' : '' !!}>
                            <i class="{{icon_type('pages')}}" title="Pages"></i>
                            <span class="title">{{ $item->title }}</span>
                        </a>
                    </li>
                @endforeach
            @endif
            @if ($userRouteAccess('menus.index'))
                <li>
                    <a href="{{ $url }}"{!! $routeMatches(['menus']) ? ' class="active"' : '' !!}>
                        <i class="{{icon_type('menus')}}" title="Menus"></i>
                        <span class="title">Menu Management</span>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
@if ($userRouteAccess('products.index'))
    <li>
        <a href="{{ cms_route('products.index') }}"{!! $routeMatches(['products']) ? ' class="active"' : '' !!}>
            <i class="{{icon_type('products')}}" title="Products"></i>
            <span class="title">Products</span>
        </a>
    </li>
@endif
@if ($userRouteAccess('collections.index'))
    <li>
        <a href="{{ cms_route('collections.index') }}"{!! $routeMatches(['collections']) ? ' class="active"' : '' !!}>
            <i class="{{icon_type('collections')}}" title="Collections"></i>
            <span class="title">Collections</span>
        </a>
    </li>
@endif
@if ($userRouteAccess('filemanager'))
    <li>
        <a href="{{ cms_route('filemanager') }}"{!! $routeMatches(['filemanager']) ? ' class="active"' : '' !!}>
            <i class="fa fa-file-import" title="File Manager"></i>
            <span class="title">File Manager</span>
        </a>
    </li>
@endif
@if ($userRouteAccess('languages.index'))
    <li>
        <a href="{{ cms_route('languages.index') }}"{!! $routeMatches(['languages']) ? ' class="active"' : '' !!}>
            <i class="{{icon_type('languages')}}" title="Languages"></i>
            <span class="title">Languages</span>
        </a>
    </li>
@endif
<li{!! $routeMatches(['cmsUsers', 'cmsUserRoles']) ? ' class="has-sub expanded"' : '' !!}>
    <a href="{{ $url = cms_route('cmsUsers.index') }}">
        <i class="fa fa-users" title="User Groups"></i>
        <span class="title">User Groups</span>
    </a>
    <ul>
        @if (auth('cms')->user()->hasFullAccess())
            <li>
                <a href="{{ cms_route('cmsUserRoles.index') }}"{!! $routeMatches(['cmsUserRoles']) ? ' class="active"' : '' !!}>
                    <i class="{{icon_type('roles')}}" title="CMS User Roles"></i>
                    <span class="title">CMS User Roles</span>
                </a>
            </li>
        @endif
        <li>
            <a href="{{ $url }}"{!! $routeMatches(['cmsUsers']) ? ' class="active"' : '' !!}>
                <i class="{{icon_type('cmsUsers')}}" title="CMS Users"></i>
                <span class="title">CMS Users</span>
            </a>
        </li>
    </ul>
</li>
@if ($userRouteAccess('cmsSettings', 'webSettings', 'translations'))
<li{!! $routeMatches(['cmsSettings', 'webSettings', 'translations']) ? ' class="has-sub expanded"' : '' !!}>
    <a href="{{ $url = cms_route('cmsSettings.index') }}">
        <i class="fa fa-gears" title="Settings"></i>
        <span class="title">Settings</span>
    </a>
    <ul>
        @if ($userRouteAccess('cmsSettings'))
        <li>
            <a href="{{ $url }}"{!! $routeMatches(['cmsSettings']) ? ' class="active"' : '' !!}>
                <i class="fa fa-gear" title="CMS Settings"></i>
                <span class="title">CMS Settings</span>
            </a>
        </li>
        @endif
        @if (auth('cms')->user()->hasFullAccess())
            <li>
                <a href="{{ cms_route('webSettings.index') }}"{!! $routeMatches(['webSettings']) ? ' class="active"' : '' !!}>
                    <i class="fa fa-gear" title="Web Settings"></i>
                    <span class="title">Web Settings</span>
                </a>
            </li>
            <li>
                <a href="{{ cms_route('translations.index') }}"{!! $routeMatches(['translations']) ? ' class="active"' : '' !!}>
                    <i class="{{icon_type('translations')}}" title="Translations"></i>
                    <span class="title">Translations</span>
                </a>
            </li>
        @endif
    </ul>
</li>
@endif
@if ($userRouteAccess('calendar.index', 'slider.index', 'notes.index'))
    <li{!! $routeMatches(['calendar', 'slider', 'notes']) ? ' class="has-sub expanded"' : '' !!}>
        <a href="{{ $url = cms_route('calendar.index') }}">
            <i class="fa fa-flask" title="Extra"></i>
            <span class="title">Extra</span>
        </a>
        <ul>
            @if ($userRouteAccess('slider.index'))
                <li>
                    <a href="{{ cms_route('slider.index') }}"{!! $routeMatches(['slider']) ? ' class="active"' : '' !!}>
                        <i class="fa fa-image" title="Slider"></i>
                        <span class="title">Slider</span>
                    </a>
                </li>
            @endif
            @if ($userRouteAccess('calendar.index'))
                <li>
                    <a href="{{ $url }}"{!! $routeMatches(['calendar']) ? ' class="active"' : '' !!}>
                        <i class="fa fa-calendar" title="Calendar"></i>
                        <span class="title">Calendar</span>
                    </a>
                </li>
            @endif
            @if ($userRouteAccess('notes.index'))
                <li>
                    <a href="{{ cms_route('notes.index') }}"{!! $routeMatches(['notes']) ? ' class="active"' : '' !!}>
                        <i class="fa fa-file-text" title="notes"></i>
                        <span class="title">Notes</span>
                    </a>
                </li>
            @endif
        </ul>
    </li>
@endif
