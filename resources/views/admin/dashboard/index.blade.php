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
                        <h4 class="mb-0">{{ $cmsUsersTotal }}</h4>
                    </div>
                    <p class="mb-0">
                        <span class="fw-medium fs-5">Total cms users</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-info h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <a href="{{ $userRouteAccess('menus.index') ? cms_route('menus.index') : '#' }}" class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="icon-base fa fa-list icon-28px"></i>
                            </span>
                        </a>
                        <h4 class="mb-0">{{ $menusTotal }}</h4>
                    </div>
                    <p class="mb-0">
                        <span class="fw-medium fs-5">Total menus</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-success h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <a href="{{ $userRouteAccess('pages.index') ? cms_route('pages.index', [$mainMenuId]) : '#' }}" class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="icon-base fa fa-indent icon-28px"></i>
                            </span>
                        </a>
                        <h4 class="mb-0">{{ $pagesTotal }}</h4>
                    </div>
                    <p class="mb-0">
                        <span class="fw-medium fs-5">Total pages</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-danger h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <a href="{{ $userRouteAccess('products.index') ? cms_route('products.index') : '#' }}" class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="icon-base fa fa-list-alt icon-28px"></i>
                            </span>
                        </a>
                        <h4 class="mb-0">{{ $productsTotal }}</h4>
                    </div>
                    <p class="mb-0">
                        <span class="fw-medium fs-5">Total products</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-warning h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <a href="{{ $userRouteAccess('collections.index') ? cms_route('collections.index') : '#' }}" class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="icon-base fa fa-list-alt icon-28px"></i>
                            </span>
                        </a>
                        <h4 class="mb-0">{{ $collectionsTotal }}</h4>
                    </div>
                    <p class="mb-0">
                        <span class="fw-medium fs-5">Total collections</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-dark h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-dark">
                                <i class="icon-base fa fa-newspaper icon-28px"></i>
                            </span>
                        </div>
                        <h4 class="mb-0">{{ $articlesTotal }}</h4>
                    </div>
                    <p class="mb-0">
                        <span class="fw-medium fs-5">Total articles</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-dark h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-dark">
                                <i class="icon-base fa fa-book-atlas icon-28px"></i>
                            </span>
                        </div>
                        <h4 class="mb-0">{{ $eventsTotal }}</h4>
                    </div>
                    <p class="mb-0">
                        <span class="fw-medium fs-5">Total events</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-dark h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-dark">
                                <i class="icon-base fa fa-question-circle icon-28px"></i>
                            </span>
                        </div>
                        <h4 class="mb-0">{{ $faqTotal }}</h4>
                    </div>
                    <p class="mb-0">
                        <span class="fw-medium fs-5">Total FAQ</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
