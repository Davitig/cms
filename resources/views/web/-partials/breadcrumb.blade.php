<div id="breadcrumb" class="pt-2">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{$url = web_url('/')}}">{{trans('general.home')}}</a></li>
                @if ($breadcrumb = app('breadcrumb'))
                    @php($prevSlug = null)
                    @foreach ($breadcrumb as $item)
                        <li class="breadcrumb-item{{ $loop->last ? ' active' : '' }}">
                            @if (! $loop->last)
                                <a href="{{web_url([$prevSlug, $item->slug])}}">
                                    @endif
                                    {{$item->short_title ?? $item->title}}
                                    @if (! $loop->last)
                                </a>
                            @endif
                        </li>
                        @php($prevSlug .= '/' . $item->slug)
                    @endforeach
                @endif
            </ol>
        </nav>
    </div>
    <!-- .container -->
</div>
<!-- #breadcrumb -->
