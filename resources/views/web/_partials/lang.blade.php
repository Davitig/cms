@if (language()->containsManyVisible())
    <ul class="nav navbar-nav navbar-right text-uppercase">
        @php($currentLang = language()->active())
        @foreach (language()->allVisible() as $key => $value)
            <li{!!$key == $currentLang ? ' class="active"' : ''!!}>
                <a href="{{url($value['path'])}}">{{$value['short_name']}}</a>
            </li>
        @endforeach
    </ul>
@endif
