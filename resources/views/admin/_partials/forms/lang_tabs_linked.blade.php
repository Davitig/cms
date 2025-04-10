@if (language()->containsMany())
    @php
        $activeLang = request('lang', language()->active());
        $langCount = language()->count();
    @endphp
    @foreach (language()->all() as $language)
        <li{!! $activeLang == $language->language ? ' class="active"' : '' !!}>
            <a href="{{cms_route($routeName, $params + ['lang' => $language->language])}}">
                <img src="{{ asset('assets/libs/images/flags/'.$language->language.'.png') }}" width="23" height="13" alt="{{$language->language}}">
                {{-- <span class="visible-xs">{{$language->language}}</span> --}}
                <span class="hidden-xs">
                    {{strtoupper(language()->get($language->language, ($langCount > 5 ? 'short' : 'full') . '_name'))}}
                </span>
            </a>
        </li>
    @endforeach
@endif
