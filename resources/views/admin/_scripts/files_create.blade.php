<script type="text/javascript">
    let sort = '{{request('sort', 'desc')}}';
    let currentPage = {{(int) request('currentPage', 1)}};
    let creationPage = sort === 'desc' ? 1 : {{(int) request('lastPage', 1)}};
    let formSelector = $('#form-modal form');

    formSelector.on('ajaxFormSuccess', function (e, data) {
        let imageContainer = '.gallery-env .album-images';
        let insert = $(imageContainer).data('insert');
        insert = Function("$('" + imageContainer + "')." + insert + "('" + data.view + "');");
        insert();

        cbr_replace();

        if (currentPage !== creationPage) {
            window.location.href = window.location.href.replace(location.search, '')
                + '?page=' + creationPage;
        } else {
            $('#form-modal').find('[data-dismiss]').trigger('click');
        }
    });

    formSelector.find('[name="file"]').on('fileSet', function () {
        let fileValue = $(this).val();
        let result = getFileImage(fileValue);

        let photoSelector = $('.modal-gallery-image img');
        photoSelector.removeClass('not-photo');
        if (!result.isPhoto) {
            photoSelector.addClass('not-photo');
        }
        photoSelector.attr('src', result.file);
    });
</script>
@include('admin._scripts.get_file_func')
