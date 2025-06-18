<ul class="navbar-nav me-auto">
    <li class="nav-item">
        <a href="{{web_url('/')}}" class="nav-link">{{trans('general.home')}}</a>
    </li>
    @foreach ($pageItems as $item)
        <li class="nav-item{{ $item->sub_items ? ' dropdown' : '' }}">
            <a href="{{web_url($item->slug)}}" class="nav-link{{ $current->slug == $item->slug ? ' active' : '' }}{{ $item->sub_items ? ' dropdown-toggle' : '' }}"{!! $current->slug == $item->slug ? ' aria-current="page"' : '' !!}{!! $item->sub_items ? ' role="button" data-bs-toggle="dropdown" aria-expanded="false"' : '' !!}>
                {{$item->short_title}}
            </a>
            @include('web._partials.sub_pages')
        </li>
    @endforeach
</ul>
