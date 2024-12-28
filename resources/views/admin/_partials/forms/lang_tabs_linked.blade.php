@if (is_multilanguage())
    @php($langCount = count(languages()))
    @foreach ($items as $current)
        <li>
            <a href="{{cms_route($routeName, $params + ['lang' => $current->language])}}">
                <img src="{{ asset('assets/libs/images/flags/'.$current->language.'.png') }}" width="23" height="13" alt="{{$current->language}}">
                {{-- <span class="visible-xs">{{$current->language}}</span> --}}
                <span class="hidden-xs">
                    {{strtoupper(language($current->language, ($langCount > 5 ? 'short' : 'full') . '_name'))}}
                </span>
            </a>
        </li>
    @endforeach
@endif
