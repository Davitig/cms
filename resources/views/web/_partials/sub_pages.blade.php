@if (isset($item) && has_sub_items($item))
    <ul class="dropdown-menu">
        @foreach ($item->sub_items as $item)
            <li{!!$current->slug == $item->slug ? ' class="active"' : ''!!}>
                <a href="{{web_url($item->full_slug)}}">{{$item->short_title}}</a>
                @include('web._partials.sub_pages')
            </li>
        @endforeach
    </ul>
@endif
