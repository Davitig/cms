<ul class="navbar-nav me-auto">
    <li class="nav-item">
        <a href="{{web_url('/')}}" class="nav-link">{{trans('general.home')}}</a>
    </li>
    @foreach ($pageItems as $item)
        @php($hasSubItems = ($item->sub_items && $item->sub_items->isNotEmpty()))
        <li class="nav-item{{ $item->sub_items ? ' dropdown' : '' }}">
            <a href="{{web_url($item->slug)}}" class="nav-link{{ $current->slug == $item->slug ? ' active' : '' }}{{ $hasSubItems ? ' dropdown-toggle' : '' }}"{!! $current->slug == $item->slug ? ' aria-current="page"' : '' !!}{!! $hasSubItems ? ' role="button" data-bs-toggle="dropdown" aria-expanded="false"' : '' !!}>
                {{$item->short_title}}
            </a>
            @include('web.-partials.sub_pages')
        </li>
    @endforeach
</ul>
