@push('body.bottom')
    <script type="text/javascript">
        $(function () {
            let flagSelector = $('#lang-img');
            let sampleLangSelector = $('.sample-lang');
            $('input#language_inp').on('keyup', function (data) {
                let value = $(data.target).val();
                if (value.length === 2) {
                    flagSelector.attr('src', '{{ asset('/assets/default/img/flags/') }}/'+value+'.png');
                    sampleLangSelector.text(value);
                }
            });
            @if (language()->getSettings('down_without_language'))
            let langVisibleSelector = $('.lang-visibility-alert');
            if (langVisibleSelector.data('count') <= 1) {
                $('form#lang-form[data-ajax-form="1"]').on('ajaxFormDone', function (e, res) {
                    if (res?.data?.visible) {
                        langVisibleSelector.addClass('d-none');
                    } else {
                        langVisibleSelector.removeClass('d-none');
                    }
                });
            }
            @endif
        });
    </script>
@endpush
