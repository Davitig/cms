@extends('admin.app')
@section('content')
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-6">
                <div class="user-profile-header-banner">
                    <img src="{{ asset('assets/img/pages/profile-banner.png') }}" alt="Banner image" class="rounded-top" />
                </div>
                <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-5">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        <img src="{{ cms_route('cms_users.photo', [$current->id]) }}" alt="user image"
                             class="d-block h-auto ms-0 ms-sm-6 rounded user-profile-img bg-white" />
                    </div>
                    <div class="flex-grow-1 mt-3 mt-lg-5">
                        <div
                            class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4 class="mb-2 mt-lg-6">{{$current->first_name}} {{$current->last_name}}</h4>
                                <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 my-2">
                                    @if ($current->created_at)
                                        <li class="list-inline-item d-flex gap-2 align-items-center">
                                            <i class="icon-base fa-regular fa-calendar-check icon-md"></i>
                                            <span class="fw-medium">Joined {{ $current->created_at->format('F Y') }}</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Header -->
    <!-- Navbar pills -->
    <div class="row">
        <div class="col-md-12">
            @include('admin.cms-users.navbar')
        </div>
    </div>
    <!--/ Navbar pills -->
    <!-- User Profile Content -->
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-5">
            <!-- About User -->
            <div class="card mb-6">
                <div class="card-body">
                    <p class="card-text text-uppercase text-body-secondary small mb-0">About</p>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4">
                            <i class="icon-base fa-regular fa-user icon-sm"></i>
                            <span class="fw-medium mx-2">Full Name:</span>
                            <span>{{ $current->first_name }} {{ $current->last_name }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="icon-base fa fa-{{ $current->suspended ? 'xmark' : 'check' }} icon-sm"></i>
                            <span class="fw-medium mx-2">Status:</span>
                            <span>{{ $current->suspended ? 'suspended' : 'active' }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="icon-base fa fa-user-pen icon-sm"></i>
                            <span class="fw-medium mx-2">Role:</span>
                            <span>{{ $current->role }}</span>
                        </li>
                    </ul>
                    <p class="card-text text-uppercase text-body-secondary small mb-0">Contacts</p>
                    <ul class="list-unstyled my-3 py-1">
                        <li class="d-flex align-items-center mb-4">
                            <i class="icon-base fa-regular fa-envelope icon-sm"></i>
                            <span class="fw-medium mx-2">Email:</span>
                            <span>{{ $current->email }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-4">
                            <i class="icon-base fa fa-phone icon-sm"></i>
                            <span class="fw-medium mx-2">Phone:</span>
                            <span>{{ $current->phone }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <!--/ About User -->
        </div>
        <div class="col-xl-8 col-lg-7 col-md-7">
            <!-- Activity Timeline -->
            <div class="card card-action mb-6">
                <div class="card-header align-items-center">
                    <h5 class="card-action-title mb-0">
                        <i class="icon-base fa fa-chart-line icon-lg me-4"></i>Activity Timeline
                    </h5>
                </div>
                <div class="card-body pt-3">
                </div>
            </div>
            <!--/ Activity Timeline -->
        </div>
    </div>
    <!--/ User Profile Content -->
@endsection
@push('head')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}">
@endpush
