@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('galleries')}}"></i>
                Galleries
            </h1>
            <p class="description">Management of the galleries</p>
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
                    <strong>Gallery</strong>
                </li>
            </ol>
        </div>
    </div>
    <ul class="nav nav-tabs nav-tabs-justified">
        @include('admin._partials.items.lang')
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Edit gallery</h2>
            <div class="panel-options">
                <a href="#" data-toggle="panel">
                    <span class="collapse-icon">&ndash;</span>
                    <span class="expand-icon">+</span>
                </a>
            </div>
            <a href="{{cms_route('galleries.create', [$current->collection_id])}}" class="pull-right padr">Add more</a>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                @php
                    $activeLang = request('lang', language());
                    $languages = languages();
                    $firstLang = key($languages);
                @endphp
                @foreach ($items as $current)
                    <div class="tab-pane{{$activeLang == $current->language || ! $current->language ? ' active' : ''}}" id="item-{{$current->language ?: $firstLang}}">
                        {{ html()->modelForm($current, 'put', cms_route('galleries.update', [
                            $current->collection_id, $current->id
                        ], is_multilanguage() ? ($current->language ?: $activeLang) : null))
                        ->class('form-horizontal ' . $cmsSettings->get('ajax_form'))
                        ->data('lang', $current->language)->open() }}
                        {{ html()->hidden('type') }}
                        @include('admin.galleries.form', [
                            'submit'        => trans('general.update'),
                            'submitAndBack' => trans('general.update_n_back'),
                            'icon'          => 'save'
                        ])
                        {{ html()->form()->close() }}
                    </div>
                    @unset($languages[$current->language ?: $firstLang])
                @endforeach
                @foreach ($languages as $value)
                    <div class="tab-pane{{$activeLang == $value['language'] ? ' active' : ''}}" id="item-{{$value['language']}}">
                        {{ html()->form('post', cms_route('galleries.cloneLanguage', [$current->id], $value['language']))
                            ->class('form-horizontal')->data('lang', $value['language'])->open() }}
                        <button class="btn btn-info btn-icon btn-icon-standalone btn-lg">
                            <i class="{{$icon}}"></i>
                            <span>Create {{$value['full_name']}}</span>
                        </button>
                        {{ html()->form()->close() }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @include('admin.galleries.scripts')
@endsection
