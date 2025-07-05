@extends('web.app')
@section('content')
    @include('web.-partials.breadcrumb')
    <div class="container">
        <article id="item">
            @if ($current->image)
                <div class="img">
                    <img src="{{$current->image}}" class="img-responsive" width="500" height="300" alt="{{$current->title}}">
                </div>
                <!-- .img -->
            @endif
            <div class="content">
                <header class="heading">
                    <h1>{{$current->title}}</h1>
                </header>
                <!-- .heading -->
                <div class="price">
                    <span>Price:</span>
                    <span>{{$current->price}}</span>
                </div>
                <!-- .price -->
                <div class="quantity">
                    <span>Quantity:</span>
                    <span>{{$current->quantity}}</span>
                </div>
                <!-- .quantity -->
                <div class="text">
                    {!!$current->content!!}
                </div>
                <!-- .text -->
                <div class="row">
                    @if ($files->isNotEmpty())
                        <div class="row">
                            @if ($files->get('mixed')->isNotEmpty())
                                <div class="attached files">
                                    <ul class="list-unstyled">
                                        @foreach ($files['mixed'] as $item)
                                            <li>
                                                <a href="{{$item->file}}" target="_blank">{{$item->title}}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!-- .files -->
                            @endif
                            @if ($files->get('images')->isNotEmpty())
                                <div class="attached images">
                                    @foreach ($files['images'] as $item)
                                        <div class="col-md-3 item">
                                            <a href="{{$item->file}}" title="{{$item->title}}" target="_blank">
                                                <img src="{{$item->file}}" width="140" height="100" alt="{{$item->title}}">
                                            </a>
                                        </div>
                                        <!-- .col-md-3 -->
                                    @endforeach
                                </div>
                                <!-- .images -->
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <!-- .content -->
        </article>
        <!-- #item -->
    </div>
    <!-- .container -->
@endsection
