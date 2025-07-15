@if (language()->countVisible() > 1)
    <ul class="navbar-nav navbar-end text-uppercase">
        @php($currentLang = language()->active())
        @foreach (language()->allVisible() as $key => $value)
            <li class="nav-item">
                <a href="{{url($value['path'])}}" class="nav-link{{ $key == $currentLang ? ' active' : '' }}">{{$value['short_name']}}</a>
            </li>
        @endforeach
    </ul>
@endif
