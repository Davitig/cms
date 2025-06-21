@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">CMS Settings</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header fs-5">CMS Settings</div>
        <div class="card-body">
            {{ html()->form('put', cms_route('cmsSettings.update'))->open() }}
            <div class="row g-6 mb-6">
                <div>
                    <label class="switch switch-primary">
                        {{ html()->checkbox('horizontal_menu', $cmsSettings->get('horizontal_menu'))
                        ->id('horizontal_menu_inp')->class('switch-input') }}
                        <span class="switch-toggle-slider">
                        <span class="switch-on"></span>
                        <span class="switch-off"></span>
                    </span>
                        <span class="switch-label">Horizontal Menu</span>
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary me-4">Submit</button>
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection
