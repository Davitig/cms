@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ cms_route('settings.index') }}">Settings</a>
            </li>
            <li class="breadcrumb-item active">Meta</li>
        </ol>
    </nav>
    @include('admin.-partials.lang.tabs')
    <div class="card">
        <div class="card-header fs-5">Meta Defaults</div>
        <div class="card-body">
            <div class="tab-content p-0">
                @php($activeLang = language()->queryStringOrActive())
                @foreach ($items as $langId => $current)
                    @php($currentLang = language()->getByKey($langId, 'language'))
                    <div id="item-{{ $currentLang }}" @class(['tab-pane', 'show active' => $currentLang == $activeLang || ! $activeLang])>
                        {{ html()->modelForm($current, 'post', cms_route('settings.meta.save', [], $currentLang))->data('lang', $currentLang)
                        ->data('ajax-form', 1)->attribute('novalidate')->open() }}
                        <div class="row g-6 mb-6">
                            <div>
                                <label for="site_name_inp" class="form-label">Site Name</label>
                                {{ html()->text('site_name')->id('site_name_inp' . $currentLang)->class('form-control') }}
                                @error('site_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label for="title_inp" class="form-label">Title</label>
                                {{ html()->text('title')->id('title_inp' . $currentLang)->class('form-control') }}
                                @error('title')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label for="description_inp" class="form-label">Description</label>
                                {{ html()->text('description')->id('description_inp' . $currentLang)->class('form-control') }}
                                @error('description')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <label for="description_inp" class="form-label">Image</label>
                                <div class="input-group">
                                    {{ html()->text('image')->id('image_inp' . $currentLang)->class('form-control') }}
                                    <button type="button" class="file-manager-popup btn btn-outline-primary" data-browse="image_inp{{ $currentLang }}">Browse</button>
                                </div>
                                @error('image')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        {{ html()->form()->close() }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
