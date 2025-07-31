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
            <li class="breadcrumb-item active">System</li>
        </ol>
    </nav>
    <div class="accordion accordion-custom-button">
        <div class="accordion-item active">
            <button
                type="button"
                class="accordion-button"
                data-bs-toggle="collapse"
                data-bs-target="#sys-app"
                aria-expanded="true"
                aria-controls="sys-app">
                <h5 class="mb-0">Application</h5>
            </button>
            <div id="sys-app" class="accordion-collapse collapse show">
                <div class="accordion-body py-4">
                    <div class="h6">CMS Version: {{ cms_config('version') }}</div>
                    <div class="h6">Framework Version: {{ app()->version() }}</div>
                    <div class="h6">Server IP: {{ request()->ip() }}</div>
                    <div class="h6">Debug Mode: <span @class(['text-warning' => $debugModeEnabled = app()->hasDebugModeEnabled()])">{{ $debugModeEnabled ? 'Enabled' : 'Disabled' }}</span></div>
                    <div class="h6">Timezone: {{ config('app.timezone') }}</div>
                    <div class="h6">Database: {{ app('db')->getConfig('driver') }}</div>
                    <div class="h6">Session Driver: {{ config('session.driver') }}</div>
                    <div class="h6">Queue Connection: {{ config('queue.default') }}</div>
                    <div class="h6 mb-0">Cache Driver: {{ config('cache.default') }}</div>
                </div>
            </div>
        </div>
        <div class="accordion-item active">
            <button
                type="button"
                class="accordion-button"
                data-bs-toggle="collapse"
                data-bs-target="#sys-server"
                aria-expanded="true"
                aria-controls="sys-server">
                <h5 class="mb-0">Server</h5>
            </button>
            <div id="sys-server" class="accordion-collapse collapse show">
                <div class="accordion-body py-4">
                    <div class="h6">PHP Version: {{ phpversion() }}</div>
                    <div class="h6">Memory limit: {{ @ini_get('memory_limit') }}</div>
                    <div class="h6">Max Execution Time: {{ @ini_get('max_execution_time') }}s</div>
                    <div class="h6">Web Server: {{ request()->server->get('SERVER_SOFTWARE') }}</div>
                    <div class="h6 mb-0">OS: {{ PHP_OS }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
