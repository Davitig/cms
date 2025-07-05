@extends('web.app')
@section('content')
    @include('web.-partials.breadcrumb')
    <div class="container">
        <form action="{{url()->current()}}" method="GET">
            <div class="input-group">
                <input type="text" name="q" class="form-control" value="{{request('q')}}">
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-primary">Go!</button>
                </div>
            </div>
            <!-- .input-group -->
        </form>
        <article id="item">
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
    </div>
    <!-- .container -->
@endsection
