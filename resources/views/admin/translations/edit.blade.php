@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Translations</li>
        </ol>
    </nav>
    <div class="nav-align-top">
        @include('admin._partials.lang.tabs_linked')
        <div class="card">
            <div class="card-header header-elements">
                <div class="fs-5">Translations</div>
                <div class="card-header-elements ms-auto">
                    <a href="{{ cms_route('translations.create') }}">
                        <i class="icon-base fa fa-plus icon-xs me-sm-1"></i>
                        <span>Add New Record</span>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="panel-body">
                    {{ html()->modelForm($current, 'put', cms_route('translations.update', [$current->id]))->open() }}
                    @include('admin.translations.form')
                    {{ html()->form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
