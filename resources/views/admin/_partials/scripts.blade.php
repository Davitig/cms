<!-- Basic Scripts -->
<script src="{{ asset('assets/libs/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/libs/js/TweenMax.min.js') }}"></script>
<script src="{{ asset('assets/libs/js/resizeable.js') }}"></script>
<script src="{{ asset('assets/libs/js/joinable.js') }}"></script>
<script src="{{ asset('assets/libs/js/xenon-api.js') }}"></script>
<script src="{{ asset('assets/libs/js/xenon-toggles.js') }}"></script>

<!-- datatables scripts -->
<script src="{{ asset('assets/libs/js/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/js/datatables/dataTables.bootstrap.js') }}"></script>

<!-- fancybox scripts -->
<script src="{{ asset('assets/libs/js/fancybox/jquery.fancybox.pack.js') }}"></script>

<!-- stacktable scripts -->
<script src="{{ asset('assets/libs/js/stacktable/stacktable.js') }}"></script>

<!-- toast notifications -->
<script src="{{ asset('assets/libs/js/toastr/toastr.min.js') }}"></script>

<!-- custom scripts -->
<script src="{{ asset('assets/libs/js/xenon-custom.js') }}"></script>
<script src="{{ asset('assets/libs/js/custom.js') }}"></script>
<script type="text/javascript">
    $(function() {
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

        // Initialize stacktable
        $('.stacktable').stacktable();

        // toast notification options
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-{{$cmsSettings->get('alert_position', 'top-right')}}",
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "4000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        let notifications = $('.notifications');
        $(notifications).on('click', '.external button', function(e) {
            e.preventDefault();
        });
        $(notifications).on('click', '.external .external-btn', function(e) {
            e.preventDefault();
            let target = $(this);
            target.removeClass('external-btn');
            let form = $(this).closest('form');
            $.post(form.attr('action'), form.serialize(), function() {
                $('.sm-date', notifications).html('{{date('d F Y')}}');
                $('.sm-time', notifications).html('{{date('H:i')}}');
                $('.sm-status', form).html('Update now!');
                toastr['success']('Task has been completed successfully');
            }, 'json').done(function() {
                target.addClass('external-btn');
            }).fail(function(xhr) {
                alert(xhr.responseText);
            });
        });
        @if (session()->has('alert'))
            toastr["{{session('alert.result')}}"]("{{session('alert.message')}}");
        @endif
        @if (! session()->has('includeLockscreen') && $cmsSettings->get('lockscreen'))
        lockscreen(
            '{{$cmsSettings->get('lockscreen')}}', '{{cms_route('lockscreen.lock')}}', '{{csrf_token()}}'
        );
        @endif
    });
</script>
@include('admin._scripts.tinymce')
