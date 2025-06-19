<script type="text/javascript">
    let currentLang = '{{language()->active()}}';
    let formSelector = $('#form-modal form');

    formSelector.on('ajaxFormSuccess', function () {
        $(this).find('[name="file"]').trigger('fileSet');
        let lang = $(this).data('lang');
        if (lang !== currentLang) {
            return;
        }
        let title = $('[name="title"]', this).val();
        let file = $('[name="file"]', this).val();
        let visible = $('[name="visible"]', this).prop('checked');

        let item = $('.gallery-env #item{{$current->id}}');
        $('.title', item).text(title);
        $('.thumb img', item).attr('src', getFileImage(file).file);

        let icon = (visible ? 'fa fa-eye' : 'fa fa-eye-slash');
        $('.visibility i', item).attr('class', icon);
    });

    formSelector.find('[name="file"]').on('fileSet', function () {
        let fileId = $(this).attr('id');
        let fileValue = $(this).val();
        let result = getFileImage(fileValue);

        let photoSelector = $('#form-modal img.' + fileId);
        photoSelector.removeClass('not-photo');
        if (!result.isPhoto) {
            photoSelector.addClass('not-photo');
        }
        photoSelector.attr('src', result.file);
    });
</script>
@include('admin._scripts.get_file_func')
