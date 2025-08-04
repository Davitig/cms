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
            <li class="breadcrumb-item active">Language</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header fs-5">Language Settings</div>
        <div class="card-body">
            {{ html()->modelForm($current, 'post', cms_route('settings.language.save'))->id('lang-setting-form')
            ->data('ajax-form', $preferences->get('ajax_form'))->attribute('novalidate')->open() }}
            <div class="row g-6 mb-6">
                <div class="col-xs">
                    <div @class(['form-check custom-option custom-option-basic',
                        'checked' => $current->get('down_without_language')
                    ])>
                        <label class="form-check-label custom-option-content" for="down_without_language_inp">
                            {{ html()->checkbox('down_without_language')->id('down_without_language_inp')
                            ->class('form-check-input') }}
                            <span class="custom-option-header">
                                <span class="h6 mb-0">Maintenance Mode Without Language</span>
                            </span>
                            <span class="custom-option-body">
                                <small class="option-text">
                                    Go to maintenance mode when there is no visible language available.
                                </small>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="col-xs">
                    <div @class(['form-check custom-option custom-option-basic',
                        'checked' => $current->get('disable_main_language_from_url')
                    ])>
                        <label class="form-check-label custom-option-content" for="disable_main_language_from_url_inp">
                            {{ html()->checkbox('disable_main_language_from_url')->id('disable_main_language_from_url_inp')
                            ->class('form-check-input') }}
                            <span class="custom-option-header">
                                <span class="h6 mb-0">Disable Main Language From URL</span>
                            </span>
                            <span class="custom-option-body">
                                <small class="option-text">
                                    Do not allow main language to appear in URL.
                                </small>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="col-xs">
                    <div @class(['form-check custom-option custom-option-basic',
                        'checked' => $current->get('allow_single_language_in_url')
                    ])>
                        <label class="form-check-label custom-option-content" for="allow_single_language_in_url_inp">
                            {{ html()->checkbox('allow_single_language_in_url')
                            ->id('allow_single_language_in_url_inp')->class('form-check-input') }}
                            <span class="custom-option-header">
                                <span class="h6 mb-0">Allow Single Language in URL</span>
                            </span>
                            <span class="custom-option-body">
                                <small class="option-text">
                                    Allow single available language to appear in URL.
                                    @if ($routesAreCached && language()->countVisible() === 1)
                                        <span class="text-decoration-underline link-underline-info">
                                            After updating this option expects route <a href="{{ cms_route('settings.cache.index') }}#cache-routes" class="text-info" target="_blank">cache</a> to be cleared or refreshed
                                        </span>
                                    @endif
                                </small>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="col-lg">
                    <div @class(['form-check custom-option custom-option-basic',
                        'checked' => $current->get('redirect_from_main')
                    ])>
                        <label class="form-check-label custom-option-content" for="redirect_from_main_inp">
                            {{ html()->checkbox('redirect_from_main')
                            ->id('redirect_from_main_inp')->class('form-check-input') }}
                            <span class="custom-option-header">
                                <span class="h6 mb-0">Redirect From Main Language URL</span>
                            </span>
                            <span class="custom-option-body">
                                <small class="option-text">
                                    Redirect main language requests on non language URL (which is also main by default).
                                </small>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="col-lg">
                    <div @class(['form-check custom-option custom-option-basic',
                        'checked' => $current->get('redirect_to_main')
                    ])>
                        <label class="form-check-label custom-option-content" for="redirect_to_main_inp">
                            {{ html()->checkbox('redirect_to_main')
                            ->id('redirect_to_main_inp')->class('form-check-input') }}
                            <span class="custom-option-header">
                                <span class="h6 mb-0">Redirect To Main Language</span>
                            </span>
                            <span class="custom-option-body">
                                <small class="option-text">
                                    Redirect non language requests on main language URL.
                                    This option will take action only if
                                    <span class="text-decoration-underline">main or single language is not disabled from URL or redirected.</span>
                                </small>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection
@push('body.bottom')
    <script type="text/javascript">
        $(function () {
            @if (! language()->countVisible())
            let langVisibleSelector = $('.lang-visibility-alert');
            if (langVisibleSelector.data('count') <= 1) {
                $('form#lang-setting-form[data-ajax-form="1"]').on('ajaxFormDone', function (e, res) {
                    if (! res?.data?.down_without_language) {
                        langVisibleSelector.addClass('d-none');
                    } else {
                        langVisibleSelector.removeClass('d-none');
                    }
                });
            }
            @endif
        });
    </script>
@endpush
