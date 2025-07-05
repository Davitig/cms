@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">CMS User Roles</li>
        </ol>
    </nav>
    @includeFirst([
        'admin.cms-user-roles.index-' . $preferences->get('roles_list_view'),
        'admin.cms-user-roles.index-table'
    ])
    {{ $items->links() }}
@endsection
