@php use Illuminate\Support\Arr; @endphp
@push('head')
    <link rel="stylesheet" href="{{ asset('assets/libs/js/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/js/select2/select2-bootstrap.css') }}">
@endpush
@push('body.bottom')
    <script type="text/javascript">
        $(function() {
            $('select.select').select2({
                placeholder: 'Select item',
                allowClear: true
            }).on('select2-open', function() {
                // Add custom scrollbar
                $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
            });

            let typeSelect = $('.panel form [name="type"].select');

            let typeId = $('.panel form .type-id');
            let typeIdSelect = $('select', typeId);
            let typeIdText = '<div class="type-id-msg">List is empty. Add from <a href="{{ cms_route('collections.index') }}"'
                + ' class="text-info" target="_blank">collections</a></div>';
            let typeIdElement = typeId.find('.input-group').parent();

            let typeValue;

            typeSelect.on('change', function() {
                // Get the listable types
                if (typeValue !== this.value) {
                    getListableTypes(this.value);
                }

                typeValue = this.value;
            });

            // Get the listable types
            function getListableTypes(value) {
                typeId.addClass('hidden');
                typeIdSelect.html('<option value=""></option>');

                @php($listableTypes = Arr::mapWithKeys(cms_pages('listable'), fn ($item) => array_keys($item)))
                if (["{!! implode('","', $listableTypes) !!}"].indexOf(value) >= 0) {
                    $('label', typeId).text(String(value).charAt(0).toUpperCase() + String(value).slice(1));

                    $.get('{{cms_route('pages.getListableTypes')}}', {"type": value}, function (data) {
                        typeIdSelect.html('<option value=""></option>');
                        typeId.removeClass('hidden');

                        $.each(data, function (key, value) {
                            typeIdSelect.append('<option value="'+key+'">'+value+'</option>');
                        });

                        typeIdSelect.select2('val', '');

                        typeIdElement.find('.type-id-msg').remove();

                        if (Array.isArray(data)) {
                            typeIdElement.append(typeIdText);
                        }
                    }, 'json').fail(function (xhr) {
                        alert(xhr.responseText);
                    });
                }
            }
        });
    </script>
    <script src="{{ asset('assets/libs/js/select2/select2.min.js') }}"></script>
@endpush
