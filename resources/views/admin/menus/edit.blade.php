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
        <div class="card-header header-elements">
            <div class="fs-5">Menus</div>
            <div class="card-header-elements ms-auto">
                <a href="{{ cms_route('menus.create') }}">
                    <i class="icon-base fa fa-plus icon-xs"></i>
                    <span>Add New Record</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="panel-body">
                {{ html()->modelForm($current, 'put', cms_route('menus.update', [$current->id]))
                ->data('ajax-form', $preferences->get('ajax_form'))->open() }}
                @include('admin.menus.form')
                {{ html()->form()->close() }}
            </div>
        </div>
    </div>
@endsection
