<script type="text/javascript">
$(function () {
    var modalSelector = $('#translations-modal');

    $('form', modalSelector).on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        var lang = form.data('lang') ?? '';
        $('.form-group', form).find('.text-danger').remove();

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            dataType: 'json',
            data: form.serialize(),
            success: function (data) {
                $('.modal-footer', form).prepend(
                    $('<span class="text-success">saved</span>').delay(1000).fadeOut(300, function () {
                        $(this).remove();
                    })
                ).fadeIn(300);

                if (! lang || lang === '{{$currentLang = language()}}') {
                    var trans = $('[data-trans="'+data.code+'"]');
                    var attrName = trans.data('trans-attr');
                    if (! attrName) {
                        trans.text(data.value);
                    } else {
                        trans.attr(attrName, data.value);
                    }
                }
                if (! lang) {
                @if (count(languages()) > 1)
                    modalSelector.removeClass('fade');
                    var ev = jQuery.Event('click');
                    ev.f2 = true;
                    $('[data-trans="'+data.code+'"]').trigger(ev);
                @endif
                    modalSelector.modal('hide');
                } else {
                    $('form [name="title"]', modalSelector).each(function (i, e) {
                        $(e).val(data.title);
                    });
                    $('form [name="type"]', modalSelector).each(function (i, e) {
                        $(e).val(data.type);
                    });
                }
            },
            error: function (xhr) {
                if (xhr.status !== 422) {
                    alert(xhr.responseText);
                    return;
                }
                if (xhr.responseJSON.errors === undefined) {
                    return;
                }
                var errors = xhr.responseJSON.errors;
                $.each(errors, function (index, element) {
                    var field = $('#' + index + lang, form);
                    var errorMsg = '<div class="text-danger">'+element+'</div>';
                    field.after(errorMsg);
                });
            }
        });
    });

    $('form [name="value"]', modalSelector).on('keyup', function () {
        var lang = $(this).closest('form').data('lang');

        if (! lang || lang === '{{$currentLang}}') {
            var trans = $('[data-trans="{{$current->code}}"]');
            var attrName = trans.data('trans-attr');
            if (! attrName) {
                trans.text($(this).val());
            } else {
                trans.attr(attrName, $(this).val());
            }
        }
    });

    modalSelector.modal('show');
    modalSelector.on('hidden.bs.modal', function () {
        $(this).remove();
    });
});
</script>
