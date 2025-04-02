@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('faq')}}"></i>
                FAQ
            </h1>
            <p class="description">Management of the FAQ</p>
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
                    <strong>FAQ</strong>
                </li>
            </ol>
        </div>
    </div>
    <ul class="nav nav-tabs nav-tabs-justified">
        @include('admin._partials.forms.lang_tabs')
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Edit FAQ</h2>
            <div class="panel-options">
                <a href="#" data-toggle="panel">
                    <span class="collapse-icon">&ndash;</span>
                    <span class="expand-icon">+</span>
                </a>
            </div>
            <a href="{{cms_route('faq.create', [$current->collection_id])}}" class="pull-right padr">Add more</a>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                @php($activeLang = request('lang', language()->active()))
                @foreach ($items as $current)
                    <div class="tab-pane{{$activeLang == $current->language ? ' active' : ''}}" id="item-{{$current->language}}">
                        {{ html()->modelForm($current, 'put', cms_route('faq.update', [
                            $current->collection_id, $current->id
                        ], language()->containsMany() ? ($current->language ?: $activeLang) : null))
                        ->class('form-horizontal ' . $cmsSettings->get('ajax_form'))
                        ->data('lang', $current->language)->open() }}
                        @include('admin.collections.faq.form', [
                            'submit' => trans('general.update'),
                            'icon' => 'save'
                        ])
                        {{ html()->form()->close() }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
