@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Collections</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header header-elements flex-column flex-md-row align-items-md-center align-items-start gap-4">
            <div class="d-flex gap-4">
                <div class="fs-5">Collections</div>
                <span class="count badge bg-label-primary">{{ number_format($items->total()) }}</span>
            </div>
            <div class="card-header-elements ms-md-auto flex-md-row flex-column align-items-md-center align-items-start gap-4">
                <a href="{{ cms_route('collections.create') }}" class="btn btn-primary">
                    <i class="icon-base fa fa-plus icon-xs me-1"></i>
                    <span>Add New Record</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div id="items" class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>ID</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($items as $item)
                        <tr class="item">
                            <td class="fw-bold">
                                <a href="{{ $typeUrl = cms_route($item->type . '.index', [$item->id]) }}" title="Go to {{ $item->title }}">
                                    {{ $item->title }}
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-outline-dark">{{ $item->type }}</span>
                            </td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->id }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="icon-base fa fa-ellipsis-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a href="{{ $typeUrl }}" class="dropdown-item">
                                            <i class="icon-base fa fa-forward-step me-1"></i>
                                            Go to {{ $item->type }}
                                        </a>
                                        {{ html()->form('delete', cms_route('collections.destroy', [$item->id]))->class('form-delete')->open() }}
                                        <a href="{{ cms_route('collections.edit', [$item->id]) }}" class="dropdown-item">
                                            <i class="icon-base fa fa-edit me-1"></i>
                                            Edit
                                        </a>
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
    </div>
    {{ $items->links() }}
@endsection
