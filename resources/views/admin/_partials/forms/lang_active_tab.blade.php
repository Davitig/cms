@if (language()->containsMany())
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#item{{$lang = language()->active()}}" data-toggle="tab">
                <img src="{{ asset('assets/libs/images/flags/'.$lang.'.png') }}" width="23" height="13" alt="{{$lang}}">
                <span>{{strtoupper(language()->getActive('full_name'))}}</span>
            </a>
        </li>
    </ul>
@endif
