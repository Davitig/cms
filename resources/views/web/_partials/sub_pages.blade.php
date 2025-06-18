@if (isset($item) && has_sub_items($item))
    <ul class="dropdown-menu">
        @foreach ($item->sub_items as $item)
            <li>
                <a href="{{web_url($item->url_path)}}" class="dropdown-item">{{$item->short_title}}</a>
                @include('web._partials.sub_pages')
            </li>
        @endforeach
    </ul>
@endif
