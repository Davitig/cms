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
            <li class="breadcrumb-item active">Cache</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-body d-flex flex-column gap-6">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="me-1">
                    <h5 class="mb-0">Cache Management</h5>
                </div>
            </div>
            @if ($userRouteAccess('settings.cache.view_clear', 'settings.cache.config', 'settings.cache.routes'))
                @if ($userRouteAccess('settings.cache.view_clear'))
                    <div class="card shadow-none border">
                        <div class="card-body">
                            <h5>Clear compiled views</h5>
                            <p>
                                By default, Blade template views are compiled on demand. When a request is executed that renders a view,
                                Laravel will determine if a compiled version of the view exists. If the file exists,
                                Laravel will then determine if the uncompiled view has been modified more recently than the compiled view.
                                If the compiled view either does not exist, or the uncompiled view has been modified, Laravel will recompile the view.
                            </p>
                            {{ html()->form('post', cms_route('settings.cache.view_clear'))->data('ajax-form', 1)->open() }}
                            <button type="submit" class="btn btn-outline-success">Clear</button>
                            {{ html()->form()->close() }}

                        </div>
                    </div>
                @endif
                @if ($userRouteAccess('settings.cache.config'))
                    <div class="card shadow-none border">
                        <div class="card-body">
                            <h5 class="title">
                                Cache config
                                <i @class(['icon-base fa fa-check icon-sm text-success ms-1', 'd-none' => ! $configCached])"></i>
                            </h5>
                            <p>
                                To give your application a speed boost, you should cache all of your configuration files using the cache command.
                                This will combine all the configuration options for your application into a single file which can be quickly loaded by the framework.
                            </p>
                            {{ html()->form('post', cms_route('settings.cache.config'))->class('cache-form')
                            ->data('cached', (int) $configCached)->data('ajax-form', 1)->open() }}
                            <button type="submit" class="btn btn-{{ $configCached ? 'warning' : 'success' }}">
                                {{ $configCached ? 'Clear' : 'Cache' }}
                            </button>
                            {{ html()->form()->close() }}
                        </div>
                    </div>
                @endif
                @if ($userRouteAccess('settings.cache.routes'))
                    <div class="card shadow-none border">
                        <div class="card-body">
                            <h5 class="title">
                                Cache routes
                                <i @class(['icon-base fa fa-check icon-sm text-success ms-1', 'd-none' => ! $routesCached])"></i>
                            </h5>
                            <p>
                                When deploying your application to production, you should take advantage of Laravel's route cache.
                                Using the route cache will drastically decrease the amount of time it takes to register all of your application's routes.
                                To generate a route cache, execute the cache command.
                                <span class="text-danger">Remember, if you add or change any <a href="{{ cms_route('languages.index') }}" class="text-danger text-decoration-underline" target="_blank"><strong>language</strong></a> data you will need to generate a fresh route cache.</span>
                            </p>
                            {{ html()->form('post', cms_route('settings.cache.routes'))->class('cache-form')
                            ->data('cached', (int) $routesCached)->data('ajax-form', 1)->open() }}
                            <button type="submit" class="btn btn-{{ $routesCached ? 'warning' : 'success' }}">
                                {{ $routesCached ? 'Clear' : 'Cache' }}
                            </button>
                            {{ html()->form()->close() }}
                        </div>
                    </div>
                @endif
                @push('body.bottom')
                    <script type="text/javascript">
                        $(function () {
                            $('form.cache-form[data-ajax-form="1"]').on('ajaxFormDone', function () {
                                let form = $(this);
                                let cached = form.data('cached');
                                form.data('cached', cached ? 0 : 1);
                                let btn = $('button', form);
                                btn.text(cached ? 'Cache' : 'Clear');
                                if (cached) {
                                    btn.removeClass('btn-warning').addClass('btn-success');
                                    btn.closest('.card-body').find('i').addClass('d-none');
                                } else {
                                    btn.removeClass('btn-success').addClass('btn-warning');
                                    btn.closest('.card-body').find('i').removeClass('d-none');
                                }
                            });
                        });
                    </script>
                @endpush
            @else
                <div class="alert alert-outline-info mb-0" role="alert">
                    Cache settings are not available at this moment.
                </div>
            @endif
        </div>
    </div>
@endsection
