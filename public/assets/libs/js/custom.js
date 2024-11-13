function getDateTimeString(date) {
    const dateObj = date ? new Date(date) : new Date;
    const y = dateObj.getFullYear();
    let m = dateObj.getMonth() + 1;
    let d = dateObj.getDate();
    let h = dateObj.getHours();
    let i = dateObj.getMinutes();
    let s = dateObj.getSeconds();

    if (d < 10) d = '0' + d;
    if (m < 10) m = '0' + m;
    if (h < 10) h = '0' + h;
    if (i < 10) i = '0' + i;
    if (s < 10) s = '0' + s;

    return y+'-'+m+'-'+d+' '+h+':'+i+':'+s;
}
$(function () {
    // Fix sidebar toggle when it has fixed position
    $('a[data-toggle="sidebar"]').on('click', function (e) {
        e.preventDefault();

        let expanded = $('#main-menu').find('.expanded');
        if (expanded.length) {
            if (public_vars.$sidebarMenu.hasClass('collapsed')) {
                $('> ul', expanded).css('display', 'block');
            } else {
                $('> ul', expanded).css('display', '');
            }
        }
    });

    // Toggle page action buttons
    let items = $('#items');
    items.on('click', '.btn-toggle', function (e) {
        e.preventDefault();

        if (! $(this).hasClass('active')) {
            $('.btn-action', items).hide();
            $('.btn-toggle', items).removeClass('active');
        }

        $(this).siblings('.btn-action').toggle(300);
        $(this).addClass('active');
    });

    // Make form closable on "#submit-close" click
    $('#submit-close').on('click', function () {
        $('input.form-close').val(1);
    });

    // Disable buttons after submit for some period of time
    $(document).on('submit', 'form', function () {
        $('input[type="submit"], button[type="submit"]', this).prop('disabled', true);

        setTimeout(function (form) {
            $('input[type="submit"], button[type="submit"]', form).prop('disabled', false);
        }, 800, this);
    });

    // Delete form
    $(document).on('submit', '.form-delete', function (e) {
        e.preventDefault();
        if (! confirm('Are you sure you want to delete?')) {
            return;
        }
        let form = $(this);
        let btn = form.find('[type="submit"]').prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            dataType: 'json',
            data: form.serialize(),
            success: function (res) {
                if (res) {
                    if (res?.data?.redirect) {
                        window.location.href = res.data.redirect;
                    }
                    // toastr alert message
                    if (typeof toastr === 'object' && res?.result) {
                        toastr[res.result](res?.message);
                    }
                    // delete action
                    if (res?.result === 'success') {
                        form.closest('.item').fadeOut(600, function () {
                            if ($(this).data('parent') === 1) {
                                $(this).closest('.uk-parent').removeClass('uk-parent');
                                disableParentDeletion();
                            }

                            $(this).remove();
                        });
                    }
                }

                form.trigger('deleteFormSuccess', [res]);
            },
            error: function (xhr) {
                alert(xhr.responseText);
            },
            complete: function () {
                btn.prop('disabled', false);
            }
        });
    });

    // Ajax form submit
    let ajaxFormSelector = 'form.ajax-form';
    $(document).on('submit', ajaxFormSelector, function (e) {
        e.preventDefault();
        let form = $(this);
        form.find('.text-danger').remove();
        let lang = form.data('lang') ?? '';

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            dataType: 'json',
            data: form.serialize(),
            success: function (res) {
                // toastr alert message
                if (typeof toastr === 'object' && res?.result) {
                    toastr[res.result](res?.message);
                }

                $('.form-group', form).removeClass('validate-has-error');

                // fill form inputs
                if (res?.data && typeof res.data === 'object') {
                    $.each(res.data, function (index, element) {
                        let item = $('#' + index + lang, form);

                        if (item.data('lang')) {
                            let inputGeneral = $(ajaxFormSelector + ' [name="' + index + '"]');
                            $(inputGeneral).each(function (i, e) {
                                item = $(e);
                                if (item.val() !== element) {
                                    item.val(element);
                                    if (item.is(':checkbox')) {
                                        let bool = element === 1;
                                        item.prop('checked', bool);
                                    }
                                    if (item.is('select')) {
                                        item.trigger('change');
                                    }
                                }
                            });
                        } else if (item.val() !== element) {
                            item.val(element);
                        }
                    });
                }

                form.trigger('ajaxFormSuccess', [res]);
            },
            error: function (xhr) {
                if (xhr.responseJSON.errors === undefined) {
                    alert(xhr.responseText);

                    return;
                }
                $.each(xhr.responseJSON.errors, function (index, element) {
                    let field;
                    let arrayField = index.substr(0, index.indexOf('.'));
                    if (arrayField) {
                        field = $('.' + arrayField + lang, form).first();
                    } else {
                        field = $('#' + index + lang, form);
                    }
                    field.closest('.form-group').addClass('validate-has-error');

                    let errorMsg = '<div class="text-danger">'+element+'</div>';
                    if (field.parent().hasClass('input-group')) {
                        field.parent().after(errorMsg);
                    } else {
                        field.after(errorMsg);
                    }
                });

                let errorField = form.find('.validate-has-error').first();
                if (errorField) {
                    let errorOffset = errorField.offset();
                    if (errorOffset) {
                        $('html, body').animate({
                            scrollTop: errorField.offset().top - 100
                        }, 400);
                    }
                }
            },
            complete: function () {
                form.trigger('ajaxFormComplete');
            }
        });
    });

    // Visibility request
    $('form.visibility').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);

        $.post(form.attr('action'), form.serialize(), function (res) {
            let icon, removeClass, addClass;
            if (res) {
                icon = 'fa fa-eye';
                removeClass = 'btn-gray';
                addClass = 'btn-white';
            } else {
                icon = 'fa fa-eye-slash';
                removeClass = 'btn-white';
                addClass = 'btn-gray';
            }
            form.removeClass(removeClass)
                .addClass(addClass)
                .find('span')
                .attr('class', icon);
        }, 'json').fail(function (xhr) {
            alert(xhr.responseText);
        });
    });

    // substring text
    $('[data-substr-limit], [data-substr-end]').each(function () {
        let strLimit = parseInt($(this).data('substr-limit'));
        strLimit = strLimit ? strLimit : 100;
        let strEnd = $(this).data('substr-end');
        let str = $(this).text();
        if (str.length > strLimit) {
            $(this).text(str.substring(0, strLimit) + (strEnd !== undefined ? strEnd : '...'));
        }
    });
});

