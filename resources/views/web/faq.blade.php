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
    <article id="item" class="jumbotron">
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
        </div>
        <!-- .content -->
    </article>
    <!-- #item -->
    <div id="items" class="row">
        @foreach ($items as $item)
            <article class="col-sm-4">
                <div class="content clearfix">
                    <header class="title">
                        <h2>{{ $item->title}}</h2>
                    </header>
                    <!-- .title -->
                    <div class="desc">
                        {!! $item->description !!}
                    </div>
                    <!-- .desc -->
                </div>
                <!-- .content -->
            </article>
            <!-- .col-sm-4 -->
        @endforeach
    </div>
</div>
<!-- .container -->
@endsection
