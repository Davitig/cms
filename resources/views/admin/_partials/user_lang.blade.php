<li class="dropdown hover-line language-switcher">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img src="{{ asset('assets/libs/images/flags/'.language()->active().'.png') }}" width="30" height="20"
             alt="{{language()->getActive('full_name')}}">
    </a>
    <ul class="dropdown-menu languages">
        @php
            $queryString = request()->getQueryString();
            $queryString = $queryString ? '?' . $queryString : '';
        @endphp
        @foreach (language()->all() as $key => $value)
            <li data-id="{{$value['id']}}">
                <a href="{{url($value['path']) . $queryString}}">
                    <img src="{{ asset('assets/libs/images/flags/'.$key.'.png') }}" width="30" height="20" alt="{{$value['full_name']}}">
                    {{ $value['full_name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</li>
