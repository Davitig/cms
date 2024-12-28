@if (is_multilanguage())
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#item{{$lang = language()}}" data-toggle="tab">
                <img src="{{ asset('assets/libs/images/flags/'.$lang.'.png') }}" width="23" height="13" alt="{{$lang}}">
                <span>{{strtoupper(language(true, 'full_name'))}}</span>
            </a>
        </li>
    </ul>
@endif
