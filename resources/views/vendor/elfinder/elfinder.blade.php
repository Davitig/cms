<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>elFinder 2.1</title>
    <link rel="stylesheet" href="{{ asset('assets/libs/js/jquery-ui/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset($dir.'/css/elfinder.min.css') }}">
    <link rel="stylesheet" href="{{ asset($dir.'/css/theme.css') }}">
    <script src="{{ asset('assets/libs/js/jquery-1.11.3.min.js') }}"></script>
    <script src="{{ asset('assets/libs/js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset($dir.'/js/elfinder.min.js') }}"></script>
    <script type="text/javascript">
        // Documentation for client options:
        // https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
        $(function() {
            // Initialize elFinder
            $('#elfinder').elfinder({
                customData: {
                    _token: '{{ csrf_token() }}'
                },
                url: '{{ cms_route("filemanager.connector") }}',  // connector URL
                height: 600,
                getFileCallback: function(file, instance) {
                    if (file.mime.indexOf('image/')) {
                        instance.exec('open');
                    } else {
                        instance.exec('quicklook');
                    }
                }
            });
        });
    </script>
    @include('vendor.elfinder._head')
</head>
<body>
<div id="elfinder"></div>
</body>
</html>
