<!-- Core JS -->
<script src="{{ asset('assets/default/libs/jquery/jquery-3.7.1.min.js') }}"></script>
<!-- build:js assets/vendor/js/theme.js -->
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->
<script src="{{ asset('assets/vendor/libs/notyf/notyf.js') }}"></script>
<!-- Main JS -->
<script src="{{ asset('assets/js/main.js') }}"></script>
<!-- Default -->
<script src="{{ asset('assets/default/libs/fancybox-6.0/fancybox.umd.js') }}"></script>
<script src="{{ asset('assets/default/js/custom.js') }}"></script>
<script type="text/javascript">
    $(function () {
        @if (session()->has('alert'))
        notyf('{{session('alert.message')}}', '{{session('alert.result') ? 'success' : 'error'}}');
        @endif

        // Fancybox click event handler
        $(document).on('click', '.file-manager-popup', function (e) {
            e.preventDefault();
            let id = $(this).data('browse');
            Fancybox.show([
                {
                    src: '{{ cms_url('file-manager/popup') }}/' + id + '?iframe=1',
                    width: 1000,
                    height: 600,
                    type: 'iframe'
                }
            ], {
                mainClass: 'has-gmap' // add this class to remove padding on content
            });
        });
    });
</script>
@include('admin.-scripts.tinymce')
