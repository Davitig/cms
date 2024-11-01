@if (is_multilanguage())
    @php
        $activeLang = request('lang', language());
        $langCount = count(languages());
    @endphp
    @foreach ($items as $current)
        <li{!! $activeLang == $current->language ? ' class="active"' : '' !!}>
            <a href="#item-{{$current->language}}" data-toggle="tab">
                <img src="{{ asset('assets/libs/images/flags/'.$current->language.'.png') }}" width="23" height="13" alt="{{$current->language}}">
                {{-- <span class="visible-xs">{{strtoupper($current->language)}}</span> --}}
                <span class="hidden-xs">
                    {{strtoupper(language($current->language, ($langCount > 5 ? 'short' : 'full') . '_name'))}}
                </span>
            </a>
        </li>
    @endforeach
@else
    @if ($items->count() <= 1)
        <li class="active">
            <a href="#item-{{$items->first()->language}}" data-toggle="tab">
                <span class="visible-xs"><i class="fa fa-home"></i></span>
                <span class="hidden-xs">
                    <i class="fa fa-home"></i> General
                </span>
            </a>
        </li>
    @endif
@endif
