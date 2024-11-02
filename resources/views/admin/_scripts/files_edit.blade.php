<script type="text/javascript">
    var currentLang = '{{language()}}';
    var formSelector = $('#form-modal .{{$cmsSettings->get('ajax_form')}}');

    formSelector.on('ajaxFormSuccess', function () {
        $(this).find('[name="file"]').trigger('fileSet');
        var lang = $(this).data('lang');
        if (lang !== currentLang) {
            return;
        }
        var title = $('[name="title"]', this).val();
        var file = $('[name="file"]', this).val();
        var visible = $('[name="visible"]', this).prop('checked');

        var item = $('.gallery-env #item{{$current->id}}');
        $('.title', item).text(title);
        $('.thumb img', item).attr('src', getFileImage(file).file);

        var icon = (visible ? 'fa fa-eye' : 'fa fa-eye-slash');
        $('.visibility i', item).attr('class', icon);
    });

    formSelector.find('[name="file"]').on('fileSet', function () {
        var fileId = $(this).attr('id');
        var fileValue = $(this).val();
        var result = getFileImage(fileValue);

        var photoSelector = $('#form-modal img.' + fileId);
        photoSelector.removeClass('not-photo');
        if (!result.isPhoto) {
            photoSelector.addClass('not-photo');
        }
        photoSelector.attr('src', result.file);
    });
</script>
@include('admin._scripts.get_file_func')
