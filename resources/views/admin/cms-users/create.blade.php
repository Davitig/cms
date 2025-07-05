@extends('admin.app')
@section('content')
    <div class="card">
        <!-- Account -->
        {{ html()->modelForm($current, 'post', cms_route('cmsUsers.store'))->acceptsFiles()->open() }}
        @include('admin.cms-users.photo')
        <div class="card-body pt-4">
            @include('admin.cms-users.form')
        </div>
        {{ html()->form()->close() }}
        <!-- /Account -->
    </div>
@endsection
@include('admin.cms-users.scripts')
