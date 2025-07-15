<ul class="navbar-nav me-auto">
    <li class="nav-item">
        <a href="{{web_url('/')}}" class="nav-link">{{trans('general.home')}}</a>
    </li>
    @php($currentUrl = url()->current())
    @foreach ($pageItems as $item)
        @php($hasSubItems = ($item->sub_items && $item->sub_items->isNotEmpty()))
        <li class="nav-item{{ $item->sub_items ? ' dropdown' : '' }}">
            <a href="{{$url = web_url($item->slug)}}" class="nav-link{{ $url == $currentUrl ? ' active' : '' }}{{ $hasSubItems ? ' dropdown-toggle' : '' }}"{!! $url == $currentUrl ? ' aria-current="page"' : '' !!}{!! $hasSubItems ? ' role="button" data-bs-toggle="dropdown" aria-expanded="false"' : '' !!}>
                {{$item->short_title}}
            </a>
            @include('web.-partials.sub_pages', compact('currentUrl'))
        </li>
    @endforeach
</ul>
