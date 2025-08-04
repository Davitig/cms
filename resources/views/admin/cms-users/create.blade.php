@extends('admin.app')
@section('content')
    <div class="card">
        <div class="card-header fs-5">CMS Users</div>
        <div class="card-body">
            {{ html()->modelForm($current, 'post', cms_route('cms_users.store'))->acceptsFiles()->open() }}
            @include('admin.cms-users.form')
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection
