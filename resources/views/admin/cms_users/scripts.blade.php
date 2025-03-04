@push('body.bottom')
    <script type="text/javascript">
        $(function () {
            $('#photo-upload-btn').on('click', function () {
                $('#photo-input').trigger('click');
            });
            $('#photo-input').on('change', function() {
                let allowedExtension = [
                    'image/jpeg', 'image/jpg', 'image/png','image/gif','image/bmp', 'image/webp'
                ];
                if (! this.files || ! this.files[0]) {
                    $('.photo-msg').text('Error selecting a file');

                    return;
                }
                if (allowedExtension.indexOf(this.files[0].type) < 0) {
                    $('.photo-msg').text('Extension type ' + this.files[0].type + ' not allowed');

                    return;
                }

                let reader = new FileReader();

                reader.onload = function (e) {
                    $('#user-photo').attr('src', e.target.result).removeClass('hidden');
                    $('.photo-upload-text').addClass('hidden');
                }

                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endpush
