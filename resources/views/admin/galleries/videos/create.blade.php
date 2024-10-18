@if (! empty($current))
    <div class="modal fade" id="form-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-gallery-image embed-responsive embed-responsive-16by9">
                    <iframe width="600" height="315" src="" frameborder="0" allowfullscreen class="embed-responsive-item"></iframe>
                </div>
                {{ html()->modelForm($current,
                    'post', cms_route('videos.store', [$current->gallery_id])
                )->class('form-create form-horizontal')->open() }}
                <div class="modal-body">
                    <div class="row">
                        @include('admin.galleries.videos.form')
                    </div>
                </div>
                {{ html()->form()->close() }}
            </div>
        </div>
        <script type="text/javascript">
            var sort = '{{request('sort', 'desc')}}';
            var currentPage = '{{request('page_val', '1')}}';
            var creationPage = sort === 'desc' ? '1' : '{{request('lastPage', '1')}}';
            var formSelector = $('#form-modal').find('.form-create');

            formSelector.on('submit', function(e) {
                e.preventDefault();

                var form = $(this);
                $('.form-group', form).find('.text-danger').remove();
                var url = form.attr('action');
                var input = form.serialize();
                $.post(url, input, function(data) {
                    // alert toastr message
                    toastr[data.result](data.message);

                    var imageContainer = '.gallery-env .album-images';
                    var insert = $(imageContainer).data('insert');
                    insert = Function("$('"+imageContainer+"')."+insert+"('"+data.view+"');");
                    insert();

                    cbr_replace();

                    if (currentPage !== creationPage) {
                        window.location.href = '{{cms_route('videos.index', [$current->gallery_id])}}?page=' + creationPage;
                    } else {
                        $('#form-modal').find('[data-dismiss]').trigger('click');
                    }
                }, 'json').fail(function(xhr) {
                    if (xhr.status === 422) {
                        var data = xhr.responseJSON.errors;

                        $.each(data, function(index, element) {
                            var input = $('#' + index, form);
                            input.closest('.form-group').addClass('validate-has-error');
                            input.after('<span class="text-danger">'+element+'</span>');
                        });
                    } else {
                        alert(xhr.responseText);
                    }
                });
            });
        </script>
    </div>
@endif
