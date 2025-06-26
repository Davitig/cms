@extends('admin.app')
@section('content')
    <div class="card">
        <!-- Account -->
        {{ html()->modelForm($current, 'post', cms_route('cmsUsers.store'))->acceptsFiles()->open() }}
        @include('admin.cms_users.photo')
        <div class="card-body pt-4">
            @include('admin.cms_users.form')
        </div>
        {{ html()->form()->close() }}
        <!-- /Account -->
    </div>
@endsection
@include('admin.cms_users.scripts')
