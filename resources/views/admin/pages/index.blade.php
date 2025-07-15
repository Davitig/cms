@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ cms_route('menus.index') }}">Menus</a>
            </li>
            <li class="breadcrumb-item active">Pages</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header header-elements flex-column flex-md-row align-items-md-center align-items-start gap-4">
            <div class="d-flex">
                <div class="fs-5">{{ $menu->title }}</div>
                <span class="count badge bg-label-primary ms-4">{{ number_format($itemsCount = $items->count()) }}</span>
            </div>
            <div class="card-header-elements ms-md-auto flex-row-reverse flex-md-row">
                <a href="{{ cms_route('menus.edit', [$menu->id]) }}" class="btn" title="Edit Menu">
                    <i class="icon-base fa fa-gear icon-xs"></i>
                </a>
                <a href="{{ cms_route('pages.create', [$menu->id]) }}" class="btn btn-primary">
                    <i class="icon-base fa fa-plus icon-xs me-1"></i>
                    <span>Add New Record</span>
                </a>
            </div>
        </div>
        <div id="items" class="card-body" data-parent-slug="">
            <ul class="uk-nestable list-group list-group-flush" data-uk-nestable="{handleClass:'uk-nestable-handle'}">
                @php($prevPos = 0)
                @foreach(make_sub_items($items) as $item)
                    <li id="item{{ $item->id }}" class="item uk-nestable-item list-group-item ps-0 m-0" data-id="{{ $item->id }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="uk-nestable-handle cursor-move icon-base fa fa-bars icon-sm align-text-bottom me-1"></i>
                                <a href="{{ $editUrl = cms_route('pages.edit', [$item->menu_id, $item->id]) }}" class="text-black">
                                    {{ $item->title }}
                                </a>
                                @if ($prevPos == $item->position)
                                    <i class="icon-base fa fa-question-circle icon-xs ms-2 text-warning duplicated-position cursor-pointer"
                                       data-id="{{ $item->id }}" title="Duplicated position detected"></i>
                                @endif
                            </div>
                            <div class="actions d-flex align-items-center gap-4">
                                <div class="item-id badge bg-label-gray text-black">{{ $item->id }}</div>
                                <a href="{{ web_url($item->url_path) }}" class="link" data-slug="{{ $item->slug }}" target="_blank" title="View Website Page">
                                    <i class="icon-base fa fa-link icon-sm"></i>
                                </a>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="icon-base fa fa-ellipsis-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a href="{{ $editUrl }}" class="dropdown-item">
                                            <i class="icon-base fa fa-edit me-1"></i>
                                            Edit
                                        </a>
                                        {{ html()->form('put', cms_route('pages.visibility', [$item->id]))->id('visibility' . $item->id)->class('visibility')->open() }}
                                        <button type="submit" class="dropdown-item" title="{{trans('general.visibility')}}">
                                            <i class="icon-base fa fa-toggle-{{$item->visible ? 'on' : 'off'}} icon-sm me-2"></i>
                                            Visibility
                                        </button>
                                        {{ html()->form()->close() }}
                                        <a href="{{ cms_route('pages.create', [$menu->id, 'parent_id' => $item->id]) }}" class="dropdown-item" title="{{trans('general.create')}}">
                                            <span class="icon-base fa fa-plus me-1"></span>
                                            Add Sub Page
                                        </a>
                                        <a href="{{ cms_route('pages.files.index', [$item->id]) }}" class="dropdown-item">
                                            <i class="icon-base fa fa-paperclip me-1"></i>
                                            Files
                                        </a>
                                        <button class="dropdown-item transfer" title="Transfer to other menu" data-id="{{$item->id}}">
                                            <i class="icon-base fa fa-indent icon-sm me-1"></i>
                                            Transfer
                                        </button>
                                        @if ($typeUrl = (array_key_exists($item->type, cms_pages('extended')) || array_key_exists($item->type, cms_pages('listable.collections'))
                                        ? cms_route($item->type . '.index', [$item->type_id]) : null))
                                            <a href="{{$typeUrl}}" class="dropdown-item">
                                                <span class="icon-base fa fa-circle-right me-1"></span>
                                                {{ucfirst($item->type)}}
                                            </a>
                                        @endif
                                        {{ html()->form('delete', cms_route('pages.destroy', [$item->menu_id, $item->id]))->class('form-delete')->open() }}
                                        <button type="submit" class="dropdown-item">
                                            <i class="icon-base fa fa-trash me-1"></i>
                                            Delete
                                        </button>
                                        {{ html()->form()->close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('admin.pages.sub_items')
                    </li>
                    @php($prevPos = $item->position)
                @endforeach
            </ul>
        </div>
    </div>
@endsection
@push('head')
    <link rel="stylesheet" href="{{ asset('assets/default/libs/uikit-2.27.5/css/components/nestable.min.css') }}">
@endpush
@push('body.bottom')
    @include('admin.-scripts.transfer', [
    'route' => cms_route('pages.transfer', [$menu->id]), 'column' => 'menu_id', 'id' => $menu->id, 'recursive' => true
    ])
    <script type="text/javascript">
        $(function () {
            nestable('{{ cms_route('pages.positions') }}', '{{ csrf_token() }}', 'asc', 'menu_id');

            $('.uk-nestable').on('positionUpdated', function () {
                updateSubItems($(this).find('> li'));
            });
        });
    </script>
    <script src="{{ asset('assets/default/libs/uikit-2.27.5/js/uikit.min.js') }}"></script>
    <script src="{{ asset('assets/default/libs/uikit-2.27.5/js/components/nestable.min.js') }}"></script>
@endpush
