@if (language()->countVisible() > 1)
    <ul class="navbar-nav navbar-end text-uppercase">
        @php
            $mainLang = language()->main();
            $currentLang = language()->active();
            $path = request()->route()?->getActionName() == 'Closure' ? '/' : request()->path();
            $path = str($path)->replaceStart($currentLang, '');
            $disableMainLang = language()->getSettings('disable_main_language_from_url') ||
                               language()->getSettings('redirect_from_main');
        @endphp
        @foreach (language()->allVisible() as $key => $value)
            <li class="nav-item">
                <a href="{{web_url($path, [], ! $disableMainLang || $key != $mainLang ? $key : false)}}" @class(['nav-link', 'active' => $key == $currentLang])>{{$value['short_name']}}</a>
            </li>
        @endforeach
    </ul>
@endif
