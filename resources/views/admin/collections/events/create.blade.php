@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('events')}}"></i>
                Events
            </h1>
            <p class="description">Management of the events</p>
        </div>
        <div class="breadcrumb-env">
            <ol class="breadcrumb bc-1">
                <li>
                    <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li>
                    <a href="{{ cms_route('collections.index') }}"><i class="{{icon_type('collections')}}"></i>Collections</a>
                </li>
                <li class="active">
                    <i class="{{$icon}}"></i>
                    Events
                </li>
            </ol>
        </div>
    </div>
    <ul class="nav nav-tabs nav-tabs">
        @include('admin._partials.lang.active_tab')
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Create a new event</h2>
        </div>
        <div class="panel-body">
            {{ html()->modelForm($current,
                'post', cms_route('events.store', [$current->collection_id])
           )->class('form-horizontal')->open() }}
            @include('admin.collections.events.form', [
                'submit' => trans('general.create'),
                'submitAndBack' => trans('general.create_n_close'),
                'icon' => 'save'
            ])
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection
