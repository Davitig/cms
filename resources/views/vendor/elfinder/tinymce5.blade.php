<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>elFinder 2.1</title>
    <link rel="stylesheet" href="{{ asset('assets/default/libs/jquery/jquery-ui-1.14.1/jquery-ui.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset($dir.'/css/elfinder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset($dir.'/css/theme.css') }}">
    <script src="{{ asset('assets/default/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/default/libs/jquery/jquery-ui-1.14.1/jquery-ui.min.js') }}"></script>
    <script src="{{ asset($dir.'/js/elfinder.min.js') }}"></script>
    @if ($locale)
        <!-- elFinder translation (OPTIONAL) -->
        <script src="{{ asset($dir."/js/i18n/elfinder.$locale.js") }}"></script>
    @endif
    <script type="text/javascript">
        let FileBrowserDialogue = {
            init: function() {
                // Here goes your code for setting your custom things onLoad.
            },
            mySubmit: function (file) {
                window.parent.postMessage({
                    mceAction: 'fileSelected',
                    data: {
                        file: file
                    }
                }, '*');
            }
        };
        $(function () {
            $('#elfinder').elfinder({
                // set your elFinder options here
                @if ($locale)
                lang: '{{ $locale }}', // locale
                @endif
                customData: {
                    _token: '{{ csrf_token() }}'
                },
                url: '{{ cms_route('fileManager.connector', ['hide_disks' => 1]) }}',  // connector URL
                soundPath: '{{ asset($dir.'/sounds') }}',
                getFileCallback: function(file) { // editor callback
                    FileBrowserDialogue.mySubmit(file); // pass a selected file path to TinyMCE
                },
                // width: 880,
                height: 580,
                resizable: false
            }).elfinder('instance');
        });
    </script>
</head>
<body>
<div id="elfinder"></div>
</body>
</html>
