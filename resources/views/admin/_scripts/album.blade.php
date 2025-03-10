<script type="text/javascript">
    $(function() {
        let galleryEnv = $('.gallery-env');

        // Select all items
        $("#select-all").on('change', function(e) {
            let is_checked = $(this).is(':checked');
            $(".album-image input[type='checkbox']").prop('checked', is_checked).trigger('change');
        });

        let multiselect = [];

        // Call Modal
        galleryEnv.on('click', 'a[data-modal]', function(e) {
            e.preventDefault();
            let action = $(this).data('modal');
            if (action === 'edit') {
                let item = $(this).closest('.item');
                let url = item.data('url');

                $.get(url, function(data) {
                    galleryEnv.append(data.view);

                    $("#form-modal").modal('show');
                }, 'json').fail(function(xhr) {
                    alert(xhr.responseText);
                });

                if (multiselect.length) {
                    multiselect.splice(0, 1);
                }
            } else if (action === 'add') {
                let params = {sort: '{{$sort}}', currentPage: {{$currentPage}}, lastPage: {{$lastPage}}};
                $.get('{{$routeCreate}}', params, function(data) {
                    galleryEnv.append(data.view);

                    $("#form-modal").modal('show');
                }, 'json').fail(function(xhr) {
                    alert(xhr.responseText);
                });
            } else if (action === 'multiselect') {
                $('.album-image input:checked').each(function() {
                    multiselect.push($(this).data('id'));
                });

                if (multiselect.length) {
                    $('#item'+multiselect[0]+' .thumb[data-modal="edit"]').trigger('click');
                }
            }
        });

        // Modal hidden event listener
        galleryEnv.on('hidden.bs.modal', '#form-modal', function () {
            if (multiselect.length) {
                $('#item'+multiselect[0]+' .thumb[data-modal="edit"]').trigger('click');
            }

            $(this).remove();
        });

        // Delete item(s)
        galleryEnv.on('click', 'a[data-delete]', function(e) {
            e.preventDefault();
            if (confirm("{{trans('general.confirm_delete_selected')}}") !== true) {
                return;
            }
            let ids = [];
            let action = $(this).data('delete');
            if (action === 'multiselect') {
                action = $('.select-item input:checked', galleryEnv).each(function(i, e) {
                    ids.push($(e).data('id'));
                }).closest('.image-options').find('[data-delete]').data('delete');
            }

            let input = {'ids':ids, '_method':'delete', '_token':"{{$csrfToken = csrf_token()}}"};
            ids.push($(this).data('id'));

            $.post(action, input, function(data) {
                // alert toastr message
                toastr[data.result](data.message);

                if (data.result === 'success') {
                    $.each(ids, function(i, e) {
                        $('#item'+e, galleryEnv).remove();
                    });
                }
            }, 'json').fail(function(xhr) {
                alert(xhr.responseText);
            });
        });

        // visibility of the item
        galleryEnv.on('click', '.visibility', function(e) {
            e.preventDefault();
            let item = $(this);
            let url = item.data('url');

            let input = {'_token': '{{$csrfToken}}', '_method': 'put'};
            $.post(url, input, function(data) {
                item.find('i').attr('class', data ? 'fa fa-eye' : 'fa fa-eye-slash');
            }, 'json').fail(function(xhr) {
                alert(xhr.responseText);
            });
        });

        // Sortable
        $('.gallery-env a[data-action="sort"]').on('click', function(e) {
            e.preventDefault();

            let albumImageSelector = $(".album-images");
            let is_sortable = albumImageSelector.sortable('instance');

            if( ! is_sortable) {
                $('.gallery-env .album-images').sortable({
                    items: '> li',
                    containment: 'parent'
                });

                $('.album-sorting-info').stop().slideDown(300);
                $('#save-tree').show().prop('disabled', false);
            } else {
                albumImageSelector.sortable('destroy');
                $('.album-sorting-info').stop().slideUp(300);
            }
        });

        positionable('{{$routePosition}}', '{{$sort}}', {{$currentPage}}, {{(int) ($currentPage < $lastPage)}});
    });
</script>
