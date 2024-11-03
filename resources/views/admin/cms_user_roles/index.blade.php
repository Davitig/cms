@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('roles')}}"></i>
                CMS User Roles
            </h1>
            <p class="description">Management of the CMS user roles</p>
        </div>
        <div class="breadcrumb-env">
            <ol class="breadcrumb bc-1">
                <li>
                    <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li class="active">
                    <i class="{{$icon}}"></i>
                    <strong>CMS User Roles</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">List of all CMS user roles</h2>
            <div class="panel-options">
                <a href="#" data-toggle="panel">
                    <span class="collapse-icon">&ndash;</span>
                    <span class="expand-icon">+</span>
                </a>
            </div>
        </div>
        <div class="panel-body">
            <a href="{{ cms_route('cmsUserRoles.create') }}" class="btn btn-secondary btn-icon-standalone">
                <i class="{{$icon}}"></i>
                <span>{{ trans('general.create') }}</span>
            </a>
            <table id="items" class="table stacktable table-bordered table-striped">
                <thead>
                <tr>
                    <th>Role</th>
                    <th>Full Access</th>
                    <th>Permissions</th>
                    <th>ID</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr id="item{{$item->id}}" class="item">
                        <td class="text-primary">{{ ucfirst($item->role) }}</td>
                        <td class="text-{{$item->full_access ? 'success' : 'red'}}">{{ $item->full_access ? 'Yes' : 'No' }}</td>
                        <td>
                            @if (! $item->full_access)
                                <a href="{{ cms_route('permissions.index', ['role' => $item->id]) }}" class="btn btn-orange">Set Permissions</a>
                            @endif
                        </td>
                        <td>{{ $item->id }}</td>
                        <td>
                            <div class="btn-action">
                                <a href="{{ cms_route('cmsUserRoles.edit', [$item->id]) }}" class="btn btn-orange" title="{{trans('general.edit')}}">
                                    <span class="fa fa-edit"></span>
                                </a>
                                {{ html()->form('delete', cms_route('cmsUserRoles.destroy', [$item->id]))->class('form-delete')->open() }}
                                <button type="submit" class="btn btn-danger" title="{{trans('general.delete')}}">
                                    <span class="fa fa-trash"></span>
                                </button>
                                {{ html()->form()->close() }}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
