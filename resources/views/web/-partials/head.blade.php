<head>
    <meta charset="utf-8">
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
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="canonical" href="{{$url}}">
    @if (language()->containsMany())
        @foreach (language()->all() as $key => $value)
            <link rel="alternate" hreflang="{{$key}}" href="{{web_url($current->slug, [], $key)}}">
        @endforeach
    @endif
    <link rel="stylesheet" href="{{ asset('assets/default/libs/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <script src="{{ asset('assets/default/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    @stack('head')
</head>