// Lockscreen event handlers and functions
let timer;
let timerIsActive = true;

$('form#set-lockscreen').on('submit', function (e) {
    e.preventDefault();

    clearTimeout(timer);
    timerIsActive = false;

    setLockscreen($(this));
});

function setLockscreen(form) {
    $.post(form.attr('action'), form.serialize(), function (res) {
        if (res) {
            let body = $('body');
            body.append(res.view);
            body.addClass('lockscreen-page');
        }
    }, 'json').fail(function (xhr) {
        alert(xhr.responseText);
    });
}

function lockscreen(time, form, reActive) {
    if (reActive) {
        timerIsActive = true;
    }

    $(document).on('click mousemove keypress scroll', function () {
        if (timerIsActive) {
            clearTimeout(timer);

            timer = setTimeout(function () {
                setLockscreen(form);

                timerIsActive = false;
            }, time);
        }
    });

    $(document).trigger('mousemove');
}
// Lockscreen end

// Update url recursively
function updateUrl(target, url) {
    let prevUrl = url;

    target.each(function () {
        let item = $(this).find('a.link');

        url = prevUrl + '/' + item.data('slug');

        item.attr('href', url);

        if ($(this).hasClass('uk-parent')) {
            updateUrl($('> ul', this).children('li'), url);
        }
    });
}

