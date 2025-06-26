@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ cms_route('products.index') }}">Products</a>
            </li>
            <li class="breadcrumb-item active">Files</li>
        </ol>
    </nav>
    <div id="files-block">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center row-gap-4 mb-6">
            <div class="fs-4">
                <a href="{{ cms_route('products.edit', [$foreignModel->id]) }}" class="text-black">
                    {{ $foreignModel->title }}
                    <i class="icon-base fa fa-caret-left icon-lg text-primary"></i>
                </a>
            </div>
            <div class="d-flex align-items-center flex-wrap flex-row-reverse flex-md-row gap-4">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary item-add">
                        <i class="icon-base fa fa-plus icon-xs me-1"></i>
                        <span>Add New Record</span>
                    </button>
                    <label class="btn btn-outline-secondary form-check-dark" for="items-multi-select">
                        <input type="checkbox" id="items-multi-select" class="form-check-input me-1">
                        <span>Select All</span>
                    </label>
                    <button type="button" id="edit-selected-items" class="btn btn-outline-secondary">
                        <i class="icon-base fa fa-pencil icon-xs me-1"></i>
                        <span>Edit</span>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-delete data-delete-selected="true"
                            data-url="{{cms_route('products.files.destroyMany', [$foreignModel->product_id])}}">
                        <i class="icon-base fa fa-trash icon-xs me-1"></i>
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        </div>
        <div id="sortable" class="row gy-4">
            @foreach($items as $item)
                <div id="item{{ $item->id }}" class="item col-6 col-md-4 col-lg-2"
                     data-id="{{ $item->id }}" data-pos="{{$item->position}}">
                    <div class="card">
                        <div class="py-3 px-5">
                            <div class="item-title fs-5 fw-medium text-black">{{ $item->title }}</div>
                        </div>
                        @if (in_array($ext = pathinfo($item->file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{$item->file}}" width="100%" height="150"  class="item-img cursor-move" alt="{{ $item->title }}">
                        @elseif(! empty($ext))
                            <img src="{{asset('assets/default/img/file-ext-icons/'.$ext.'.png')}}" width="100%" height="150"
                                 class="item-img cursor-move" alt="{{ $item->title }}">
                        @else
                            <img src="{{asset('assets/default/img/file-ext-icons/www.png')}}" width="100%" height="150"
                                 class="item-img cursor-move" alt="{{ $item->title }}">
                        @endif
                        <div class="py-3 px-5">
                            <a href="#" data-url="{{cms_route('products.files.edit', [$item->product_id, $item->id])}}"
                               class="card-link item-edit" title="{{trans('general.edit')}}">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a href="#" data-url="{{cms_route('products.files.visibility', [$item->id])}}"
                               class="card-link visibility" title="{{trans('general.visibility')}}">
                                <i class="fa fa-eye{{$item->visible ? '' : '-slash'}}"></i>
                            </a>
                            <a href="#" data-url="{{cms_route('products.files.destroy', [$item->product_id, $item->id])}}"
                               data-delete class="card-link" title="{{trans('general.delete')}}">
                                <i class="fa fa-trash"></i>
                            </a>
                            <span class="card-link form-check-dark">
                                <input class="form-check-input item-select" type="checkbox" data-id="{{ $item->id }}">
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {!! $items->links() !!}
    </div>
@endsection
@push('body.bottom')
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/sortablejs/sortable.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            sortable('{{cms_route('products.files.updatePosition', [$foreignModel->id])}}', '{{csrf_token()}}', 'desc');
        });
    </script>
    @include('admin._scripts.files_index', [
        'routeCreate' => cms_route('products.files.create', [$foreignModel->id]),
        'routePosition' => cms_route('products.files.updatePosition'),
        'sort' => 'desc',
        'currentPage' => $items->currentPage(),
        'lastPage' => $items->lastPage()
    ])
@endpush
