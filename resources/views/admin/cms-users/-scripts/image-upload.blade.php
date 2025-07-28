@pushonce('body.bottom')
    <script type="text/javascript">
        $(function () {
            $('.delete-image').on('submit', function (e) {
                e.preventDefault();
                let type = $(this).data('type');
                $.post($(this).attr('action'), $(this).serialize(), function (res) {
                    notyf(res?.message, res?.result ? res.result : 'warning');
                    let userTypeSelector = $('.user-' + type);
                    userTypeSelector.attr('src', userTypeSelector.data('default'));
                }, 'json').fail(function (xhr) {
                    notyf(xhr.statusText, 'error');
                });
            });

            uploadImage('photo');
            uploadImage('cover');

            function uploadImage(type) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    $('.user-' + type).attr('src', e.target.result);
                }

                let uploadFormSelector = $('form#upload-' + type);
                let imageFile;

                $('#' + type + '_inp').on('change', function () {
                    if (! this.files || ! this.files[0]) {
                        alert('Error selecting a file');

                        return;
                    }

                    $('.loading-' + type).removeClass('d-none');

                    imageFile = this.files[0];

                    uploadFormSelector.submit();
                });

                uploadFormSelector.on('ajaxFormDone', function () {
                    if (imageFile) {
                        reader.readAsDataURL(imageFile);
                    }
                });
                uploadFormSelector.on('ajaxFormAlways', function () {
                    $('.loading-' + type).addClass('d-none');
                });
            }
        });
    </script>
@endpushonce
