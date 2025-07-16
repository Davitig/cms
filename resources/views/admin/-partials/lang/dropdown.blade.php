@if ($userRouteAccess('languages.index'))
<!-- Language -->
<li class="language-switcher nav-item dropdown">
    <a href="javascript:void(0);"
       class="nav-link dropdown-toggle hide-arrow btn btn-icon btn-text-secondary rounded-pill"
       data-bs-toggle="dropdown">
        <img src="{{ asset('assets/default/img/flags/'.language()->active().'.png') }}" width="25" height="18"
             alt="{{language()->getActive('full_name')}}">
    </a>
    <ul class="dropdown-languages dropdown-menu dropdown-menu-end">
        @php
            $queryString = request()->getQueryString();
            $queryString = $queryString ? '?' . $queryString : '';
        @endphp
        @foreach (language()->all() as $key => $value)
            <li data-id="{{$value['id']}}">
                <a href="{{url($value['path']) . $queryString}}" class="dropdown-item">
                    <img src="{{ asset('assets/default/img/flags/'.$key.'.png') }}" width="25" height="18" class="me-2" alt="{{$value['full_name']}}">
                    <span>{{ $value['full_name'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</li>
<!--/ Language -->
@endif
