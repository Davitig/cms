@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">CMS Users</li>
        </ol>
    </nav>
    <div class="card mb-4">
        <div class="card-header fs-5">Filter</div>
        <div class="card-body">
            <form action="{{cms_route('cmsUsers.index')}}" method="GET">
                <div class="row">
                    <div class="col-md-2 pe-0 mb-2">
                        <input type="text" name="name" class="form-control" placeholder="First name / Last Name" value="{{request('name')}}">
                    </div>
                    <div class="col-md-2 pe-0 mb-2">
                        <input type="text" name="email" class="form-control" placeholder="E-mail" value="{{request('email')}}">
                    </div>
                    <div class="col-md-2 pe-0 mb-2">
                        {{ html()->select('role', [
                            '' => '-- Role --',
                        ] + $roles, request('role'))->class('form-select') }}
                    </div>
                    <div class="col-md-2 pe-0 mb-2">
                        {{ html()->select('suspended', [
                            '' => '-- Status --',
                            '0' => 'Active',
                            '1' => 'Suspended'
                        ], request('suspended'))->class('form-select') }}
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary me-2">Search</button>
                        <a href="{{cms_route('cmsUsers.index', request()->only(['role']))}}" class="btn btn-label-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header header-elements gap-4">
            <div class="fs-5">CMS Users</div>
            <span class="count badge bg-label-primary">{{ number_format($items->total()) }}</span>
            <div class="card-header-elements ms-auto">
                <a href="{{ cms_route('cmsUsers.create') }}" class="btn btn-primary">
                    <i class="icon-base fa fa-plus icon-xs me-1"></i>
                    <span>Add New Record</span>
                </a>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>E-Mail</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>ID</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr class="item">
                        <td>
                            <img src="{{ cms_route('cmsUsers.photo', [$item->id]) }}" width="40" height="40" alt="Photo" class="rounded-circle me-4" />
                            <a href="{{cms_route('cmsUsers.show', [$item->id])}}" class="text-black{{auth('cms')->id() == $item->id ? ' active' : ''}}">
                                {{$item->first_name}} {{$item->last_name}}
                            </a>
                        </td>
                        <td>{{ $item->email }}</td>
                        <td>{{ ucfirst($item->role) }}</td>
                        <td>
                            <span class="badge bg-label-{{ $item->suspended ? 'warning' : 'primary' }} me-1">{{ $item->suspended ? 'Suspended' : 'Active' }}</span>
                        </td>
                        <td>{{ $item->id }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base fa fa-ellipsis-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    @if (auth('cms')->user()->hasFullAccess() || auth('cms')->id() == $item->id)
                                        <a href="{{ cms_route('cmsUsers.edit', [$item]) }}" class="dropdown-item">
                                            <i class="icon-base fa fa-edit icon-sm me-1"></i>
                                            Edit
                                        </a>
                                    @endif
                                    @if (auth('cms')->user()->hasFullAccess() && auth('cms')->id() != $item->id)
                                        {{ html()->form('delete', cms_route('cmsUsers.destroy', [$item->id]))->class('form-delete')->open() }}
                                        <button type="submit" class="dropdown-item">
                                            <i class="icon-base fa fa-trash icon-sm me-1"></i>
                                            Delete
                                        </button>
                                        {{ html()->form()->close() }}
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $items->links() }}
@endsection
