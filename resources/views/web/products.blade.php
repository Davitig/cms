@extends('web.app')
@section('content')
    @include('web.-partials.breadcrumb')
    <div class="container">
        <header class="heading">
            <h1>{{$current->title}}</h1>
            {!!$current->description!!}
        </header>
        <!-- .heading -->
        <div id="items" class="row">
            @foreach ($items as $item)
                <article class="col-sm-3">
                    @if ($item->image)
                        <div class="img">
                            <a href="{{web_url([$current->url_path, $item->slug])}}">
                                <img src="{{$item->image}}" class="img-responsive" width="150" height="100" alt="{{$item->title}}">
                            </a>
                        </div>
                        <!-- .img -->
                    @endif
                    <div class="content clearfix">
                        <header class="title">
                            <h2>
                                <a href="{{web_url([$current->url_path, $item->slug])}}">{{$item->title ?: $item->slug}}</a>
                            </h2>
                        </header>
                        <!-- .title -->
                        <div class="price">
                            <span>Price:</span>
                            <span>{{$item->price}}</span>
                        </div>
                        <!-- .price -->
                        <div class="quantity">
                            <span>Quantity:</span>
                            <span>{{$item->quantity}}</span>
                        </div>
                        <!-- .quantity -->
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
    </div>
    <!-- .container -->
@endsection
