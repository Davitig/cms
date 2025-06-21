@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">File Manager</li>
        </ol>
    </nav>
    <iframe src="{{ cms_route('fileManager.index') }}" width="100%" height="700" tabindex="-1"></iframe>
@endsection
