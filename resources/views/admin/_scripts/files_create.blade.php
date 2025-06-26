<script type="text/javascript">
    $('#file-modal form').on('ajaxFormSuccess', function (e, data) {
        let currentPage = {{(int) request('currentPage', 1)}};
        let creationPage = '{{request('sort', 'desc')}}' === 'desc' ? 1 : {{(int) request('lastPage', 1)}};

        $('#sortable').prepend(data.view);

        if (currentPage !== creationPage) {
            window.location.href = window.location.href.replace(location.search, '') + '?page=' + creationPage;
        } else {
            $(this).find('[data-bs-dismiss]').trigger('click');
        }
    });
</script>