function disableParentDeletion() {
    let nestable = $('#nestable-list');
    $('.form-delete [type="submit"]', nestable).prop('disabled', false);

    $('.uk-parent', nestable).each(function () {
        $('.form-delete [type="submit"]', this).first().prop('disabled', true);
    });
}

function positionable(url, orderBy, page, hasMorePages) {
    const aTagStart = '<a href="#" class="move btn btn-gray fa-long-arrow-';
    const aTagPrev = 'left left" data-move="prev" title="Move to prev page"';
    const aTagNext = 'right right" data-move="next" title="Move to next page"';
    const aTagEnd = '></a>';
    let saveBtn = $('#save-tree');
    let saveBtnIcon = $('.icon-var', saveBtn);
    let postHidden = {'_method':'put', '_token':saveBtn.data('token')};
    let nestable = $('#nestable-list');
    page = parseInt(page);

    if (page) {
        if (hasMorePages) {
            $('.btn-action', nestable).prepend(aTagStart + aTagNext + aTagEnd);
        }
        if (page > 1) {
            $('.btn-action', nestable).prepend(aTagStart + aTagPrev + aTagEnd);
        }
    }

    nestable.on('nestable-stop', function () {
        $('.move', nestable).remove();
        saveBtn.show().prop('disabled', false);
        saveBtnIcon.removeClass('fa-spin fa-check').addClass('fa-save');
    });

    // Position move
    nestable.on('click',  'a.move', function (e) {
        e.preventDefault();
        let move = $(this).data('move');
        let item = $(this).closest('li');
        let input = [{'id':item.data('id'), 'pos':item.data('pos')}];
        let items;

        if (move === 'next') {
            items = item.nextAll();
        } else {
            items = item.prevAll();
        }

        items.each(function (i, e) {
            input.push({'id':$(e).data('id'), 'pos':$(e).data('pos')});
        });

        input = $.extend({'data':input, 'move':move, 'orderBy':orderBy}, postHidden);

        $.post(url, input, function () {
            page = move === 'next' ? page + 1 : page - 1;
            let href = window.location.href;
            let hrefQueryStart = href.indexOf('?');
            if (hrefQueryStart > 1) {
                href = href.substr(0, hrefQueryStart);
            }
            window.location.href = href + '?page=' + page;
        }, 'json').fail(function (xhr) {
            alert(xhr.responseText);
        });
    });

    // Position save
    saveBtn.on('click', function () {
        $(this).prop('disabled', true);
        saveBtnIcon.addClass('fa-spin');
        let nestable = $('#nestable-list');

        if (page) {
            $('.move', nestable).remove();
            if (hasMorePages) {
                $('btn-action', nestable).prepend(aTagStart + aTagNext + aTagEnd);
            }
            if (page > 1) {
                $('btn-action', nestable).prepend(aTagStart + aTagPrev + aTagEnd);
            }
        }

        let input = nestable.data('nestable').serialize();

        if (orderBy) {
            let posArr = [];
            $(input).each(function (i, e) {
                posArr[i] = e.pos;
            });
            if (orderBy === 'desc') {
                posArr.sort(function (a, b) {return b-a});
            } else {
                posArr.sort(function (a, b) {return a-b});
            }
            $(posArr).each(function (i, e) {
                input[i].pos = e;
            });
        }

        input = {'data':input};
        input = $.extend(input, postHidden);

        $.post(url, input, function () {
            saveBtnIcon.removeClass('fa-spin fa-save').addClass('fa-check');

            if (orderBy) {
                $(input.data).each(function (i, e) {
                    $('item'+e.id, nestable).data('pos', e.pos);
                });
            }

            disableParentDeletion();

            saveBtn.trigger('positionSaved');
        }, 'json').fail(function (xhr) {
            saveBtnIcon.removeClass('fa-spin fa-save').addClass('fa-remove');

            alert(xhr.responseText);
        }).always(function () {
            saveBtn.delay(400).fadeOut(500);
        });
    });
}
