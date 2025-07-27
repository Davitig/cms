@extends('admin.app')
@section('content')
    @include('admin.cms-users.-partials.header')
    @include('admin.cms-users.-partials.navbar')
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
                        @if ($current->phone)
                        <li class="d-flex align-items-center mb-4">
                            <i class="icon-base fa fa-mobile-screen icon-sm"></i>
                            <span class="fw-medium mx-2">Phone:</span>
                            <span>{{ $current->phone }}</span>
                        </li>
                        @endif
                        @if ($current->address)
                        <li class="d-flex align-items-center mb-4">
                            <i class="icon-base fa-regular fa-address-book icon-sm"></i>
                            <span class="fw-medium mx-2">Address:</span>
                            <span>{{ $current->address }}</span>
                        </li>
                        @endif
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
