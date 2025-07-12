<!-- Vendors JS -->
<script src="{{ asset('assets/vendor/libs/sortablejs/sortable.js') }}"></script>
<script type="text/javascript">
    $(function () {
        sortable('{{ $routePositions }}', '{{ $csrfToken = csrf_token() }}', '{{ $sort }}', '{{ $currentPage }}', '{{ $foreignKey }}');

        let filesBlock = $('#files-block');
        let multiselect = [];

        // modal hidden event listener
        filesBlock.on('hidden.bs.modal', '#file-modal', function () {
            if (multiselect.length) {
                $('#item' + multiselect[0] + ' .item-edit').trigger('click');
            }

            $(this).remove();
        });

        // select all items
        $('.items-multi-select').on('click', function () {
            $('#items-multi-select').trigger('click');
        });
        $('#items-multi-select').on('change', function () {
            let isChecked = $(this).is(':checked');
            $('.item input[type="checkbox"]', filesBlock).prop('checked', isChecked).trigger('change');
        });

        // call create method modal
        filesBlock.on('click', '.item-add', function (e) {
            e.preventDefault();
            let params = {sort: '{{$sort}}', currentPage: {{$currentPage}}, lastPage: {{$lastPage}}};
            $.get('{{$routeCreate}}', params, function (data) {
                filesBlock.append(data.view);

                $('#file-modal').modal('show');
            }, 'json').fail(function (xhr) {
                notyf(xhr.statusText, 'error');
            });
        });

        // call edit method modal
        filesBlock.on('click', '.item-edit', function (e) {
            e.preventDefault();
            $.get($(this).data('url'), function (data) {
                filesBlock.append(data.view);

                $('#file-modal').modal('show');

                if (multiselect.length) {
                    multiselect.splice(0, 1);
                }
            }, 'json').fail(function (xhr) {
                notyf(xhr.statusText, 'error');
            });
        });

        // call multiple edit method modal
        filesBlock.on('click', '#edit-selected-items', function (e) {
            e.preventDefault();
            multiselect = [];
            $('.item input.item-select:checked', filesBlock).each(function() {
                multiselect.push($(this).data('id'));
            });

            if (multiselect.length) {
                $('#item' + multiselect[0] + ' .item-edit').trigger('click');
            } else {
                notyf('No items selected', 'warning');
            }
        });

        // delete item(s)
        filesBlock.on('click', '[data-delete-selected]', function (e) {
            e.preventDefault();

            let target = $(this);
            let input = {'_method':'delete', '_token':"{{csrf_token()}}"};

            input['ids'] = [];

            $('input.item-select:checked', filesBlock).each(function(i, e) {
                input.ids.push($(e).data('id'));
            });

            if (! input.ids.length) {
                notyf('No items selected', 'warning');
                return;
            }

            if (confirm('{{trans('general.confirm_delete_selected')}}') !== true) {
                return;
            }

            let url = target.data('url');

            $.post(url, input, function (res) {
                $.each(input.ids, function(i, e) {
                    $('#item' + e, filesBlock).fadeOut(500, function () {
                        $(this).remove();
                    });
                });
                textDecrement(input.ids.length);
                // alert message
                notyf(res.message, res.result ? 'success' : 'warning');
            }, 'json').fail(function (xhr) {
                notyf(xhr.statusText, 'error');
            });
        });
    });

    function getImageOrExtImage(file) {
        let fileExt = file.substring((~-file.lastIndexOf('.') >>> 0) + 2);
        if (! fileExt.length) {
            file = '{{ asset('assets/default/img/file-ext-icons/www.png') }}';
        } else if (['jpg', 'jpeg', 'png', 'gif'].indexOf(fileExt) < 0) {
            file = '{{ asset('assets/default/img/file-ext-icons') }}/' + fileExt + '.png';
        }
        return file;
    }
</script>
