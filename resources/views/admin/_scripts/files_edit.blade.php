<script type="text/javascript">
    $('#file-modal form').on('ajaxFormSuccess', function () {
        if ($(this).data('lang') !== '{{language()->active()}}') {
            return;
        }

        let item = $('#sortable #item{{ $current->id }}');
        $('.item-title', item).text($('[name="title"]', this).val());
        $('.item-img', item).attr('src', getImageOrExtImage($('[name="file"]', this).val()));

        $('.visibility i', item).attr(
            'class', $('[name="visible"]', this).prop('checked') ? 'fa fa-eye' : 'fa fa-eye-slash'
        );
    });
</script>
