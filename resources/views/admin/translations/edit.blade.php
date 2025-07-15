@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Translations</li>
        </ol>
    </nav>
    @include('admin.-partials.lang.tabs')
    <div class="card">
        <div class="card-header header-elements">
            <div class="fs-5">Translations</div>
            <div class="card-header-elements ms-auto">
                <a href="{{ cms_route('translations.create') }}">
                    <i class="icon-base fa fa-plus icon-xs"></i>
                    <span>Add New Record</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content p-0">
                @php
                    $activeLang = language()->queryStringOrActive();
                    $hasManyLanguage = language()->count() > 1;
                @endphp
                @foreach($items as $current)
                    <div id="item-{{ $current->language }}" class="tab-pane{{ $current->language == $activeLang || ! $activeLang ? ' show active' : '' }}">
                        {{ html()->modelForm($current, 'put', cms_route('translations.update', [$current->id], $hasManyLanguage ? $current->language : null))
                        ->data('ajax-form', $preferences->get('ajax_form'))->data('lang', $current->language)->attribute('novalidate')->open() }}
                        @include('admin.translations.form')
                        {{ html()->form()->close() }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
