<div class="nav-align-top">
    <ul class="nav nav-pills flex-column flex-md-row gap-md-0 gap-2 mb-6">
        <li class="nav-item">
            <a class="nav-link{{ $routeMatches(['cmsUsers.show'], [], false) ? ' active' : '' }}" href="{{ cms_route('cmsUsers.show', [$current->id]) }}">
                <i class="icon-base fa fa-user-check icon-sm me-2"></i>
                Profile
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $routeMatches(['cmsUsers.edit'], [], false) ? ' active' : '' }}" href="{{ cms_route('cmsUsers.edit', [$current->id]) }}">
                <i class="icon-base fa fa-user-gear icon-sm me-2"></i>
                Account
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{ $routeMatches(['cmsUsers.security'], [], false) ? ' active' : '' }}" href="{{ cms_route('cmsUsers.security', [$current->id]) }}">
                <i class="icon-base fa fa-user-shield icon-sm me-2"></i>
                Security
            </a>
        </li>
    </ul>
</div>

