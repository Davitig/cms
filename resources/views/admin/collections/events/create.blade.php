@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ cms_route('collections.index') }}">Collections</a>
            </li>
            <li class="breadcrumb-item active">Events</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header fs-5">Events</div>
        <div class="card-body">
            @includeWhen(! language()->queryStringOrActive(), 'admin.-alerts.resource-requires-lang')
            {{ html()->modelForm($current, 'post', cms_route('events.store', [$current->collection_id]))->open() }}
            @include('admin.collections.events.form')
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection
