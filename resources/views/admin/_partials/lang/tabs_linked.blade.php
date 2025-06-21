@if (language()->containsMany())
    @php($activeLang = request('lang', language()->active()))
    <ul class="nav nav-tabs" role="tablist">
        @foreach (language()->all() as $language)
            <li class="nav-item">
                <a href="{{ cms_route('translations.edit', [$current->id], $language->language) }}"
                   class="nav-link{{ $language->language == $activeLang ? ' active' : '' }}"
                   role="tab"{!! $language->language == $activeLang ? ' aria-selected="true"' : '' !!}>
                    <span class="d-none d-sm-inline-flex align-items-center">
                        <img src="{{ asset('assets/default/img/flags/' . $language->language . '.png') }}" width="20" height="13" class="me-2" alt="{{ $language->full_name }}">
                        {{ $language->full_name }}
                    </span>
                </a>
            </li>
        @endforeach
    </ul>
@endif
