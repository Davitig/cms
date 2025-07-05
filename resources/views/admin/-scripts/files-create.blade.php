<script type="text/javascript">
    $('#file-modal form').on('ajaxFormSuccess', function (e, res) {
        let sort = '{{ request('sort', 'desc') }}';
        let currentPage = {{(int) request('currentPage', 1)}};
        let creationPage = sort === 'desc' ? 1 : {{ (int) request('lastPage', 1) }};

        if (currentPage !== creationPage) {
            window.location.href = window.location.href.replace(location.search, '') + '?page=' + creationPage;
        } else {
            if (sort === 'desc') {
                $('#sortable').prepend(res.view);
            } else {
                $('#sortable').append(res.view);
            }

            textIncrement();

            $(this).find('[data-bs-dismiss]').trigger('click');
        }
    });
</script>
