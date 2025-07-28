@if (isset($item) && has_sub_items($item))
    <ul class="dropdown-menu">
        @foreach ($item->sub_items as $item)
            <li>
                <a href="{{$url = web_url($item->url_path)}}" @class(['dropdown-item', 'active' => $url == $currentUrl])>{{$item->short_title}}</a>
                @include('web.-partials.sub_pages', compact('currentUrl'))
            </li>
        @endforeach
    </ul>
@endif
