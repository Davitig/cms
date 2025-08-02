@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ cms_route('menus.index') }}">Menus</a>
            </li>
            <li class="breadcrumb-item active">Pages</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header fs-5">Pages</div>
        <div class="card-body">
            @includeWhen(! language()->queryStringOrActive(), 'admin.-alerts.resource-requires-lang')
            {{ html()->modelForm($current, 'post', cms_route('pages.store', [$current->menu_id]))->id('pages-form')->open() }}
            {{ html()->hidden('parent_id') }}
            @include('admin.pages.form')
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection
@include('admin.pages.scripts')
