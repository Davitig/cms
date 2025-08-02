@php($langName = $current->language ? language()->getBy($current->language, 'full_name') : null)
<div @class(array_merge(['alert alert-outline-warning alert-lang-data'], $current->language ? [$current->language] : [])) role="alert">
    Resource was created without {!! $langName ? ' <span class="text-decoration-underline">' . strtolower($langName) . '</span>' : '' !!} language data
</div>
@if ($langName)
    @pushonce('body.bottom')
        <script type="text/javascript">
            $(function () {
                $('form[data-ajax-form="1"][data-lang]').on('ajaxFormDone', function () {
                    $('.alert.alert-lang-data.' + $(this).data('lang')).remove();
                });
            });
        </script>
    @endpushonce
@endif
