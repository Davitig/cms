@if (language()->containsMany())
    @php
        $activeLang = request('lang', language()->active());
        $langCount = language()->count();
    @endphp
    @foreach ($items as $current)
        <li{!! $activeLang == $current->language ? ' class="active"' : '' !!}>
            <a href="#item-{{$current->language}}" data-toggle="tab">
                <img src="{{ asset('assets/libs/images/flags/'.$current->language.'.png') }}" width="23" height="13" alt="{{$current->language}}">
                {{-- <span class="visible-xs">{{strtoupper($current->language)}}</span> --}}
                <span class="hidden-xs">
                    {{strtoupper(language()->get($current->language, ($langCount > 5 ? 'short' : 'full') . '_name'))}}
                </span>
            </a>
        </li>
    @endforeach
@endif
