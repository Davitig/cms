@extends('web.app')
@section('content')
    @include('web._partials.breadcrumb')
    <!-- #breadcrumb -->
    <div class="container">
        <header class="heading">
            <h1>{{$current->title}}</h1>
            {!!$current->description!!}
        </header>
        <!-- .jumbotron -->
        <div id="items" class="row">
            @foreach ($items as $item)
                <article class="col-sm-3">
                    @if ($item->image)
                        <div class="img">
                            <a href="{{web_url([$current->url_path, $item->slug])}}">
                                <img src="{{$item->image}}" class="img-responsive" alt="{{$item->title}}">
                            </a>
                        </div>
                        <!-- .img -->
                    @endif
                    <div class="content clearfix">
                        <header class="title">
                            <h2>
                                <a href="{{web_url([$current->url_path, $item->slug])}}">{{$item->title}}</a>
                            </h2>
                        </header>
                        <!-- .title -->
                        <div class="desc">
                            {!! $item->description !!}
                        </div>
                        <!-- .desc -->
                    </div>
                    <!-- .content -->
                </article>
                <!-- .col-sm-3 -->
            @endforeach
        </div>
        <!-- #items -->
        @if ($items->lastPage() > 1)
            <div id="pager">
                {!! $items->links() !!}
            </div>
            <!-- #pager -->
        @endif
    </div>
    <!-- .container -->
@endsection
