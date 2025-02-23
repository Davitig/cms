<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>elFinder 2.1</title>
    <link rel="stylesheet" href="{{ asset('assets/libs/js/jquery-ui/jquery-ui.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset($dir.'/css/elfinder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset($dir.'/css/theme.css') }}">
    <script src="{{ asset('assets/libs/js/jquery-1.11.3.min.js') }}"></script>
    <script src="{{ asset('assets/libs/js/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset($dir.'/js/elfinder.min.js') }}"></script>
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
        $(function() {
            $('#elfinder').elfinder({
                // set your elFinder options here
                customData: {
                    _token: '{{ csrf_token() }}'
                },
                url: '{{ cms_route('filemanager.connector', ['hide_disks' => 1]) }}',  // connector URL
                getFileCallback: function(file) { // editor callback
                    FileBrowserDialogue.mySubmit(file); // pass selected file path to TinyMCE
                },
                // width: 880,
                height: 580,
                resizable: false
            }).elfinder('instance');
        });
    </script>
    @include('vendor.elfinder._head')
</head>
<body>
<div id="elfinder"></div>
</body>
</html>
