@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Menus</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header fs-5">Menus</div>
        <div class="card-body">
            {{ html()->modelForm($current, 'post', cms_route('menus.store'))->attribute('novalidate')->open() }}
            @include('admin.menus.form')
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection
