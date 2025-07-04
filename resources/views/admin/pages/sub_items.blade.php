@if (isset($item) && has_sub_items($item))
    <ul class="uk-nestable-list">
        @foreach($item->sub_items as $item)
            <li id="item{{ $item->id }}" class="item uk-nestable-item list-group-item ps-0 m-0"
                data-id="{{ $item->id }}" data-pos="{{ $item->position }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="uk-nestable-handle cursor-move icon-base fa fa-bars icon-sm align-text-bottom me-1"></i>
                        <a href="{{ $editUrl = cms_route('pages.edit', [$item->menu_id, $item->id]) }}" class="text-black">
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
                                @if ($typeUrl = (array_key_exists($item->type, cms_pages('extended')) || array_key_exists($item->type, cms_pages('listable.menus'))
                                ? cms_route($item->type . '.index', [$item->type_id]) : null))
                                    <a href="{{$typeUrl}}" class="dropdown-item">
                                        <span class="fa fa-bars"></span>
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
        @endforeach
    </ul>
@endif
