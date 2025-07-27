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

const notyfObj = new Notyf({
    duration: 2000,
    position: {
        x: 'right',
        y: 'top',
    },
    dismissible: true,
    types: [
        {
            type: 'warning',
            background: 'orange',
            icon: {
                className: 'icon-base fa fa-circle-question',
                tagName: 'i',
                color: 'white'
            }
        },
        {
            type: 'info',
            background: '#00bad1',
            icon: {
                className: 'icon-base fa fa-circle-info',
                tagName: 'i',
                color: 'white'
            }
        }
    ]
});

function notyf(message, type) {
    if (typeof type !== 'string' || ! type instanceof String) {
        type = type !== false ? 'success' : 'error';
    }

    notyfObj.open({type: type, message: message});
}

function textIncrement(times, selector) {
    $(selector ? selector : '.count').each(function (i, e) {
        $(e).text(parseInt($(e).text()) + (times ? times : 1));
    });
}

function textDecrement(times, selector) {
    $(selector ? selector : '.count').each(function (i, e) {
        $(e).text(parseInt($(e).text()) - (times ? times : 1));
    });
}

$(function () {
    // Disable buttons for some period of time after submitting
    $(document).on('submit', 'form', function () {
        $('input[type="submit"], button[type="submit"]', this).prop('disabled', true);

        setTimeout(function (form) {
            $('input[type="submit"], button[type="submit"]', form).prop('disabled', false);
        }, 500, this);
    });

    // Append language query string to url
    $('[data-bs-toggle="tab"][data-lang]').on('click', function () {
        const url = new URL(window.location.href);
        url.searchParams.set('lang', $(this).data('lang'));
        window.history.pushState(null, '', url.toString());
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
                if (res?.redirect) {
                    window.location.href = res.redirect;
                }
                // alert message
                if (res?.result) {
                    notyf(res?.message, res?.result ? res?.result : 'warning');
                } else {
                    return;
                }
                textDecrement();
                form.trigger('deleteFormSuccess', [res]);
                // delete action
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
            },
            error: function (xhr) {
                notyf(
                    xhr?.responseJSON?.message ? xhr.responseJSON.message : xhr.statusText,
                    'error'
                );
            },
            complete: function () {
                btn.prop('disabled', false);
            }
        });
    });

    // Ajax form
    $(document).on('submit', 'form[data-ajax-form="1"]', function (e) {
        e.preventDefault();
        let form = $(this);
        let lang = form.data('lang');
        lang = lang ? lang : '';

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            dataType: 'json',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function (res) {
                form.find('.text-danger').remove();
                // alert message
                notyf(res?.message, res?.result ? res?.result : 'warning');

                form.trigger('ajaxFormSuccess', [res]);

                if (! res?.data || typeof res.data !== 'object') {
                    return;
                }

                $.each(res.data, function (index, element) {
                    let item = $('#' + index + '_inp' + lang, form);

                    if (item.val() !== element) {
                        if (item.is(':checkbox')) {
                            item.prop('checked', Boolean(element));
                        } else {
                            if (item.is(':text')) {
                                item.val(element);
                            }
                            if (item.is('select')) {
                                item.trigger('change');
                            }
                        }
                    }
                });
            },
            error: function (xhr) {
                form.trigger('ajaxFormError', [xhr]);
                form.find('.text-danger').remove();
                if (! xhr?.responseJSON?.errors) {
                    notyf(
                        xhr?.responseJSON?.message ? xhr.responseJSON.message : xhr.statusText,
                        'error'
                    );

                    return;
                }
                $.each(xhr.responseJSON.errors, function (index, element) {
                    let field;
                    let arrayField = index.substring(0, index.indexOf('.'));
                    if (arrayField) {
                        field = $('.' + arrayField + lang, form).first();
                    } else {
                        field = $('#' + index + '_inp' + lang, form);
                    }

                    if (Array.isArray(element)) {
                        element = element.find(Boolean);
                    }

                    let errorIdentifier = field.closest('[data-error]');

                    if (errorIdentifier.length) {
                        let errorElement = '<div class="text-danger">'+element+'</div>';
                        if (errorIdentifier.data('error') === 'prepend') {
                            errorIdentifier.prepend(errorElement);
                        } else {
                            errorIdentifier.append(errorElement);
                        }
                    } else if (field.parent('.input-group').length) {
                        field.parent().after('<div class="text-danger">'+element+'</div>');
                    } else {
                        field.after('<div class="text-danger">'+element+'</div>');
                    }
                });

                let errorField = form.find('.text-danger').first();
                if (errorField) {
                    let errorOffset = errorField.offset();
                    if (errorOffset &&
                        (window.scrollY + 100 > errorOffset.top
                            || (window.scrollY + window.innerHeight) < errorOffset.top)) {
                        $('html, body').animate({
                            scrollTop: errorOffset.top - window.innerHeight / 2
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
            if (! res?.result) {
                notyf(res?.message, 'warning');
                return;
            } else {
                notyf(res.message);
            }
            form.trigger('visibilityResponse', [res])
            let addClass, removeClass;
            if (res?.data) {
                addClass = 'fa-toggle-on';
                removeClass = 'fa-toggle-off';
            } else {
                addClass = 'fa-toggle-off';
                removeClass = 'fa-toggle-on';
            }
            form.find('.fa').removeClass(removeClass).addClass(addClass);
        }, 'json').fail(function (xhr) {
            notyf(xhr.statusText, 'error');
        });
    });
});

// Update sub items data
function updateSubItems(items, url, parentId = 0) {
    if (! url) {
        url = $('#website-url').attr('href');
        if (! url) {
            url = window.location.origin;
        }
    }
    let prevUrl = url;

    items.each(function () {
        $(this).attr('data-parent', parentId);

        let item = $(this).find('a.link');
        let itemSlug = item.data('slug');

        if (itemSlug) {
            url = prevUrl + '/' + itemSlug;

            item.attr('href', url);
        }

        let children = $('> ul', this).children('li');
        if (children.length) {
            updateSubItems(children, url, $(this).data('id'));
        }
    });
}

// Sort array
function sortArray(arr, orderBy) {
    let posList = [];
    $(arr).each(function (i, e) {
        posList[i] = e.pos;
        if (e?.children && Array.isArray(e.children)) {
            e.children = sortArray(e.children, orderBy);
        }
    });
    if (orderBy === 'desc') {
        posList.sort().reverse();
    } else {
        posList.sort();
    }
    $(posList).each(function (i, e) {
        arr[i].pos = e;
    });
    return arr;
}

// Resolve duplicated position
function duplicatedPositionResolver(url, csrfToken, foreignKey) {
    $('.duplicated-position').on('click', function (e) {
        e.preventDefault();
        if (! confirm('Are you sure you want to resolve duplicated position?')) {
            return;
        }

        let input = {
            '_method': 'put', '_token': csrfToken,
            'start_id': $(this).data('id'), 'resolve_duplicated': 1, 'foreign_key': foreignKey
        };

        $.post(url, input, function (res) {
            alert(res?.message);
            window.location.reload();
        }, 'json').fail(function (xhr) {
            notyf(xhr.statusText, 'error');
        });
    });
}

// Sortable
function sortable(url, csrfToken, orderBy, page, foreignKey) {
    duplicatedPositionResolver(url, csrfToken, foreignKey);

    let target = document.getElementById('sortable');
    new Sortable(target, {
        animation: 200,
        handle: '.handle',
        onUpdate: function (event) {
            let input = {'_method': 'put', '_token': csrfToken};
            input['start_id'] = event.item.dataset.id;
            if (event.oldIndex > event.newIndex) {
                input['end_id'] = $(event.item).next().data('id');
            } else {
                input['end_id'] = $(event.item).prev().data('id');
            }

            if (foreignKey) {
                input['foreign_key'] = foreignKey;
            }

            $.post(url, input, function (res) {
                notyf(res?.message, res?.result ? res.result : 'warning');
            }, 'json').fail(function (xhr) {
                notyf(xhr.statusText, 'error');
            });
        }
    });
    // move item to next/prev page
    $(target).on('click',  'a.move', function (e) {
        e.preventDefault();
        let input = {'_method': 'put', '_token': csrfToken};
        let move = $(this).data('move');
        let item = $(this).closest('.item');

        input['start_id'] = item.data('id');

        if (move === 'next') {
            input['end_id'] = item.parent().children().last().data('id');
        } else {
            input['end_id'] = item.parent().children().first().data('id');
        }
        input['move'] = move;
        input['order_by'] = orderBy === 'desc' ? 'desc' : 'asc';

        if (foreignKey) {
            input['foreign_key'] = foreignKey;
        }

        $.post(url, input, function (res) {
            if (! res?.result) {
                notyf(res?.message, 'warning');
                return;
            }
            let href = window.location.href;
            let hrefQueryStart = href.indexOf('?');
            if (hrefQueryStart > 1) {
                href = href.substring(0, hrefQueryStart);
            }
            page = parseInt(page);
            page = move === 'next' ? page + 1 : page - 1;
            window.location.href = href + '?page=' + page;
        }, 'json').fail(function (xhr) {
            notyf(xhr.statusText, 'error');
        });
    });
}

// Nestable
function nestable(url, csrfToken, orderBy, foreignKey, selectors) {
    duplicatedPositionResolver(url, csrfToken);

    let nestableList = [];
    if (Array.isArray(selectors)) {
        $.each(selectors, function (i, selector) {
            nestableList.push($(selector));
        })
    } else {
        nestableList.push($('.uk-nestable'));
    }

    $.each(nestableList, function (i, nestable) {
        let start, prevParentId;
        nestable.on('start.uk.nestable', function (event, ui) {
            start = ui.placeEl.parent().children().index(ui.placeEl);
            prevParentId = ui.placeEl.parent('.uk-nestable-list').closest('.item').data('id');
        });
        nestable.on('change.uk.nestable', function (event, ui, e) {
            let end = e.parent().children().index(e);

            let input = {'_method': 'put', '_token': csrfToken};

            input['start_id'] = e.data('id');

            let parentId = e.parent().closest('.item').data('id');

            if (prevParentId !== e.parent().closest('.item').data('id')) {
                input['parent_id'] = parentId ? parentId : 0;
            }

            if (input['parent_id'] === undefined && start === end) {
                return;
            }

            if (input['parent_id'] !== undefined) {
                input['end_id'] = orderBy === 'desc' ? e.prev().data('id') : e.next().data('id');
            } else {
                input['end_id'] = start > end ? e.next().data('id') : e.prev().data('id');
            }

            input['order_by'] = orderBy === 'desc' ? 'desc' : 'asc';

            if (foreignKey) {
                input['foreign_key'] = foreignKey;
            }

            $.post(url, input, function (res) {
                nestable.trigger('positionUpdated');
                notyf(res?.message, res?.result ? res.result : 'warning');
            }, 'json').fail(function (xhr) {
                notyf(xhr.statusText, 'error');
            });
        });
    })
}
