<!-- Menu -->
<aside id="layout-menu" class="menu {{ ($isHorizontalMenu = $preferences->get('horizontal_menu')) ? 'layout-menu-horizontal menu-horizontal flex-grow-0' : ' layout-menu menu-vertical' }}">
    @if ($preferences->get('horizontal_menu'))
        <div class="container-xxl d-flex h-100">
            @else
                @include('admin.-partials.brand')
                <div class="menu-inner-shadow"></div>
            @endif
            <ul @class(['menu-inner', 'py-1' => ! $isHorizontalMenu])>
                <li @class(['menu-item', 'active' => $routeMatches(['dashboard'])])>
                    <a href="{{ cms_route('dashboard.index') }}" class="menu-link">
                        <i class="menu-icon icon-base fa fa-dashboard icon-20px"></i>
                        <div>Dashboards</div>
                    </a>
                </li>
                @if ($userRouteAccess('menus.index', 'pages.index'))
                    <li @class(['menu-item', 'active' => $matches = $routeMatches([
                    'menus', 'pages'
                    ]), 'open' => ! $isHorizontalMenu && $matches])>
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon icon-base fa fa-sitemap icon-20px"></i>
                            <div>Site Map</div>
                        </a>
                        <ul class="menu-sub">
                            @if (! is_null($menu) && $userRouteAccess('pages.index'))
                                <li @class(['menu-item', 'active' => $routeMatches([
                                'pages', 'pages.files' => $activeMenuId ?? null], true, ['menu' => $menu->id])
                                ])>
                                    <a href="{{ cms_route('pages.index', [$menu->id]) }}" class="menu-link">
                                        <i class="menu-icon icon-base fa fa-indent icon-18px"></i>
                                        <div>{{ $menu->title }}</div>
                                    </a>
                                </li>
                            @endif
                            @if ($userRouteAccess('menus.index'))
                                <li @class(['menu-item', 'active' => $routeMatches(['menus'])])>
                                    <a href="{{ cms_route('menus.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base fa fa-list icon-18px"></i>
                                        <div>Menus</div>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if ($userRouteAccess('products.index'))
                    <li @class(['menu-item', 'active' => $routeMatches(['products', 'products.files'])])>
                        <a href="{{ cms_route('products.index') }}" class="menu-link">
                            <i class="menu-icon icon-base fa fa-store icon-20px"></i>
                            <div>Products</div>
                        </a>
                    </li>
                @endif
                @if ($userRouteAccess('collections.index'))
                    <li @class(['menu-item', 'active' => $routeMatches([
                    'collections', 'articles', 'events'
                    ])])>
                        <a href="{{ cms_route('collections.index') }}" class="menu-link">
                            <i class="menu-icon icon-base fa fa-list-alt icon-20px"></i>
                            <div>Collections</div>
                        </a>
                    </li>
                @endif
                @if ($userRouteAccess('file_manager'))
                    <li @class(['menu-item', 'active' => $routeMatches(['file_manager'])])>
                        <a href="{{ cms_route('file_manager') }}" class="menu-link">
                            <i class="menu-icon icon-base fa fa-file-import icon-20px"></i>
                            <div>File Manager</div>
                        </a>
                    </li>
                @endif
                @if ($userRouteAccess('languages.index'))
                    <li @class(['menu-item', 'active' => $routeMatches(['languages'])])>
                        <a href="{{ cms_route('languages.index') }}" class="menu-link">
                            <i class="menu-icon icon-base fa fa-language icon-18px me-3"></i>
                            <div>Languages</div>
                        </a>
                    </li>
                @endif
                <li @class(['menu-item', 'active' => $matches = $routeMatches([
                'cms_users', 'cms_user_roles', 'permissions'
                ]), 'open' => ! $isHorizontalMenu && $matches])>
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon icon-base fa fa-users icon-18px me-3"></i>
                        <div>Users</div>
                    </a>
                    <ul class="menu-sub">
                        <li @class(['menu-item', 'active' => $routeMatches(['cms_users'])])>
                            <a href="{{ cms_route('cms_users.index') }}" class="menu-link">
                                <i class="menu-icon icon-base fa fa-user-tie icon-18px me-2"></i>
                                <div>CMS Users</div>
                            </a>
                        </li>
                        @if (auth('cms')->user()->hasFullAccess())
                            <li @class(['menu-item', 'active' => $routeMatches(['cms_user_roles'])])>
                                <a href="{{ cms_route('cms_user_roles.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base fa fa-user-tag icon-16px me-3"></i>
                                    <div>Roles</div>
                                </a>
                            </li>
                            <li @class(['menu-item', 'active' => $routeMatches(['permissions'])])>
                                <a href="{{ cms_route('permissions.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base fa fa-lock icon-16px me-3"></i>
                                    <div>Permissions</div>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                @if ($userRouteAccess('settings.index'))
                    <li @class(['menu-item', 'active' => $matches = $routeMatches([
                    'settings', 'translations'
                    ]), 'open' => ! $isHorizontalMenu && $matches])>
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon icon-base fa fa-gear icon-20px"></i>
                            <div>Settings</div>
                        </a>
                        <ul class="menu-sub">
                            <li @class(['menu-item', 'active' => $routeMatches(['settings'])])>
                                <a href="{{ cms_route('settings.index') }}" class="menu-link">
                                    <i class="menu-icon icon-base fa fa-gears icon-18px"></i>
                                    <div>Settings</div>
                                </a>
                            </li>
                            @if (auth('cms')->user()->hasFullAccess())
                                <li @class(['menu-item', 'active' => $routeMatches(['translations'])])>
                                    <a href="{{ cms_route('translations.index') }}" class="menu-link">
                                        <i class="menu-icon icon-base fa fa-sort-alpha-asc icon-18px"></i>
                                        <div>Translations</div>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
            @if ($preferences->get('horizontal_menu'))
        </div>
    @endif
</aside>
@if (! $preferences->get('horizontal_menu'))
    <div class="menu-mobile-toggler d-xl-none rounded-1">
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
            <i class="fa fa-bars icon-base"></i>
            <i class="fa fa-arrow-right icon-base"></i>
        </a>
    </div>
@endif
