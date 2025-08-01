@extends('web.app')
@section('content')
    @include('web.-partials.breadcrumb')
    <div class="container">
        <div id="feedback">
            @if ($current->image)
                <div class="img">
                    <img src="{{$current->image}}" class="img-responsive" width="500" height="300" alt="{{$current->title}}">
                </div>
                <!-- .img -->
            @endif
            <header class="heading">
                <h1>{{$current->title}}</h1>
            </header>
            <!-- .heading -->
            @if ($current->content)
                <div class="text">
                    {!!$current->content!!}
                </div>
                <!-- .text -->
            @endif
            @if ($alert = session('alert'))
                <div class="alert alert-{{$alert['result'] ? 'success' : 'danger'}}">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{$alert['message']}}
                </div>
            @endif
            <div id="feedback">
                <form action="{{url()->current()}}" method="POST">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="pt-2">
                                {{ html()->text('name')->class('form-control')
                                    ->placeholder($trans->get('name', 'Name'))
                                    ->data('trans', 'name')
                                    ->data('trans-attr', 'placeholder') }}
                                @if ($error = $errors->first('name'))
                                    <div class="text-danger">{{$error}}</div>
                                @endif
                            </div>
                            <div class="pt-2">
                                {{ html()->text('email')->class('form-control')
                                    ->placeholder($trans->get('email', 'Email'))
                                    ->data('trans', 'email')
                                    ->data('trans-attr', 'placeholder') }}
                                @if ($error = $errors->first('email'))
                                    <div class="text-danger">{{$error}}</div>
                                @endif
                            </div>
                            <div class="pt-2">
                                {{ html()->text('phone')->class('form-control')
                                    ->placeholder($trans->get('phone', 'Phone'))
                                    ->data('trans', 'phone')
                                    ->data('trans-attr', 'placeholder') }}
                                @if ($error = $errors->first('phone'))
                                    <div class="text-danger">{{$error}}</div>
                                @endif
                            </div>
                            <div class="pt-2">
                                <input type="text" name="captcha" autocomplete="off" class="form-control" placeholder="{{$trans->get('enter_code', 'Enter the code')}}" data-trans="enter_code" data-trans-attr="placeholder">
                                <div class="pt-2">
                                <img src="{{ captcha_src('flat') }}" height="40" class="captcha-img" alt="captcha">
                                <a href="#" class="captcha-reload">
                                    <img src="{{asset('assets/default/img/reload.png')}}" width="20" height="20" alt="reload">
                                </a>
                                @error('captcha')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                                </div>
                            </div>
                        </div>
                        <!-- .col-md-6 -->
                        <div class="col-md-6">
                            <div class="pt-2">
                                {{ html()->textarea('text')->class('form-control')
                                    ->placeholder($trans->get('text', 'Enter a text'))
                                    ->data('trans', 'text')
                                    ->rows(8) }}
                                @if ($error = $errors->first('text'))
                                    <span class="text-danger">{{$error}}</span>
                                @endif
                            </div>
                        </div>
                        <!-- .col-md-6 -->
                    </div>
                    <!-- .row -->
                    <div class="pt-2">
                        <button type="submit" class="btn btn-primary" data-trans="send">{{$trans->get('send', 'Send')}}</button>
                    </div>
                </form>
            </div>
            <!-- #feedback -->
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
        <!-- #feedback -->
    </div>
    <!-- .container -->
@endsection
@include('web.-scripts.captcha')
