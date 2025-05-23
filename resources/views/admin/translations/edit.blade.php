@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('articles')}}"></i>
                Translations
            </h1>
            <p class="description">Management of the translations</p>
        </div>
        <div class="breadcrumb-env">
            <ol class="breadcrumb bc-1">
                <li>
                    <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li class="active">
                    <i class="{{$icon}}"></i>
                    <strong>Translations</strong>
                </li>
            </ol>
        </div>
    </div>
    <ul class="nav nav-tabs nav-tabs-justified">
        @include('admin._partials.forms.lang_tabs')
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <h2 class="panel-title">Edit translation</h2>
            <a href="{{cms_route('translations.create')}}" class="pull-right">Add more</a>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                @php($activeLang = request('lang', language()->active()))
                @foreach ($items as $current)
                    <div class="tab-pane{{$activeLang == $current->language ? ' active' : ''}}" id="item-{{$current->language}}">
                        {{ html()->modelForm($current, 'put', cms_route('translations.update', [
                            $current->id
                        ], language()->containsMany() ? ($current->language ?: $activeLang) : null))
                        ->class('form-horizontal ' . $cmsSettings->get('ajax_form'))
                        ->data('lang', $current->language)->open() }}
                        @include('admin.translations.form', [
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
