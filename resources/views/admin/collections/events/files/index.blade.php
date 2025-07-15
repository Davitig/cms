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
            <li class="breadcrumb-item">
                <a href="{{ cms_route('events.index', [$foreignModel->collection_id]) }}">Events</a>
            </li>
            <li class="breadcrumb-item active">Files</li>
        </ol>
    </nav>
    <div id="files-block">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center row-gap-4 gap-4 mb-6">
            <div class="fs-4 text-black">{{ $foreignModel->title }}</div>
            <a href="{{ cms_route('events.edit', [$foreignModel->collection_id, $foreignModel->id]) }}" class="ms-md-auto text-dark">
                <i class="icon-base fa fa-caret-left icon-xs"></i>
                Go back
            </a>
            <div class="badge bg-label-primary fs-6">
                Total:
                <span class="count">{{ number_format($items->total()) }}</span>
            </div>
            <div class="d-flex btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary text-dark item-add">
                    <i class="icon-base fa fa-plus icon-xs me-1"></i>
                    <span>Add New Record</span>
                </button>
                <label class="btn btn-outline-secondary text-dark form-check-dark" for="items-multi-select">
                    <input type="checkbox" id="items-multi-select" class="form-check-input me-1">
                    <span>Select All</span>
                </label>
                <button type="button" id="edit-selected-items" class="btn btn-outline-secondary text-dark">
                    <i class="icon-base fa fa-pencil icon-xs me-1"></i>
                    <span>Edit</span>
                </button>
                <button type="button" class="btn btn-outline-secondary text-dark" data-delete-selected
                        data-url="{{cms_route('events.files.destroyMany', [$foreignModel->id])}}">
                    <i class="icon-base fa fa-trash icon-xs me-1"></i>
                    <span>Delete</span>
                </button>
            </div>
        </div>
        <div id="sortable" class="row gy-4">
            @php
                $currentPage = $items->currentPage();
                $lastPage = $items->lastPage();
            @endphp
            @foreach($items as $item)
                <div id="item{{ $item->id }}" class="item col-6 col-md-4 col-lg-2" data-id="{{ $item->id }}" data-pos="{{$item->position}}">
                    <div class="card handle">
                        <div class="py-3 px-5 d-flex justify-content-between align-items-center">
                            <div class="item-title fs-5 fw-medium text-black">{{ $item->title }}</div>
                            @if ($lastPage > 1)
                                <div class="btn-pos-actions d-flex justify-content-{{ $currentPage > 1 ? 'between' : 'end' }}">
                                    @if ($currentPage > 1)
                                        <a href="#" class="move fa fa-arrow-left opacity-75" data-move="prev" title="Move to prev page"></a>
                                    @endif
                                    @if ($currentPage < $lastPage)
                                        <a href="#" class="move fa fa-arrow-right ps-2 opacity-75" data-move="next" title="Move to next page"></a>
                                    @endif
                                </div>
                            @endif
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
                        <div class="py-3 px-5 d-flex gap-6">
                            <a href="#" data-url="{{cms_route('events.files.edit', [$item->event_id, $item->id])}}"
                               class="item-edit" title="{{trans('general.edit')}}">
                                <i class="icon-base fa fa-pencil icon-xs"></i>
                            </a>
                            {{ html()->form('put', cms_route('events.files.visibility', [$item->id]))
                            ->id('visibility' . $item->id)->class('visibility')->open() }}
                            <button type="submit" class="dropdown-item" title="{{trans('general.visibility')}}">
                                <i class="icon-base fa fa-toggle-{{$item->visible ? 'on' : 'off'}} icon-md text-primary"></i>
                            </button>
                            {{ html()->form()->close() }}
                            {{ html()->form('delete', cms_route('events.files.destroy', [$item->event_id, $item->id]))
                            ->class('form-delete')->open() }}
                            <button type="submit" class="dropdown-item">
                                <i class="icon-base fa fa-trash icon-xs text-primary"></i>
                            </button>
                            {{ html()->form()->close() }}
                            <span class="form-check-dark">
                                <input class="form-check-input align-bottom item-select" type="checkbox" data-id="{{ $item->id }}">
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
    @include('admin.-scripts.files-index', [
        'routeCreate' => cms_route('events.files.create', [$foreignModel->id]),
        'routePositions' => cms_route('events.files.positions', [$foreignModel->id]),
        'sort' => 'desc',
        'currentPage' => $currentPage,
        'lastPage' => $lastPage,
        'foreignKey' => 'event_id'
    ])
@endpush
