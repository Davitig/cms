<script type="text/javascript">
    // Initialize tinymce
    tinymceInit();

    function tinymceInit() {
        tinymce.init({
            license_key: 'gpl',
            selector: ".text-editor",
            relative_urls: false,
            remove_script_host: false,
            plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion',
            toolbar: "undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl",
            font_size_formats: '10px 12px 14px 16px 18px 20px 22px 24px 26px 30px 36px',
            image_advtab: true,

            file_picker_callback: elFinderBrowser,

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
    function elFinderBrowser (callback, value, meta) {
        tinymce.activeEditor.windowManager.openUrl({
            title: 'elFinder File Manager',
            url: '{{ cms_route('filemanager.tinymce5') . '?iframe=1' }}',
            /**
             * On message will be triggered by the child window
             *
             * @param dialogApi
             * @param details
             * @see https://www.tiny.cloud/docs/ui-components/urldialog/#configurationoptions
             */
            onMessage: function (dialogApi, details) {
                if (details.mceAction === 'fileSelected') {
                    const file = details.data.file;

                    // Make file info
                    const info = file.name;

                    // Provide file and text for the link dialog
                    if (meta.filetype === 'file') {
                        callback(file.url, {text: info, title: info});
                    }

                    // Provide image and alt text for the image dialog
                    if (meta.filetype === 'image') {
                        callback(file.url, {alt: info});
                    }

                    // Provide alternative source and posted for the media dialog
                    if (meta.filetype === 'media') {
                        callback(file.url);
                    }

                    dialogApi.close();
                }
            }
        });
    }
</script>
