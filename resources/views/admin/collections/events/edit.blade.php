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
                <strong>Events</strong>
            </li>
        </ol>
    </div>
</div>
<div class="clearfix">
    @include('admin._partials.items.lang')
    <ul class="nav nav-tabs col-xs-6 right-aligned">
        <li>
            <a href="{{cms_route('files.index', ['events', $current->id])}}">
                <span class="visible-xs"><i class="{{$iconFiles = icon_type('files')}}"></i></span>
                <div class="hidden-xs">
                    <i class="{{$iconFiles}}"></i> {{trans('general.files')}}
                </div>
            </a>
        </li>
    </ul>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="panel-title">Edit event</h2>
        <div class="panel-options">
            <a href="#" data-toggle="panel">
                <span class="collapse-icon">&ndash;</span>
                <span class="expand-icon">+</span>
            </a>
        </div>
        <a href="{{cms_route('events.create', [$current->collection_id])}}" class="pull-right padr">Add more</a>
    </div>
    <div class="panel-body">
        <div class="tab-content">
        @foreach ($items as $current)
            <div class="tab-pane{{language() != $current->language ? '' : ' active'}}" id="item-{{$current->language}}">
                {!! Form::model($current, [
                    'method'    => 'put',
                    'url'       => cms_route('events.update', [$current->collection_id, $current->id], is_multilanguage() ? $current->language : null),
                    'class'     => 'form-horizontal '.$cmsSettings->get('ajax_form'),
                    'data-lang' => $current->language
                ]) !!}
                    @include('admin.collections.events.form', [
                        'submit'        => trans('general.update'),
                        'submitAndBack' => trans('general.update_n_back'),
                        'icon'          => 'save'
                    ])
                {!! Form::close() !!}
            </div>
        @endforeach
        </div>
    </div>
</div>
@endsection
