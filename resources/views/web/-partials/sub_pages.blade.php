@if (isset($item) && has_sub_items($item))
    <ul class="dropdown-menu">
        @foreach ($item->sub_items as $item)
            <li>
                <a href="{{$url = web_url($item->url_path, [], ! $disableMainLang || $item->language != $mainLang ? null : false)}}" @class(['dropdown-item', 'active' => $url == $currentUrl])>
                    {{$item->short_title ?: $item->slug}}
                </a>
                @include('web.-partials.sub_pages', compact('currentUrl', 'mainLang', 'disableMainLang'))
            </li>
        @endforeach
    </ul>
@endif
