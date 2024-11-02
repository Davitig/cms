@push('body.bottom')
    <script type="text/javascript">
        var flagSelector = $('#lang-img');
        var sampleLangSelector = $('.sample-lang');
        $('input#language').on('keyup', function (data) {
            var value = $(data.target).val();
            if (value.length === 2) {
                flagSelector.attr('src', '{{ asset('/assets/libs/images/flags/') }}/'+value+'.png');
                sampleLangSelector.text(value);
            }
        });
    </script>
@endpush
