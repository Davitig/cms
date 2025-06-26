@extends('admin.app')
@section('content')
    <div class="d-md-flex justify-content-md-between align-items-center">
        @include('admin.cms_users.navbar')
        <div class="text-md-end text-center mb-6">
            <a href="{{ cms_route('cmsUsers.create') }}">
                <i class="icon-base fa fa-plus icon-xs me-sm-1"></i>
                <span>Add New Record</span>
            </a>
        </div>
    </div>
    <div class="card">
        <!-- Account -->
        {{ html()->modelForm($current, 'put', cms_route('cmsUsers.update', [$current->id]))->acceptsFiles()->open() }}
        @include('admin.cms_users.photo')
        <div class="card-body pt-4">
            @include('admin.cms_users.form')
        </div>
        {{ html()->form()->close() }}
        <!--/ Account -->
    </div>
@endsection
@include('admin.cms_users.scripts')
