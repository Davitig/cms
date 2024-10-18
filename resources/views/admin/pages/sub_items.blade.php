@if (isset($item) && has_model_sub_items($item))
    <ul>
        @foreach ($item->sub_items as $item)
            <li id="item{{ $item->id }}" class="item{{$item->collapse ? ' uk-collapsed' : ''}}" data-id="{{ $item->id }}" data-pos="{{$item->position}}">
                <div class="uk-nestable-item">
                    <div class="row">
                        <div class="col-sm-7 col-xs-10">
                            <div class="uk-nestable-handle pull-left"></div>
                            <div data-nestable-action="toggle"></div>
                            <div class="list-label"><a href="{{ $editUrl = cms_route('pages.edit', [$item->menu_id, $item->id]) }}">{{ $item->short_title }}</a></div>
                        </div>
                        <div class="col-sm-5 col-xs-2">
                            <div class="btn-action togglable pull-right">
                                <div class="btn btn-gray item-id disabled">#{{$item->id}}</div>
                                <a href="{{web_url($item->slug)}}" class="link btn btn-white" title="Go to page" data-slug="{{$item->slug}}" target="_blank">
                                    <span class="fa fa-link"></span>
                                </a>
                                <a href="#" class="transfer btn btn-white" title="Transfer to another menu" data-id="{{$item->id}}">
                                    <span class="{{icon_type('menus')}}"></span>
                                </a>
                                {{ html()->form('post', cms_route('pages.visibility', [$item->id]))->id('visibility' . $item->id)->class('visibility')->open() }}
                                <button type="submit" class="btn btn-{{$item->visible ? 'white' : 'gray'}}" title="{{trans('general.visibility')}}">
                                    <span class="fa fa-eye{{$item->visible ? '' : '-slash'}}"></span>
                                </button>
                                {{ html()->form()->close() }}
                                <a href="{{ cms_route('pages.files.index', [$item->id]) }}" class="btn btn-{{$item->has_file ? 'turquoise' : 'white'}}" title="{{trans('general.files')}}">
                                    <span class="{{icon_type('files')}}"></span>
                                </a>
                                <span title="{{ucfirst($type = $item->collection_type ?: $item->type)}}">
                                    <a href="{{$typeUrl = (array_key_exists($item->type, cms_config('pages.explicit'))
                                    ? cms_route($item->type . '.index') : (array_key_exists($item->type, cms_config('pages.implicit'))
                                    ? cms_route($item->collection_type . '.index', [$item->type_id]) : '#'))}}" class="btn btn-{{$typeUrl == '#' ? 'white disabled' : 'info'}}">
                                        <span class="{{icon_type($type, 'fa fa-file-text-o')}}"></span>
                                    </a>
                                </span>
                                <a href="{{ cms_route('pages.create', [$item->menu_id, 'parent_id' => $item->id]) }}" class="btn btn-secondary" title="{{trans('general.create')}}">
                                    <span class="fa fa-plus"></span>
                                </a>
                                <a href="{{ $editUrl }}" class="btn btn-orange" title="{{trans('general.edit')}}">
                                    <span class="fa fa-edit"></span>
                                </a>
                                {{ html()->form('delete', cms_route('pages.destroy', [$menu->id, $item->id]))->class('form-delete')->open() }}
                                <button type="submit" class="btn btn-danger" title="{{trans('general.delete')}}"{{has_model_sub_items($item) ? ' disabled' : ''}}>
                                    <span class="fa fa-trash"></span>
                                </button>
                                {{ html()->form()->close() }}
                            </div>
                            <a href="#" class="btn btn-primary btn-toggle pull-right">
                                <span class="fa fa-toggle-left"></span>
                            </a>
                        </div>
                    </div>
                </div>
                @include('admin.pages.sub_items')
            </li>
        @endforeach
    </ul>
@endif
