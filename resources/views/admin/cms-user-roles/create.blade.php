@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">CMS User Roles</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header fs-5">CMS User Roles</div>
        <div class="card-body">
            {{ html()->modelForm($current, 'post', cms_route('cms_user_roles.store'))->attribute('novalidate')->open() }}
            @include('admin.cms-user-roles.form')
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection
