<script type="text/javascript">
    $('#file-modal form').on('ajaxFormSuccess', function () {
        if ($(this).data('lang') !== '{{language()->active()}}') {
            return;
        }

        let item = $('#sortable #item{{ $current->id }}');
        $('.item-title', item).text($('[name="title"]', this).val());
        $('.item-img', item).attr('src', getImageOrExtImage($('[name="file"]', this).val()));

        let checked = $('[name="visible"]', this).prop('checked');
        $('.visibility i', item).addClass(checked ? 'fa-toggle-on' : 'fa-toggle-off')
            .removeClass(checked ? 'fa-toggle-off' : 'fa-toggle-on');
    });
</script>
