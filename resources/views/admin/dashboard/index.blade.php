@extends('admin.app')
@section('content')
    <div class="row g-6">
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-primary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <a href="{{ cms_route('cmsUsers.index') }}" class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="icon-base fa fa-user-tie icon-28px"></i>
                            </span>
                        </a>
                        <h4 class="mb-0">{{ $cmsUserCount }}</h4>
                    </div>
                    <p class="mb-0">
                        <span class="fw-medium fs-5">Total CMS Users</span>
                    </p>
                </div>
            </div>
        </div>
        @if ($hasAccess = $userRouteAccess('menus.index'))
            <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-info h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <a href="{{ $hasAccess ? cms_route('menus.index') : '#' }}" class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="icon-base fa fa-list icon-28px"></i>
                            </span>
                            </a>
                            <h4 class="mb-0">{{ number_format($menuCount) }}</h4>
                        </div>
                        <p class="mb-0">
                            <span class="fw-medium fs-5">Total Menus</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if ($menuId && $hasAccess = $userRouteAccess('pages.index'))
            <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <a href="{{ $userRouteAccess('pages.index') ? cms_route('pages.index', [$menuId]) : '#' }}" class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="icon-base fa fa-indent icon-28px"></i>
                            </span>
                            </a>
                            <h4 class="mb-0">{{ number_format($pageCount) }}</h4>
                        </div>
                        <p class="mb-0">
                            <span class="fw-medium fs-5">Total Pages</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if ($hasAccess = $userRouteAccess('products.index'))
            <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-danger h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <a href="{{ $userRouteAccess('products.index') ? cms_route('products.index') : '#' }}" class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="icon-base fa fa-list-alt icon-28px"></i>
                            </span>
                            </a>
                            <h4 class="mb-0">{{ number_format($productCount) }}</h4>
                        </div>
                        <p class="mb-0">
                            <span class="fw-medium fs-5">Total Products</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if ($hasAccess = $userRouteAccess('collections.index'))
            <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-warning h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <a href="{{ $userRouteAccess('collections.index') ? cms_route('collections.index') : '#' }}" class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="icon-base fa fa-list-alt icon-28px"></i>
                            </span>
                            </a>
                            <h4 class="mb-0">{{ number_format($collectionCount) }}</h4>
                        </div>
                        <p class="mb-0">
                            <span class="fw-medium fs-5">Total Collections</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if ($hasAccess = $userRouteAccess('articles.index'))
            <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-dark h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar me-4">
                                <span class="avatar-initial rounded bg-label-dark">
                                    <i class="icon-base fa fa-newspaper icon-28px"></i>
                                </span>
                            </div>
                            <h4 class="mb-0">{{ number_format($articleCount) }}</h4>
                        </div>
                        <p class="mb-0">
                            <span class="fw-medium fs-5">Total Articles</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if ($hasAccess = $userRouteAccess('events.index'))
            <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-dark h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar me-4">
                                <span class="avatar-initial rounded bg-label-dark">
                                    <i class="icon-base fa fa-book-atlas icon-28px"></i>
                                </span>
                            </div>
                            <h4 class="mb-0">{{ number_format($eventCount) }}</h4>
                        </div>
                        <p class="mb-0">
                            <span class="fw-medium fs-5">Total Events</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if ($hasAccess = $userRouteAccess('translations.index'))
            <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-dark h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar me-4">
                                <span class="avatar-initial rounded bg-label-dark">
                                    <i class="icon-base fa fa-sort-alpha-asc icon-28px"></i>
                                </span>
                            </div>
                            <h4 class="mb-0">{{ number_format($translationCount) }}</h4>
                        </div>
                        <p class="mb-0">
                            <span class="fw-medium fs-5">Total Translations</span>
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
