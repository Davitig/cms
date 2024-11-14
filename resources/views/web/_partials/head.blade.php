<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{$current->meta_desc}}">
    <meta name="robots" content="index, follow">
    <meta property="og:url" content="{{$url = web_url($current->slug, [], true)}}">
    <meta property="og:type" content="Website">
    <meta property="og:site_name" content="{{$trans->get('title')}}">
    <meta property="og:title" content="{{$current->meta_title ?: $current->title}}">
    <meta property="og:description" content="{{$current->meta_desc}}">
    <meta property="og:image" content="{{$current->image}}">
    <title>{{$current->title}}</title>
    <link rel="canonical" href="{{$url}}">
    @if (is_multilanguage())
        @foreach (languages() as $key => $value)
            <link rel="alternate" hreflang="{{$key}}" href="{{web_url($current->slug, [], $key)}}">
        @endforeach
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" media="all" href="{{asset('assets/css/main.css')}}">
    <script src="{{asset('assets/libs/js/jquery-1.11.3.min.js')}}"></script>
</head>
