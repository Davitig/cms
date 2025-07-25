@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Products</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header header-elements">
            <div class="fs-5">Products</div>
            <span class="count badge bg-label-primary ms-4">{{ number_format($items->total()) }}</span>
            <div class="card-header-elements ms-auto">
                <a href="{{ cms_route('products.create') }}" class="btn btn-primary">
                    <i class="icon-base fa fa-plus icon-xs me-1"></i>
                    <span>Add New Record</span>
                </a>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>QTY</th>
                    <th>ID</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr class="item">
                        <td>
                            @if ($item->image)
                                <img src="{{ $item->image }}" width="40" height="40" alt="Image" class="rounded-2 me-4">
                            @else
                                <i class="icon-base fa fa-image icon-40px me-4"></i>
                            @endif
                            <a href="{{ $editUrl = cms_route('products.edit', [$item->id]) }}" class="text-dark">
                                {{ $item->title ?: $item->slug }}
                            </a>
                        </td>
                        <td>
                            <i class="icon-base fa-regular fa-circle-{{ $item->in_stock ? 'check' : 'xmark' }} text-{{ $item->in_stock ? 'success' : 'danger' }}"
                               title="{{ $item->in_stock ? 'In stock' : 'Out of stock' }}"></i>
                        </td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->id }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base fa fa-ellipsis-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a href="{{ $editUrl }}" class="dropdown-item">
                                        <i class="icon-base fa fa-edit me-1"></i>
                                        Edit
                                    </a>
                                    {{ html()->form('put', cms_route('products.visibility', [$item->id]))->id('visibility' . $item->id)->class('visibility')->open() }}
                                    <button type="submit" class="dropdown-item" title="{{trans('general.visibility')}}">
                                        <i class="icon-base fa fa-toggle-{{$item->visible ? 'on' : 'off'}} icon-sm me-2"></i>
                                        Visibility
                                    </button>
                                    {{ html()->form()->close() }}
                                    <a href="{{ cms_route('products.files.index', [$item->id]) }}" class="dropdown-item">
                                        <i class="icon-base fa fa-paperclip me-1"></i>
                                        Files
                                    </a>
                                    {{ html()->form('delete', cms_route('products.destroy', [$item->id]))->class('form-delete')->open() }}
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
