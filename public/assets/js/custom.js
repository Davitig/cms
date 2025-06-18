// stick the footer at the bottom of the page
$(window).on('load resize', function() {
    let windowHeight = $(window).outerHeight();
    let rootHeight = $('#root').outerHeight();
    let footer = $('#footer');
    let footerHeight = footer.outerHeight();
    let position = 0;

    if (windowHeight > (rootHeight + footerHeight)) {
        position = windowHeight - rootHeight - (footerHeight * 2);
    }

    footer.css('position', 'relative').css('top', position);
});
