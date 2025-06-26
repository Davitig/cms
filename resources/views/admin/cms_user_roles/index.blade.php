@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">CMS User Roles</li>
        </ol>
    </nav>
    @includeFirst([
        'admin.cms_user_roles.index_' . $cmsSettings->get('roles_list_view'),
        'admin.cms_user_roles.index_table'
    ])
    {{ $items->links() }}
@endsection
