<!-- Navbar pills -->
<div class="nav-align-top">
    <ul class="nav nav-pills flex-column flex-md-row gap-md-0 gap-2 mb-6">
        <li class="nav-item">
            <a href="{{ cms_route('cms_users.show', [$current->id]) }}"
               class="nav-link{{ $routeMatches(['cms_users.show'], [], false) ? ' active' : '' }}">
                <i class="icon-base fa fa-user-check icon-sm me-2"></i>
                Profile
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ cms_route('cms_users.edit', [$current->id]) }}"
               class="nav-link{{ $routeMatches(['cms_users.edit'], [], false) ? ' active' : '' }}">
                <i class="icon-base fa fa-user-pen icon-sm me-2"></i>
                Account
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ cms_route('cms_users.security', [$current->id]) }}"
               class="nav-link{{ $routeMatches(['cms_users.security'], [], false) ? ' active' : '' }}">
                <i class="icon-base fa fa-user-shield icon-sm me-2"></i>
                Security
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ cms_route('cms_users.preferences.index', [$current->id]) }}"
               class="nav-link{{ $routeMatches(['cms_users.preferences.index'], [], false) ? ' active' : '' }}">
                <i class="icon-base fa fa-sliders icon-sm me-2"></i>
                Preferences
            </a>
        </li>
    </ul>
</div>
<!--/ Navbar pills -->
