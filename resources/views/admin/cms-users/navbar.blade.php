<div class="nav-align-top">
    <ul class="nav nav-pills flex-column flex-md-row gap-md-0 gap-2 mb-6">
        <li class="nav-item">
            <a href="{{ cms_route('cmsUsers.show', [$current->id]) }}"
               class="nav-link{{ $routeMatches(['cmsUsers.show'], [], false) ? ' active' : '' }}">
                <i class="icon-base fa fa-user-check icon-sm me-2"></i>
                Profile
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ cms_route('cmsUsers.edit', [$current->id]) }}"
               class="nav-link{{ $routeMatches(['cmsUsers.edit'], [], false) ? ' active' : '' }}">
                <i class="icon-base fa fa-user-pen icon-sm me-2"></i>
                Account
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ cms_route('cmsUsers.security', [$current->id]) }}"
               class="nav-link{{ $routeMatches(['cmsUsers.security'], [], false) ? ' active' : '' }}">
                <i class="icon-base fa fa-user-shield icon-sm me-2"></i>
                Security
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ cms_route('cmsUsers.preferences.index', [$current->id]) }}"
               class="nav-link{{ $routeMatches(['cmsUsers.preferences.index'], [], false) ? ' active' : '' }}">
                <i class="icon-base fa fa-sliders icon-sm me-2"></i>
                Preferences
            </a>
        </li>
    </ul>
</div>

