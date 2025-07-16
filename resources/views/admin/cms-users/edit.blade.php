@extends('admin.app')
@section('content')
    <div class="d-md-flex justify-content-md-between align-items-center">
        @include('admin.cms-users.navbar')
        <div class="text-md-end text-center mb-6">
            <a href="{{ cms_route('cms_users.create') }}">
                <i class="icon-base fa fa-plus icon-xs"></i>
                <span>Add New Record</span>
            </a>
        </div>
    </div>
    <div class="card">
        <!-- Account -->
        {{ html()->modelForm($current, 'put', cms_route('cms_users.update', [$current->id]))->acceptsFiles()
        ->data('ajax-form', $preferences->get('ajax_form'))->attribute('novalidate')->open() }}
        @include('admin.cms-users.photo')
        <div class="card-body pt-4">
            @include('admin.cms-users.form')
        </div>
        {{ html()->form()->close() }}
        <!--/ Account -->
    </div>
@endsection
@include('admin.cms-users.scripts')
