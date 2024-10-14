<script type="text/javascript">
    // Initialize tinymce
    tinymceInit();

    function tinymceInit() {
        tinymce.init({
            selector: ".text-editor",
            theme: "modern",
            relative_urls: false,
            remove_script_host: false,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor | fullscreen",
            fontsize_formats: '10px 12px 14px 16px 18px 20px 22px 24px 26px 30px 36px',
            image_advtab: true,

            file_browser_callback : elFinderBrowser,

            setup: function(ed) {
                ed.on("init", function() {
                    $(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").hide();
                });
                ed.on('focus blur', function(e) {
                    if (e.type === 'focus') {
                        $(this.contentAreaContainer.parentElement).find("div.mce-toolbar-grp").show();
                    }
                    // Add "table" class to all table tags
                    tinymce.activeEditor.dom.addClass(tinymce.activeEditor.dom.select('table'), 'table');
                });
                ed.on('change', function() {
                    tinymce.triggerSave();
                });
            }
        });
    }

    // elFinder callback for tinymce
    function elFinderBrowser(field_name, url, type, win) {
        tinymce.activeEditor.windowManager.open({
            file: '{{ cms_route('filemanager.tinymce4') . '?iframe=1' }}', // use an absolute path!
            title: 'elFinder 2.1',
            width: 900,
            height: 600
            // resizable: 'yes'
        }, {
            setUrl: function(url) {
                win.$('#' + field_name).val(url);
            }
        });
        return false;
    }
</script>
