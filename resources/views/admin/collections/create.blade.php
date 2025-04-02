@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('collections')}}"></i>
                Collections
            </h1>
            <p class="description">Management of the collections</p>
        </div>
        <div class="breadcrumb-env">
            <ol class="breadcrumb bc-1">
                <li>
                    <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li class="active">
                    <i class="{{$icon}}"></i>
                    <strong>Collections</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Create a new collection</h2>
        </div>
        <div class="panel-body">
            {{ html()->modelForm($current,
                'post', cms_route('collections.store')
            )->class('form-horizontal')->open() }}
            @include('admin.collections.form', [
                'submit' => trans('general.create'),
                'submitAndBack' => trans('general.create_n_close'),
                'icon' => 'save'
            ])
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection
