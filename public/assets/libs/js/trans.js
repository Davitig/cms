$(function () {
    // f2+click on data-trans element to get the translation form
    let f2 = false;
    $(document).keydown(function (e) {
        if (f2) return;
        if (e.keyCode === 113) {
            f2 = true;
        }
    });
    $(document).keyup(function () {
        f2 = false;
    });
    $(document).on('click', '[data-trans]', function (e) {
        if (f2 || e.f2 === true) {
            e.preventDefault();
            let trans = $('#trans-modal-form');
            let url = trans.data('trans-url');
            if (url && url !== '/#not_found') {
                $.get(trans.data('trans-url'), {'code':$(this).data('trans'), '_token':trans.data('token')}, function (data) {
                    $('body').append($(data).hide().fadeIn(200));
                }).fail(function (xhr) {
                    alert(xhr.responseText);
                });
            }
        }
    });

    let style = document.createElement('style');
    style.innerHTML += `
.trans-modal-bg {position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: #000; opacity: .2; z-index: 1000;}
.trans-modal .clearfix:after {content: ""; clear: both; display: table;}
.trans-modal {position: fixed; left: 0; top: 0; right: 0; width: 100%; height: 100%; overflow-x: hidden; overflow-y: auto; z-index: 1100;}
.trans-dialog {position: relative; width: 600px; margin: 30px auto; padding: 20px; color: #666; background: #fff; font-size: 14px; font-family: Arial, Helvetica, sans-serif;}
.trans-header {margin-bottom: 20px;}
.trans-header .trans-close-btn {
    border: 0;
    background: 0;
    float: right;
    font-size: 21px;
    font-weight: 700;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    filter: alpha(opacity=20);
    opacity: .2;
}
.trans-header .trans-close-btn:hover {opacity: .4;}
.trans-title {font-size: 24px;}
.trans-nav .trans-nav-item {float: left; list-style: none; padding: 0 3px; border-bottom: 1px solid #e8e8e8;}
.trans-nav .trans-nav-item.active {border-bottom: 1px solid #fff;}
.trans-nav .trans-nav-item a {display: block; padding: 8px 15px; color: #555; background: #f4f4f4; border: 1px solid #e8e8e8; border-bottom: 0;}
.trans-nav .trans-nav-item.active a {background: #fff;}
.trans-nav .trans-nav-item a:hover {color: #337ab7; background: #fff;}
.trans-nav .trans-nav-item a:focus {text-decoration: none; outline: none;}
.trans-tab-content .trans-tab-pane {display: none;}
.trans-tab-content .trans-tab-pane.active {display: block;}
.trans-modal .trans-form-group {padding-bottom: 20px;}
.trans-modal .trans-control-label {float: left; font-weight: 700;}
.trans-modal .trans-form-control:focus {outline: none;}
.trans-modal .trans-form-control[readonly] {background: #f4f4f4; cursor: default;}
.trans-modal .trans-form-control {
    border: 1px solid #e2eaef;
    border-radius: 3px;
    -webkit-box-shadow: 0 0;
    box-shadow: 0 0;
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
}
.trans-modal .desc {color: #888;}
.trans-modal .trans-modal-footer {text-align: right;}
.trans-modal .trans-btn {
    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
}
.trans-modal .trans-btn-default {color: #333; background-color: #fff; border-color: #ccc;}
.trans-modal .trans-btn-primary {color: #fff; background-color: #337ab7; border-color: #2e6da4;}
.trans-modal .trans-btn-default:hover {background-color: #e6e6e6; border-color: #adadad;}
.trans-modal .trans-btn-primary:hover {background-color: #204d74; border-color: #122b40;}
.trans-modal .trans-text-danger {color: red; padding-bottom: 2px;}
.trans-modal .trans-text-success {color: #3c763d;}
`;
    document.head.appendChild(style);
});
