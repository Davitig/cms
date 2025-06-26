@push('body.bottom')
    <script type="text/javascript">
        let flagSelector = $('#lang-img');
        let sampleLangSelector = $('.sample-lang');
        $('input#language_inp').on('keyup', function (data) {
            let value = $(data.target).val();
            if (value.length === 2) {
                flagSelector.attr('src', '{{ asset('/assets/default/img/flags/') }}/'+value+'.png');
                sampleLangSelector.text(value);
            }
        });
    </script>
@endpush
