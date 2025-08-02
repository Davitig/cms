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
                @php($activeLang = language()->queryStringOrActive())
                @includeWhen(! $activeLang, 'admin.-alerts.resource-requires-lang')
                @foreach($items as $current)
                    <div id="item-{{ $current->language }}" @class(['tab-pane', 'show active' => $current->language == $activeLang || ! $activeLang])>
                        @includeWhen(! $current->language_id, 'admin.-alerts.resource-without-lang')
                        {{ html()->modelForm($current, 'put', cms_route('translations.update', [$current->id], $current->language))
                        ->data('ajax-form', $preferences->get('ajax_form'))->data('lang', $current->language)->attribute('novalidate')->open() }}
                        @include('admin.translations.form')
                        {{ html()->form()->close() }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
