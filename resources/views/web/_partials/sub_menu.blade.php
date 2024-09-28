@if (isset($item) && has_model_sub_items($item))
    <ul class="dropdown-menu">
        @foreach ($item->sub_items as $item)
            <li{!!$current->slug == $item->slug ? ' class="active"' : ''!!}>
                <a href="{{web_url($item->slug)}}">{{$item->short_title}}</a>
                @include('web._partials.sub_menu')
            </li>
        @endforeach
    </ul>
@endif
