@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Settings</li>
        </ol>
    </nav>
    <div class="d-flex flex-column gap-6">
        @if (auth('cms')->user()->hasFullAccess())
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="fs-5">Common</div>
                </div>
                <div class="card-body">
                    <div class="row gy-6">
                        <div class="col-12 col-md-6 col-lg-auto">
                            <a href="{{ cms_route('settings.meta.index') }}" class="d-inline-flex align-items-center">
                            <span class="badge bg-label-primary text-body p-2 me-4 rounded">
                                <i class="icon-base fa fa-magnifying-glass-plus icon-md"></i>
                            </span>
                                <span>
                                <span class="fs-6 d-block text-primary">Meta Defaults</span>
                                <small class="text-body">Set default meta data</small>
                            </span>
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-auto">
                            <a href="{{ cms_route('settings.contact.index') }}" class="d-inline-flex align-items-center">
                            <span class="badge bg-label-primary text-body p-2 me-4 rounded">
                                <i class="icon-base fa fa-bars-staggered icon-md"></i>
                            </span>
                                <span>
                                <span class="fs-6 d-block text-primary">Contact</span>
                                <small class="text-body">Contact information</small>
                            </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="fs-5">Utilities</div>
            </div>
            <div class="card-body">
                @if ($userRouteAccess('settings.cache.index', 'settings.system.index'))
                    <div class="row gy-6">
                        @if ($userRouteAccess('settings.cache.index'))
                            <div class="col-12 col-md-6 col-lg-auto">
                                <a href="{{ cms_route('settings.cache.index') }}" class="d-inline-flex align-items-center">
                            <span class="badge bg-label-primary text-body p-2 me-4 rounded">
                                <i class="icon-base fa fa-layer-group icon-md"></i>
                            </span>
                                    <span>
                                <span class="fs-6 d-block text-primary">Cache</span>
                                <small class="text-body">Cache Management</small>
                            </span>
                                </a>
                            </div>
                        @endif
                        @if ($userRouteAccess('settings.system.index'))
                            <div class="col-12 col-md-6 col-lg-auto">
                                <a href="{{ cms_route('settings.system.index') }}" class="d-inline-flex align-items-center">
                            <span class="badge bg-label-primary text-body p-2 me-4 rounded">
                                <i class="icon-base fa fa-server icon-md"></i>
                            </span>
                                    <span>
                                <span class="fs-6 d-block text-primary">System Information</span>
                                <small class="text-body">Information of active system configuration</small>
                            </span>
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="alert alert-outline-info mb-0" role="alert">
                        Utility settings are not available at this moment.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
