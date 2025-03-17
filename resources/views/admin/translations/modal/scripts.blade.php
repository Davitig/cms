<script type="text/javascript">
$(function () {
    let transModalSelector = $('.trans-modal');

    $('form', transModalSelector).on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let lang = form.data('lang') ?? '';
        $('.trans-form-group', form).find('.trans-text-danger').remove();

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            dataType: 'json',
            data: form.serialize(),
            success: function (data) {
                $('.trans-modal-footer', form).prepend(
                    $('<span class="trans-text-success">saved</span>').delay(1000).fadeOut(300, function () {
                        $(this).remove();
                    })
                ).fadeIn(300);

                if (! lang || lang === '{{$currentLang = language()->active()}}') {
                    let trans = $('[data-trans="'+data.code+'"]');
                    let attrName = trans.data('trans-attr');
                    if (! attrName) {
                        trans.text(data.value);
                    } else {
                        trans.attr(attrName, data.value);
                    }
                }
                if (! lang) {
                @if (language()->containsMany())
                    transModalSelector.removeClass('fade');
                    let ev = jQuery.Event('click');
                    ev.f2 = true;
                    $('[data-trans="'+data.code+'"]').trigger(ev);
                @endif
                    transModalSelector.trigger('close.trans.modal');
                } else {
                    $('form [name="title"]', transModalSelector).each(function (i, e) {
                        $(e).val(data.title);
                    });
                    $('form [name="type"]', transModalSelector).each(function (i, e) {
                        $(e).val(data.type);
                    });
                }
            },
            error: function (xhr) {
                if (! xhr?.responseJSON?.errors) {
                    alert(xhr.responseText);
                    return;
                }
                $.each(xhr.responseJSON.errors, function (index, element) {
                    let field = $('#' + index + lang, form);
                    if (index === 'code') {
                        element += ' (current code: "' + field.val() + '")';
                    }
                    let errorMsg = '<div class="trans-text-danger">'+element+'</div>';
                    field.after(errorMsg);
                });
            }
        });
    });

    let trans = $('[data-trans="{{$current->code}}"]');

    $('form [name="value"]', transModalSelector).on('keyup', function () {
        let lang = $(this).closest('form').data('lang');

        if (! lang || lang === '{{$currentLang}}') {
            let attrName = trans.data('trans-attr');
            if (! attrName) {
                trans.text($(this).val());
            } else {
                trans.attr(attrName, $(this).val());
            }
        }
    });

    $('.trans-nav a', transModalSelector).on('click', function (e) {
        e.preventDefault();
        $(this).parent().addClass('active').siblings().each((i, e) => $(e).removeClass('active'));
        let tab = $(this).attr('href');
        $(tab, transModalSelector).addClass('active').siblings().each((i, e) => $(e).removeClass('active'));
    });

    $('[data-dismiss]', transModalSelector).on('click', function () {
        transModalSelector.trigger('close.trans.modal');
    });
    $(document).on('click', function (e) {
        if (! $(e.target).closest('.trans-dialog').length) {
            transModalSelector.trigger('close.trans.modal');
        }
    });

    transModalSelector.on('close.trans.modal', function () {
        $(this).fadeOut(200, function () {
            $(this).remove();
        });
        $('.trans-modal-bg').fadeOut(200, function () {
            $(this).remove();
        });
    });
});
</script>
