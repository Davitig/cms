@push('body.bottom')
    <script type="text/javascript">
        var flagSelector = $('#lang-img');
        $('form.ajax-form #language').on('keyup', function (data) {
            var value = $(data.target).val();
            if (value.length === 2) {
                flagSelector.attr('src', '{{ asset('/') }}/assets/libs/images/flags/'+value+'.png');
            }
        });
    </script>
    <script src="{{ asset('assets/libs/js/inputmask/jquery.inputmask.bundle.js') }}"></script>
@endpush
