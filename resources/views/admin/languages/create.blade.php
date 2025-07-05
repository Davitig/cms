@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Languages</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header fs-5">Languages</div>
        <div class="card-body">
            {{ html()->modelForm($current, 'post', cms_route('languages.store'))->attribute('novalidate')->open() }}
            @include('admin.languages.form')
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection
@include('admin.languages.scripts')
