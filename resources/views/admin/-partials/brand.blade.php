<div class="app-brand navbar-brand py-5{{ $preferences->get('horizontal_menu') ? ' d-none d-xl-flex' : '' }}">
    <a href="{{ cms_route('dashboard.index') }}" class="app-brand-link">
        <span class="app-brand-logo">
            <span class="text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="23" viewbox="0 0 470 358" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" fill="none" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <path fill="currentColor" d="M 171.5,-0.5 C 213.5,-0.5 255.5,-0.5 297.5,-0.5C 297.343,0.873441 297.51,2.20677 298,3.5C 354.941,121.047 412.108,238.381 469.5,355.5C 469.5,356.167 469.5,356.833 469.5,357.5C 427.833,357.5 386.167,357.5 344.5,357.5C 308.389,281.944 271.722,206.61 234.5,131.5C 197.294,206.579 160.627,281.913 124.5,357.5C 82.8333,357.5 41.1667,357.5 -0.5,357.5C -0.5,356.833 -0.5,356.167 -0.5,355.5C 56.892,238.381 114.059,121.047 171,3.5C 171.49,2.20677 171.657,0.873441 171.5,-0.5 Z"/>
                </svg>
            </span>
        </span>
        <span class="app-brand-text menu-text fw-bold fs-4{{ $preferences->get('horizontal_menu') ? ' text-heading' : '' }}">CMS</span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto{{ $preferences->get('horizontal_menu') ? ' d-xl-none' : '' }}">
        @if ($preferences->get('horizontal_menu'))
            <i class="icon-base fa fa-xmark icon-sm d-flex align-items-center justify-content-center"></i>
        @else
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base fa fa-xmark d-block d-xl-none"></i>
        @endif
    </a>
</div>
