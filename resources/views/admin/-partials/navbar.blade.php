<!-- Navbar -->
<nav class="layout-navbar navbar navbar-expand-xl align-items-center{{ $preferences->get('horizontal_menu') ? '' : ' container-xxl navbar-detached bg-navbar-theme' }}"
     id="layout-navbar">
    @if ($preferences->get('horizontal_menu'))
        <div class="container-xxl">
            @include('admin.-partials.brand')
            @endif
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                <a href="javascript:void(0);" class="nav-item nav-link px-0 me-xl-6">
                    <i class="icon-base fa fa-bars icon-md"></i>
                </a>
            </div>
            <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
                @if (! $preferences->get('horizontal_menu'))
                    <!-- Search -->
                    <div class="navbar-nav align-items-center">
                        <div class="nav-item navbar-search-wrapper px-md-0 px-2 mb-0">
                            <a href="javascript:void(0);" class="nav-item nav-link search-toggler d-flex align-items-center px-0">
                                <span class="d-inline-block text-body-secondary fw-normal" id="search"></span>
                            </a>
                        </div>
                    </div>
                    <!--/ Search -->
                    @include('admin.-scripts.search')
                @endif
                <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                    @if ($preferences->get('horizontal_menu'))
                        <!-- Search -->
                        <li class="nav-item navbar-search-wrapper btn btn-text-secondary btn-icon rounded-pill">
                            <a href="javascript:void(0);" class="nav-item nav-link search-toggler px-0">
                                <span class="d-inline-block text-body-secondary fw-normal" id="search"></span>
                            </a>
                        </li>
                        <!-- /Search -->
                        @include('admin.-scripts.search')
                    @endif
                    @include('admin.-partials.lang.dropdown')
                    <!-- Website -->
                    <li class="nav-item">
                        <a href="{{ web_url('/') }}" id="website-url" class="nav-link btn btn-icon rounded-pill" title="View Website" target="_blank">
                            <i class="icon-base fa fa-globe icon-22px"></i>
                        </a>
                    </li>
                    <!--/ Website -->
                    <!-- Style Switcher -->
                    <li class="nav-item dropdown">
                        <a
                            href="javascript:void(0);"
                            id="nav-theme"
                            class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
                            data-bs-toggle="dropdown"
                            title="Theme">
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
                                    <span>
                                        <i class="icon-base fa fa-moon icon-22px me-3" data-icon="moon-stars"></i>
                                        Dark
                                    </span>
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
                    <!--/ Style Switcher-->
                    <!-- Sitemap XML -->
                    <li class="nav-item navbar-dropdown dropdown me-3 me-xl-2">
                        <a
                            href="javascript:void(0);"
                            class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false"
                            title="Quick Links">
                            <i class="icon-base fa fa-sitemap icon-20px text-heading"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-0">
                            <li class="dropdown-menu-header border-bottom">
                                <div class="dropdown-header d-flex align-items-center py-5">
                                    <h6 class="mb-0 me-auto">Sitemap XML</h6>
                                    <i class="icon-base fa fa-sitemap icon-20px text-heading"></i>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex p-4">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="p-3 rounded-circle bg-label-success">
                                            <i class="icon-base fa fa-sitemap icon-lg"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong class="fs-6 mb-1 d-block">sitemap.xml</strong>
                                        <small class="d-block">
                                            Last Update: <span class="sitemap-xml-ctime">{{$sitemapXmlTime ? date('d F Y H:i', $sitemapXmlTime) : 'N/A'}}</span>
                                        </small>
                                    </div>
                                </div>
                            </li>
                            @if (isset($sitemapXmlTime))
                                <li class="border-top">
                                    <div class="d-flex flex-column p-4">
                                        <a href="{{ asset('sitemap.xml') }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                            <small class="align-middle">View sitemap.xml</small>
                                        </a>
                                    </div>
                                </li>
                            @endif
                            <li class="border-top">
                                <form action="{{cms_route('sitemap.xml.store')}}" method="POST" id="sitemap-xml-form" data-ajax-form="1">
                                    @csrf
                                    <div class="d-flex flex-column p-4">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <small class="align-middle">Generate sitemap.xml</small>
                                        </button>
                                    </div>
                                </form>
                            </li>
                        </ul>
                        @push('body.bottom')
                            <script type="text/javascript">
                                $(function () {
                                    $('#sitemap-xml-form').on('ajaxFormSuccess', function (e, res) {
                                        if (res.result) {
                                            $('.sitemap-xml-ctime').text(res.data);
                                        }
                                        notyf(res.message, res.result);
                                    });
                                });
                            </script>
                        @endpush
                    </li>
                    <!--/ Sitemap XML -->
                    @include('admin.-partials.user')
                </ul>
            </div>
            @if ($preferences->get('horizontal_menu'))
        </div>
    @endif
</nav>
