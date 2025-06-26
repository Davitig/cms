<!-- Navbar -->
<nav class="layout-navbar navbar navbar-expand-xl align-items-center{{ $cmsSettings->get('horizontal_menu') ? '' : ' container-xxl navbar-detached bg-navbar-theme' }}"
     id="layout-navbar">
    @if ($cmsSettings->get('horizontal_menu'))
        <div class="container-xxl">
            @include('admin._partials.brand')
            @endif
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                    <i class="icon-base fa fa-bars icon-md"></i>
                </a>
            </div>
            <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
                @if (! $cmsSettings->get('horizontal_menu'))
                    <!-- Search -->
                    <div class="navbar-nav align-items-center">
                        <div class="nav-item navbar-search-wrapper px-md-0 px-2 mb-0">
                            <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">
                                <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete"></span>
                            </a>
                        </div>
                    </div>
                    <!-- /Search -->
                @endif
                <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                    @if ($cmsSettings->get('horizontal_menu'))
                        <!-- Search -->
                        <li class="nav-item navbar-search-wrapper btn btn-text-secondary btn-icon rounded-pill">
                            <a class="nav-item nav-link search-toggler px-0" href="javascript:void(0);">
                                <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete"></span>
                            </a>
                        </li>
                        <!-- /Search -->
                    @endif
                    @include('admin._partials.lang.dropdown')
                    <!-- Style Switcher -->
                    <!-- Website -->
                    <li class="nav-item">
                        <a href="{{ web_url('/') }}" id="website-url" class="nav-link btn btn-icon rounded-pill" target="_blank">
                            <i class="icon-base fa fa-globe icon-22px"></i>
                        </a>
                    </li>
                    <!--/ Website -->
                    <li class="nav-item dropdown">
                        <a
                            class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
                            id="nav-theme"
                            href="javascript:void(0);"
                            data-bs-toggle="dropdown">
                            <i class="icon-base fa-regular fa-sun icon-22px theme-icon-active text-heading"></i>
                            <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                            <li>
                                <button
                                    type="button"
                                    class="dropdown-item align-items-center active"
                                    data-bs-theme-value="light"
                                    aria-pressed="false">
                                    <span><i class="icon-base fa-regular fa-sun icon-22px me-3" data-icon="sun"></i>Light</span>
                                </button>
                            </li>
                            <li>
                                <button
                                    type="button"
                                    class="dropdown-item align-items-center"
                                    data-bs-theme-value="dark"
                                    aria-pressed="true">
                        <span
                        ><i class="icon-base fa fa-moon icon-22px me-3" data-icon="moon-stars"></i
                            >Dark</span>
                                </button>
                            </li>
                            <li>
                                <button
                                    type="button"
                                    class="dropdown-item align-items-center"
                                    data-bs-theme-value="system"
                                    aria-pressed="false">
                        <span>
                            <i class="icon-base fa fa-desktop icon-18px me-3" data-icon="device-desktop-analytics"></i>
                            System
                        </span>
                                </button>
                            </li>
                        </ul>
                    </li>
                    <!-- / Style Switcher-->
                    <!-- Quick links  -->
                    <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown">
                        <a
                            class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
                            href="javascript:void(0);"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false">
                            <i class="icon-base fa fa-grip icon-22px text-heading"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-0">
                            <div class="dropdown-menu-header border-bottom">
                                <div class="dropdown-header d-flex align-items-center py-3">
                                    <h6 class="mb-0 me-auto">Shortcuts</h6>
                                    <a
                                        href="javascript:void(0)"
                                        class="dropdown-shortcuts-add py-2 btn btn-text-secondary rounded-pill btn-icon"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="Add shortcuts">
                                        <i class="icon-base fa fa-plus icon-20px text-heading"></i></a>
                                </div>
                            </div>
                            <div class="dropdown-shortcuts-list scrollable-container">
                                <div class="row row-bordered overflow-visible g-0">
                                    <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                    <i class="icon-base fa fa-calendar icon-26px text-heading"></i>
                                </span>
                                        <a href="app-calendar.html" class="stretched-link">Calendar</a>
                                        <small>Appointments</small>
                                    </div>
                                    <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                    <i class="icon-base fa fa-dollar icon-26px text-heading"></i>
                                </span>
                                        <a href="app-invoice-list.html" class="stretched-link">Invoice App</a>
                                        <small>Manage Accounts</small>
                                    </div>
                                </div>
                                <div class="row row-bordered overflow-visible g-0">
                                    <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                    <i class="icon-base fa fa-user icon-26px text-heading"></i>
                                </span>
                                        <a href="app-user-list.html" class="stretched-link">User App</a>
                                        <small>Manage Users</small>
                                    </div>
                                    <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                    <i class="icon-base fa fa-user-lock icon-20px text-heading"></i>
                                </span>
                                        <a href="app-access-roles.html" class="stretched-link">Role Management</a>
                                        <small>Permission</small>
                                    </div>
                                </div>
                                <div class="row row-bordered overflow-visible g-0">
                                    <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                    <i class="icon-base fa fa-desktop icon-20px text-heading"></i>
                                </span>
                                        <a href="index.html" class="stretched-link">Dashboard</a>
                                        <small>User Dashboard</small>
                                    </div>
                                    <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                    <i class="icon-base fa fa-gear icon-22px text-heading"></i>
                                </span>
                                        <a href="pages-account-settings-account.html" class="stretched-link">Setting</a>
                                        <small>Account Settings</small>
                                    </div>
                                </div>
                                <div class="row row-bordered overflow-visible g-0">
                                    <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                    <i class="icon-base fa fa-circle-question icon-26px text-heading"></i>
                                </span>
                                        <a href="pages-faq.html" class="stretched-link">FAQs</a>
                                        <small>FAQs & Articles</small>
                                    </div>
                                    <div class="dropdown-shortcuts-item col">
                                <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                                    <i class="icon-base fa-regular fa-square icon-26px text-heading"></i>
                                </span>
                                        <a href="modal-examples.html" class="stretched-link">Modals</a>
                                        <small>Useful Popups</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- Quick links -->
                    <!-- Notification -->
                    <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                        <a
                            class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
                            href="javascript:void(0);"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false">
                    <span class="position-relative">
                      <i class="icon-base fa-regular fa-bell icon-22px text-heading"></i>
                      <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
                    </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-0">
                            <li class="dropdown-menu-header border-bottom">
                                <div class="dropdown-header d-flex align-items-center py-3">
                                    <h6 class="mb-0 me-auto">Notification</h6>
                                    <div class="d-flex align-items-center h6 mb-0">
                                        <span class="badge bg-label-primary me-2">8 New</span>
                                        <a
                                            href="javascript:void(0)"
                                            class="dropdown-notifications-all p-2 btn btn-icon"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Mark all as read">
                                            <i class="icon-base fa fa-envelope-open-text text-heading"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li class="dropdown-notifications-list scrollable-container">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar">
                                                    <img src="{{ asset('assets/default/img/avatar.png') }}" alt class="rounded-circle" />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="small mb-1">Congratulation Lettie 🎉</h6>
                                                <small class="mb-1 d-block text-body">Won the monthly best seller gold badge</small>
                                                <small class="text-body-secondary">1h ago</small>
                                            </div>
                                            <div class="flex-shrink-0 dropdown-notifications-actions">
                                                <a href="javascript:void(0)" class="dropdown-notifications-read">
                                                    <span class="badge badge-dot"></span>
                                                </a>
                                                <a href="javascript:void(0)" class="dropdown-notifications-archive">
                                                    <span class="icon-base fa fa-xmark"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar">
                                                    <span class="avatar-initial rounded-circle bg-label-danger">CF</span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 small">Charles Franklin</h6>
                                                <small class="mb-1 d-block text-body">Accepted your connection</small>
                                                <small class="text-body-secondary">12hr ago</small>
                                            </div>
                                            <div class="flex-shrink-0 dropdown-notifications-actions">
                                                <a href="javascript:void(0)" class="dropdown-notifications-read"
                                                ><span class="badge badge-dot"></span
                                                    ></a>
                                                <a href="javascript:void(0)" class="dropdown-notifications-archive"
                                                ><span class="icon-base fa fa-xmark"></span
                                                    ></a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li class="border-top">
                                <div class="d-grid p-4">
                                    <a class="btn btn-primary btn-sm d-flex" href="javascript:void(0);">
                                        <small class="align-middle">View all notifications</small>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!--/ Notification -->
                    @include('admin._partials.user')
                </ul>
            </div>
            @if ($cmsSettings->get('horizontal_menu'))
        </div>
    @endif
</nav>
