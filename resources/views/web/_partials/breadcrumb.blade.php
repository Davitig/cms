<nav>
    <ul class="breadcrumb">
        <li><a href="{{$url = web_url('/')}}">{{trans('general.home')}}</a></li>
        @if ($breadcrumb = app_instance('breadcrumb'))
            @php($prevSlug = null)
            @foreach ($breadcrumb as $item)
                <li{!! $loop->last ? ' class="active"' : '' !!}>
                    @if (! $loop->last)
                        <a href="{{web_url([$prevSlug, $item->slug])}}">
                            @endif
                            {{$item->short_title ?: $item->title}}
                            @if (! $loop->last)
                        </a>
                    @endif
                </li>
                @php($prevSlug = $item->slug)
            @endforeach
        @endif
    </ul>
</nav>
