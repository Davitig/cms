<script type="text/javascript">
function getFileImage(file) {
    let fileExt = file.substr((~-file.lastIndexOf('.') >>> 0) + 2);
    let result = {'file':file, 'isPhoto':true};
    if (fileExt.length
        && ['jpg', 'jpeg', 'png', 'gif'].indexOf(fileExt) < 0
        && fileExt.indexOf('/') < 0
    ) {
        result.file = '{{asset('assets/libs/images/file-ext-icons')}}/' + fileExt + '.png';
        result.isPhoto = false;
    } else if (! fileExt.length || fileExt.indexOf('/') >= 0) {
        result.file = '{{asset('assets/libs/images/file-ext-icons/www.png')}}';
        result.isPhoto = false;
    }

    return result;
}
</script>
