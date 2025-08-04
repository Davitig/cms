@php
    $currentUrl = url()->current();
    $mainLang = language()->main();
    $disableMainLang = language()->getSettings('disable_main_language_from_url');
@endphp
<ul class="navbar-nav me-auto">
    <li class="nav-item">
        <a href="{{web_route('home', [], ! $disableMainLang ? null : false)}}" class="nav-link">{{trans('general.home')}}</a>
    </li>
    @foreach ($pageItems as $item)
        @php($hasSubItems = ($item->sub_items && $item->sub_items->isNotEmpty()))
        <li @class(['nav-item', 'dropdown' => $hasSubItems])>
            <a href="{{$url = web_url($item->slug, [], ! $disableMainLang || $item->language != $mainLang ? null : false)}}" @class(['nav-link active', 'dropdown-toggle' => $hasSubItems]){!! $url == $currentUrl ? ' aria-current="page"' : '' !!}{!! $hasSubItems ? ' role="button" data-bs-toggle="dropdown" aria-expanded="false"' : '' !!}>
                {{$item->short_title ?: $item->slug}}
            </a>
            @include('web.-partials.sub_pages', compact('currentUrl', 'mainLang', 'disableMainLang'))
        </li>
    @endforeach
</ul>
