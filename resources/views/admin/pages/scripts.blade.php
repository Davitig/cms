@php use Illuminate\Support\Arr; @endphp
@push('body.bottom')
    <script type="text/javascript">
        $(function () {
            let form = $('form#pages-form');

            let typeId = $('.type-id', form);
            let typeIdSelectEl = $('select', typeId);
            let typeIdText = '<div class="type-id-msg">List is empty. Add in <a href="{{ cms_route('collections.index') }}"'
                + ' class="text-info" target="_blank">Collections</a></div>';

            $('[name="type"]', form).on('change', function () {
                $('.type-id .text-danger', form).remove();
                getListableTypes(this.value);
            });

            // Get the listable types
            function getListableTypes(value) {
                typeId.addClass('d-none');
                typeIdSelectEl.html('<option value=""></option>');

                @php($listableTypes = Arr::mapWithKeys(cms_pages('listable'), fn ($item) => array_keys($item)))
                if (["{!! implode('","', $listableTypes) !!}"].indexOf(value) >= 0) {
                    $('label', typeId).text(String(value).charAt(0).toUpperCase() + String(value).slice(1));

                    $.get('{{cms_route('pages.get_listable_types')}}', {"type": value}, function (data) {
                        typeIdSelectEl.html('<option value=""></option>');
                        typeId.removeClass('d-none');

                        $.each(data, function (key, value) {
                            typeIdSelectEl.append('<option value="'+key+'">'+value+'</option>');
                        });

                        typeId.find('.type-id-msg').remove();

                        if (Array.isArray(data)) {
                            typeId.append(typeIdText);
                        }
                    }, 'json').fail(function (xhr) {
                        notyf(xhr.statusText, 'error');
                    });
                }
            }
        });
    </script>
@endpush
