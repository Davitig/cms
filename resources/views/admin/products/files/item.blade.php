@if (! empty($item) || ! empty($itemInput))
    <div id="item{{ $item->id }}" class="item col-6 col-md-4 col-lg-2" data-id="{{ $item->id }}" data-pos="{{$item->position}}">
        <div class="card">
            <div class="py-3 px-5">
                <div class="item-title fs-5 fw-medium text-black">{{ $itemInput['title'] }}</div>
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
                   data-delete class="card-link item-delete" title="{{trans('general.delete')}}">
                    <i class="fa fa-trash"></i>
                </a>
                <span class="card-link form-check-dark">
                    <input class="form-check-input item-select" type="checkbox" data-id="{{ $item->id }}">
                </span>
            </div>
        </div>
    </div>
@endif
