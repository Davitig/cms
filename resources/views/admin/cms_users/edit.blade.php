@extends('admin.app')
@section('content')
    @include('admin.cms_users.navbar')
    <div class="card">
        <!-- Account -->
        {{ html()->modelForm($current, 'put', cms_route('cmsUsers.update', [$current->id]))->acceptsFiles()->open() }}
        @include('admin.cms_users.photo')
        <div class="card-body pt-4">
            @include('admin.cms_users.form')
        </div>
        {{ html()->form()->close() }}
        <!-- /Account -->
    </div>
@endsection
@include('admin.cms_users.scripts')
