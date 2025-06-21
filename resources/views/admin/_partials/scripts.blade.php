<!-- Core JS -->
<!-- build:js assets/vendor/js/theme.js -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->
<!-- Vendors JS -->
<script src="{{ asset('assets/vendor/libs/notyf/notyf.js') }}"></script>
<!-- Main JS -->
<script src="{{ asset('assets/js/main.js') }}"></script>
<!-- Page JS -->
<!-- Custom Scripts -->
<script type="text/javascript">
    $(function() {
        @if (session()->has('alert'))
        // Create an instance of Notyf
        var notyf = new Notyf({
            duration: 2000,
            position: {
                x: 'right',
                y: 'top',
            }
        });
        // Display an error notification
        notyf.{{session('alert.result')}}('{{session('alert.message')}}');
        @endif

        // Fancybox click event handler
        $(document).on('click', '.popup', function(e) {
            e.preventDefault();
            let id = $(this).data('browse');
            $.fancybox({
                width    : 900,
                height   : 600,
                type     : 'iframe',
                href     : '{{ cms_url('filemanager/popup') }}/' + id + '?iframe=1',
                autoSize : false,
                helpers: {
                    overlay: {
                        locked: false
                    }
                }
            });
        });
    });
</script>
@include('admin._scripts.tinymce')
