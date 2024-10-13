@push('head')
    <link rel="stylesheet" href="{{ asset('assets/libs/js/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/js/select2/select2-bootstrap.css') }}">
@endpush
@push('body.bottom')
    <script type="text/javascript">
        $(function() {
            $('.select').select2({
                placeholder: 'Select item',
                allowClear: true
            }).on('select2-open', function() {
                // Add custom scrollbar
                $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
            });

            var typeSelect = $('.panel form [name="type"].select');

            var typeId = $('.panel form .type-id');
            var typeIdSelect = $('select', typeId);

            var template = $('.panel form .template');
            var templateSelect = $('select', template);

            var typeValue, templateValue;

            typeSelect.on('change', function() {
                // Get the attached types list
                if (typeValue !== this.value) {
                    getListableTypes(this.value);
                }

                typeValue = this.value;

                if (templateValue !== this.value) {
                    getTemplates(this.value)
                }

                templateValue = this.value;
            });

            // Get the attached types list
            function getListableTypes(value) {
                typeId.addClass('hidden');
                typeIdSelect.html('<option value=""></option>');

                if (["{!!implode('","', (array) cms_pages('listable'))!!}"].indexOf(value) >= 0) {
                    $('label', typeId).text(value);

                    $.get('{{cms_route('pages.listableTypes')}}', {"type": value}, function (data) {
                        typeIdSelect.html('<option value=""></option>');
                        typeId.removeClass('hidden');

                        $.each(data, function (key, value) {
                            typeIdSelect.append('<option value="'+key+'">'+value+'</option>');
                        });

                        typeIdSelect.select2('val', '');
                    }, 'json').fail(function (xhr) {
                        alert(xhr.responseText);
                    });
                }
            }

            // Get the template list
            function getTemplates(value) {
                if (["{!!implode('","', array_keys((array) cms_pages('templates')))!!}"].indexOf(value) >= 0) {
                    $.get('{{cms_route('pages.templates')}}', {"type": value}, function (data) {
                        templateSelect.html('<option value=""></option>');

                        if (!$.isEmptyObject(data)) {
                            template.removeClass('hidden');

                            $.each(data, function (key, value) {
                                templateSelect.append('<option value="' + key + '">' + value + '</option>');
                            });
                        } else {
                            template.addClass('hidden');
                        }
                    }, 'json').fail(function (xhr) {
                        alert(xhr.responseText);
                    });
                } else {
                    template.addClass('hidden');
                }

                templateSelect.select2('val', '');
            }
        });
    </script>
    <script src="{{ asset('assets/libs/js/select2/select2.min.js') }}"></script>
@endpush
