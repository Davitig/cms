<script type="text/javascript">
    var sort = '{{request('sort', 'desc')}}';
    var currentPage = '{{request('page_val', '1')}}';
    var creationPage = sort === 'desc' ? '1' : '{{request('lastPage', '1')}}';
    var formSelector = $('#form-modal form');

    formSelector.on('ajaxFormSuccess', function (e, data) {
        // alert toastr message
        toastr[data.result](data.message);

        var imageContainer = '.gallery-env .album-images';
        var insert = $(imageContainer).data('insert');
        insert = Function("$('" + imageContainer + "')." + insert + "('" + data.view + "');");
        insert();

        cbr_replace();

        if (currentPage !== creationPage) {
            window.location.href = window.location.href + '?page=' + creationPage;
        } else {
            $('#form-modal').find('[data-dismiss]').trigger('click');
        }
    });

    formSelector.find('[name="file"]').on('fileSet', function () {
        var fileValue = $(this).val();
        var result = getFileImage(fileValue);

        var photoSelector = $('.modal-gallery-image img');
        photoSelector.removeClass('not-photo');
        if (!result.isPhoto) {
            photoSelector.addClass('not-photo');
        }
        photoSelector.attr('src', result.file);
    });
</script>
@include('admin._scripts.get_file_func')
