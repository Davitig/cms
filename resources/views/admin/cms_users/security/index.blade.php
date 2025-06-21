@extends('admin.app')
@section('content')
    <!-- Navbar pills -->
    <div class="row">
        <div class="col-md-12">
            @include('admin.cms_users.navbar')
        </div>
    </div>
    @include('admin.cms_users.security.password_form')
@endsection
