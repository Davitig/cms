@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Menus</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header header-elements">
            <div class="fs-5">Menus</div>
            <span class="count badge bg-label-primary ms-4">{{ number_format($items->total()) }}</span>
            <div class="card-header-elements ms-auto">
                <a href="{{ cms_route('menus.create') }}" class="btn btn-primary">
                    <i class="icon-base fa fa-plus icon-xs me-1"></i>
                    <span>Add New Record</span>
                </a>
            </div>
        </div>
        <div id="items" class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Main</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>ID</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr class="item">
                        <td>
                            <input type="radio" name="main" class="form-check-input" data-id="{{$item->id}}"@checked($item->main)>
                        </td>
                        <td>
                            <a href="{{ cms_route('pages.index', [$item->id]) }}" title="Go to Pages">
                                <i class="icon-base fa fa-indent icon-sm text-primary me-4"></i>
                                <span class="fw-medium">{{ $item->title }}</span>
                            </a>
                        </td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->id }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base fa fa-ellipsis-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="{{ cms_route('menus.edit', [$item->id]) }}" class="dropdown-item">
                                        <i class="icon-base fa fa-edit me-1"></i>
                                        Edit
                                    </a>
                                    {{ html()->form('delete', cms_route('menus.destroy', [$item->id]))->class('form-delete')->open() }}
                                    <button type="submit" class="dropdown-item">
                                        <i class="icon-base fa fa-trash me-1"></i>
                                        Delete
                                    </button>
                                    {{ html()->form()->close() }}
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
@include('admin.-scripts.checkbox-xhr', ['url' => cms_route('menus.update_main')])
