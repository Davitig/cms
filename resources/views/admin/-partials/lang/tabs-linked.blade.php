@if (language()->containsMany())
    @php
        $langParam = language()->queryStringOrActive();
        $langParamKey = language()->queryStringKey();
    @endphp
    <div class="nav-align-top mb-4">
        <ul class="nav nav-tabs flex-wrap" role="tablist">
            @foreach (language()->all() as $language)
                <li class="nav-item">
                    <a href="{{ cms_route($routeName, $routeParams + [$langParamKey => $language->language]) }}"
                       class="nav-link{{ $language->language == $langParam ? ' active' : '' }}"
                       role="tab"{!! $language->language == $langParam ? ' aria-selected="true"' : '' !!}>
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <img src="{{ asset('assets/default/img/flags/' . $language->language . '.png') }}" width="20" height="13" class="me-2" alt="{{ $language->full_name }}">
                        {{ $language->full_name }}
                    </span>
                        <img src="{{ asset('assets/default/img/flags/' . $language->language . '.png') }}" width="20" height="13" class="d-sm-none" alt="{{ $language->short_name }}">
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
