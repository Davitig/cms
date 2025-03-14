function formatBytes(bytes, precision = 2, separator = ' ')
{
    let units = ['B', 'KB', 'MB', 'GB', 'TB'];

    bytes = Math.max(bytes, 0).toFixed(precision);
    let pow = Math.floor((bytes ? Math.log(bytes) : 0) / Math.log(1024));
    pow = Math.min(pow, units.length - 1);

    bytes /= Math.pow(1024, pow);

    return Math.round(bytes).toFixed(precision) + separator + units[pow];
}
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

    // Disable buttons for some period of time after submitting
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
                        // move up
                        let item = form.closest('.item');
                        let subItems = item.find('.uk-nestable-list').first()
                            .find('.item[data-parent="'+item.data('id')+'"]');
                        if (subItems.length) {
                            let baseItem = item.closest('.item[data-id="'+item.data('parent')+'"]')
                                .find('.uk-nestable-list').first();
                            if (baseItem.length) {
                                let parentId = item.data('parent');
                                let pos = parseInt(
                                    $('.item[data-parent="'+parentId+'"]', baseItem).last().data('pos')
                                );
                                subItems.each(function (i) {
                                    $(this).attr('data-pos', pos + i + 1).attr('data-parent', parentId);
                                    baseItem.append(this);
                                });
                            } else {
                                let list = $('#nestable-list');
                                let pos = parseInt(
                                    $('.item[data-parent="0"]', list).last().data('pos')
                                );
                                subItems.each(function (i) {
                                    $(this).attr('data-pos', pos + i + 1).attr('data-parent', 0);
                                    list.append(this);
                                });
                            }
                        }
                        // remove
                        item.fadeOut(500, function () {
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
            data: new FormData(this),
            processData: false,
            contentType: false,
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
                                    if (item.is(':checkbox')) {
                                        item.prop('checked', Boolean(element));
                                    } else {
                                        item.val(element);
                                        if (item.is('select')) {
                                            item.trigger('change');
                                        }
                                    }
                                }
                            });
                        } else if (item.val() !== element) {
                            if (! item.is(':checkbox')) {
                                item.val(element);
                            }
                        }
                    });
                }

                form.trigger('ajaxFormSuccess', [res]);
            },
            error: function (xhr) {
                if (! xhr?.responseJSON?.errors) {
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
    $(document).on('submit', 'form.visibility', function (e) {
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
            form.trigger('visibilityResponse', [res])
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
let lockscreenTimer;
let lockscreenTimerIsActive = true;

$('form#set-lockscreen').on('submit', function (e) {
    e.preventDefault();

    clearTimeout(lockscreenTimer);
    lockscreenTimerIsActive = false;

    setLockscreen($(this).attr('action'), $(this).find('input[name="_token"]').val());
});

function activateLockscreenTimer() {
    lockscreenTimerIsActive = true;

    $(document).trigger('lockscreen');
}

function setLockscreen(url, csrfToken) {
    if (! url) {
        console.log('%c Lockscreen URL not provided.', 'color:red;');
        return;
    }
    $.post(url, {'_token': csrfToken}, function (res) {
        if (res) {
            let body = $('body');
            body.append(res.view);
            body.addClass('lockscreen-page');
        }
    }, 'json').fail(function (xhr) {
        alert(xhr.responseText);
    });
}

function lockscreen(time, url, csrfToken) {
    $(document).on('click mousemove keypress scroll lockscreen', function () {
        if (lockscreenTimerIsActive) {
            clearTimeout(lockscreenTimer);

            lockscreenTimer = setTimeout(function () {
                setLockscreen(url, csrfToken);

                lockscreenTimerIsActive = false;
            }, time);
        }
    });

    $(document).trigger('mousemove');
}
// Lockscreen end

// Update sub items data
function updateSubItems(subItems, url, parentId = 0) {
    let prevUrl = url;

    subItems.each(function () {
        $(this).attr('data-parent', parentId);

        let item = $(this).find('a.link');
        let itemSlug = item.data('slug');

        if (itemSlug) {
            url = prevUrl + '/' + itemSlug;

            item.attr('href', url);
        }

        if ($(this).hasClass('uk-parent')) {
            updateSubItems($('> ul', this).children('li'), url, $(this).data('id'));
        }
    });
}

let saveTreeBtn = $('#save-tree');

// Update nestable list data after position update
saveTreeBtn.on('positionSaved', function() {
    let webUrl = $('#web-url').attr('href');
    let parentSlug = $('#items').data('parent-slug');
    if (parentSlug) {
        webUrl += '/' + parentSlug;
    }
    updateSubItems($('#nestable-list').find('> li'), webUrl);
});

function positionable(url, orderBy, page, hasMorePages, selectors) {
    const aTagStart = '<a href="#" class="move btn btn-gray fa fa-arrow-';
    const aTagPrev = 'left left" data-move="prev" title="Move to prev page"';
    const aTagNext = 'right right" data-move="next" title="Move to next page"';
    const aTagEnd = '></a>';
    let saveBtnIcon = $('.fa-save', saveTreeBtn);
    let postHidden = {'_method':'put', '_token':saveTreeBtn.data('token')};
    let nestables = [];
    if (selectors === undefined || selectors.length === 0) {
        nestables.push($('#nestable-list'));
    } else {
        $.each(selectors, function (i, selector) {
            nestables.push($(selector));
        })
    }
    page = parseInt(page);

    if (page) {
        $.each(nestables, function (i, nestable) {
            if (hasMorePages) {
                $('.btn-action', nestable).prepend(aTagStart + aTagNext + aTagEnd);
            }
            if (page > 1) {
                $('.btn-action', nestable).prepend(aTagStart + aTagPrev + aTagEnd);
            }
        });
    }

    $.each(nestables, function (i, nestable) {
        nestable.on('nestable-stop', function () {
            $('.move', nestable).remove();
            saveTreeBtn.show().prop('disabled', false);
            saveBtnIcon.removeClass('fa-spinner fa-spin fa-check').addClass('fa-save');
        });
        // Position move
        nestable.on('click',  'a.move', function (e) {
            e.preventDefault();
            let move = $(this).data('move');
            let item = $(this).closest('li');
            let input = [{'id':item.data('id'), 'pos':item.attr('data-pos')}];
            let items;

            if (move === 'next') {
                items = item.nextAll();
            } else {
                items = item.prevAll();
            }

            items.each(function (i, e) {
                input.push({'id':$(e).data('id'), 'pos':$(e).attr('data-pos')});
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
    })

    // Position save
    saveTreeBtn.on('click', function () {
        $(this).prop('disabled', true);
        saveBtnIcon.addClass('fa-spinner fa-spin').removeClass('fa-save fa-check');

        if (page) {
            $('.move').remove();
            if (hasMorePages) {
                $('.btn-action').prepend(aTagStart + aTagNext + aTagEnd);
            }
            if (page > 1) {
                $('.btn-action').prepend(aTagStart + aTagPrev + aTagEnd);
            }
        }

        let input = [];
        $.each(nestables, function (i, nestable) {
            input = input.concat(nestable.data('nestable').serialize());
        })

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
            saveBtnIcon.removeClass('fa-spinner fa-spin fa-save').addClass('fa-check');

            if (orderBy) {
                $(input.data).each(function (i, e) {
                    $('#item'+e.id).attr('data-pos', e.pos);
                });
            }

            saveTreeBtn.trigger('positionSaved');
        }, 'json').fail(function (xhr) {
            alert(xhr.responseText);
        }).always(function () {
            saveTreeBtn.delay(400).fadeOut(500);
        });
    });
}
