@if (is_multilanguage())
    <ul class="nav navbar-nav navbar-right text-uppercase">
        @php($currentLang = language())
        @foreach (languages() as $key => $value)
            <li{!!$key == $currentLang ? ' class="active"' : ''!!}>
                <a href="{{$value['url']}}">{{$value['short_name']}}</a>
            </li>
        @endforeach
    </ul>
@endif
