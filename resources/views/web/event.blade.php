@extends('web.app')
@section('content')
    <div id="breadcrumb">
        <div class="container">
            @include('web._partials.breadcrumb')
        </div>
        <!-- .container -->
    </div>
    <!-- #breadcrumb -->
    <div class="container">
        <article id="item" class="jumbotron clearfix">
            @if ($current->image)
                <div class="img">
                    <img src="{{$current->image}}" class="img-responsive" alt="{{$current->title}}">
                </div>
                <!-- .img -->
            @endif
            <div class="content">
                <header class="heading">
                    <h1>{{$current->title}}</h1>
                </header>
                <!-- .heading -->
                <div class="text">
                    {!!$current->content!!}
                </div>
                <!-- .text -->
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
                                            <img src="{{$item->file}}" width="270" height="180" alt="{{$item->title}}">
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
            <!-- .content -->
        </article>
        <!-- #item -->
    </div>
    <!-- .container -->
@endsection
