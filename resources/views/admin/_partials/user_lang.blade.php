<li class="dropdown hover-line language-switcher">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img src="{{ asset('assets/libs/images/flags/'.language().'.png') }}" width="30" height="20" alt="{{language(true, 'full_name')}}">
    </a>
    <ul class="dropdown-menu languages">
        @php
            $queryString = request()->getQueryString();
            $queryString = $queryString ? '?' . $queryString : '';
        @endphp
        @foreach (languages() as $key => $value)
            <li data-id="{{$value['id']}}">
                <a href="{{$value['url'] . $queryString}}">
                    <img src="{{ asset('assets/libs/images/flags/'.$key.'.png') }}" width="30" height="20" alt="{{$value['full_name']}}">
                    {{ $value['full_name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</li>
