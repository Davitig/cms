@if (! empty($item) || ! empty($itemInput))
    <div id="item{{ $item->id }}" class="item col-6 col-md-4 col-lg-2" data-id="{{ $item->id }}" data-pos="{{$item->position}}">
        <div class="card handle">
            <div class="py-3 px-5">
                <div class="item-title fs-5 fw-medium text-black">{{ $itemInput['title'] }}</div>
                @if ($lastPage > 1)
                    <div class="btn-pos-actions d-flex justify-content-{{ $currentPage > 1 ? 'between' : 'end' }} pt-2">
                        @if ($currentPage > 1)
                            <a href="#" class="move fa fa-arrow-left" data-move="prev" title="Move to prev page"></a>
                        @endif
                        @if ($currentPage < $lastPage)
                            <a href="#" class="move fa fa-arrow-right" data-move="next" title="Move to next page"></a>
                        @endif
                    </div>
                @endif
            </div>
            @if (in_array($ext = pathinfo($itemInput['file'], PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                <img src="{{$itemInput['file']}}" width="100%" height="150"  class="item-img cursor-move" alt="{{ $itemInput['title'] }}">
            @elseif(! empty($ext))
                <img src="{{asset('assets/default/img/file-ext-icons/'.$ext.'.png')}}" width="100%" height="150"
                     class="item-img cursor-move" alt="{{ $itemInput['title'] }}">
            @else
                <img src="{{asset('assets/default/img/file-ext-icons/www.png')}}" width="100%" height="150"
                     class="item-img cursor-move" alt="{{ $itemInput['title'] }}">
            @endif
            <div class="py-3 px-5 d-flex gap-6">
                <a href="#" data-url="{{cms_route('products.files.edit', [$item->product_id, $item->id])}}"
                   class="item-edit" title="{{trans('general.edit')}}">
                    <i class="icon-base fa fa-pencil icon-xs"></i>
                </a>
                {{ html()->form('put', cms_route('products.files.visibility', [$item->id]))
                ->id('visibility' . $item->id)->class('visibility')->open() }}
                <button type="submit" class="dropdown-item" title="{{trans('general.visibility')}}">
                    <i class="icon-base fa fa-toggle-{{$item->visible ? 'on' : 'off'}} icon-md text-primary"></i>
                </button>
                {{ html()->form()->close() }}
                {{ html()->form('delete', cms_route('products.files.destroy', [$item->product_id, $item->id]))
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
@endif
