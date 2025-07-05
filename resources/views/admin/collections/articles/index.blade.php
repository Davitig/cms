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
            <li class="breadcrumb-item active">Articles</li>
        </ol>
    </nav>
    <div class="row flex-lg-row-reverse g-6">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header header-elements flex-column flex-md-row align-items-md-center align-items-start gap-4">
                    <div class="d-flex">
                        <div class="fs-5">Articles</div>
                        <span class="count badge bg-label-primary ms-4">{{ $items->total() }}</span>
                    </div>
                    <div class="card-header-elements ms-md-auto flex-row-reverse flex-md-row">
                        <a href="{{ cms_route('collections.edit', [$parent->id]) }}" class="btn" title="Edit Collection">
                            <i class="icon-base fa fa-gear icon-xs"></i>
                        </a>
                        <a href="{{ cms_route('articles.create', [$parent->id]) }}" class="btn btn-primary">
                            <i class="icon-base fa fa-plus icon-xs me-1"></i>
                            <span>Add New Record</span>
                        </a>
                    </div>
                </div>
                <div id="items" class="card-body">
                    <ul class="list-group list-group-flush" id="sortable">
                        @php
                            $currentPage = $items->currentPage();
                            $lastPage = $items->lastPage();
                        @endphp
                        @foreach($items as $item)
                            <li id="item{{ $item->id }}" class="item list-group-item ps-0 d-flex justify-content-between align-items-center"
                                data-id="{{ $item->id }}" data-pos="{{ $item->position }}">
                                <div>
                                    @if ($parent->admin_order_by == 'position')
                                        <i class="handle cursor-move icon-base fa fa-bars icon-sm align-text-bottom me-1"></i>
                                    @endif
                                    <a href="{{ $editUrl = cms_route('articles.edit', [$item->collection_id, $item->id]) }}" class="text-black">
                                        {{ $item->title }}
                                    </a>
                                </div>
                                <div class="actions d-flex align-items-center gap-4">
                                    <div class="item-id badge bg-label-gray text-black">{{ $item->id }}</div>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="icon-base fa fa-ellipsis-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="{{ $editUrl }}" class="dropdown-item">
                                                <i class="icon-base fa fa-edit me-1"></i>
                                                Edit
                                            </a>
                                            {{ html()->form('put', cms_route('articles.visibility', [$item->id]))->id('visibility' . $item->id)->class('visibility')->open() }}
                                            <button type="submit" class="dropdown-item" title="{{trans('general.visibility')}}">
                                                <i class="icon-base fa fa-toggle-{{$item->visible ? 'on' : 'off'}} icon-sm me-2"></i>
                                                Visibility
                                            </button>
                                            {{ html()->form()->close() }}
                                            <a href="{{ cms_route('articles.files.index', [$item->id]) }}" class="dropdown-item">
                                                <i class="icon-base fa fa-paperclip me-1"></i>
                                                Files
                                            </a>
                                            <button class="dropdown-item transfer" title="Transfer to other collection" data-id="{{$item->id}}">
                                                <i class="icon-base fa fa-list-alt icon-sm me-1"></i>
                                                Transfer
                                            </button>
                                            {{ html()->form('delete', cms_route('articles.destroy', [$item->collection_id, $item->id]))->class('form-delete')->open() }}
                                            <button type="submit" class="dropdown-item">
                                                <i class="icon-base fa fa-trash me-1"></i>
                                                Delete
                                            </button>
                                            {{ html()->form()->close() }}
                                        </div>
                                    </div>
                                    @if ($lastPage > 1)
                                        <div class="btn-pos-actions d-flex justify-content-{{ $currentPage > 1 ? 'between' : 'end' }} gap-2">
                                            @if ($currentPage > 1)
                                                <a href="#" class="move fa fa-arrow-left opacity-75" data-move="prev" title="Move to prev page"></a>
                                            @endif
                                            @if ($currentPage < $lastPage)
                                                <a href="#" class="move fa fa-arrow-right opacity-75" data-move="next" title="Move to next page"></a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    {{ $items->links() }}
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            @include('admin.collections.similar_type_menu')
        </div>
    </div>
@endsection
@push('body.bottom')
    @include('admin.-scripts.transfer', ['route' => cms_route('articles.transfer', [$parent->id]), 'column' => 'collection_id', 'list' => $parentTypes, 'parentId' => $parent->id])
    @if ($parent->admin_order_by == 'position')
        <script src="{{ asset('assets/vendor/libs/sortablejs/sortable.js') }}"></script>
        <script type="text/javascript">
            $(function () {
                sortable('{{cms_route('articles.positions')}}', '{{csrf_token()}}', '{{ $parent->admin_sort }}', {{ $currentPage }});
            });
        </script>
    @endif
@endpush
