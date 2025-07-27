@extends('admin.app')
@section('content')
    <div class="card">
        <!-- Account -->
        {{ html()->modelForm($current, 'post', cms_route('cms_users.store'))->acceptsFiles()->open() }}
        <div class="card-body pt-4">
            @include('admin.cms-users.form')
        </div>
        {{ html()->form()->close() }}
        <!-- /Account -->
    </div>
@endsection
