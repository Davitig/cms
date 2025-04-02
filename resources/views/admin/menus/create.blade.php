@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('menus')}}"></i>
                Menus
            </h1>
            <p class="description">Management of the menus</p>
        </div>
        <div class="breadcrumb-env">
            <ol class="breadcrumb bc-1">
                <li>
                    <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li class="active">
                    <i class="{{$icon}}"></i>
                    <strong>Menus</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Create a new menu</h2>
            <div class="panel-options">
                <a href="#" data-toggle="panel">
                    <span class="collapse-icon">&ndash;</span>
                    <span class="expand-icon">+</span>
                </a>
                <a href="#" data-toggle="remove">
                    &times;
                </a>
            </div>
        </div>
        <div class="panel-body">
            {{ html()->modelForm($current, 'post', cms_route('menus.store'))->class('form-horizontal')->open() }}
            @include('admin.menus.form', [
                'submit' => trans('general.create'),
                'submitAndBack' => trans('general.create_n_close'),
                'icon' => 'save'
            ])
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection
