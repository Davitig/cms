<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="cms">
    <meta name="author" content="Davit Gvritishvili <gvritishvili.davit@gmail.com>">
    <meta name="robots" content="noindex, nofollow">
    <meta name="version" content="{{cms_config('version')}}">
    <title>CMS - Login</title>
    <link rel="shortcut icon" href="{{ asset('assets/libs/images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/xenon-core.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/xenon-forms.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/xenon-components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/css/xenon-skins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/js/icheck/skins/all.css') }}">
    <script src="{{ asset('assets/libs/js/jquery-1.11.3.min.js') }}"></script>
</head>
<body class="page-body login-page">
    <div class="login-container">
        <div class="row">
            <div class="col-sm-6">
            @if ($error = $errors->first('email'))
                <div class="errors-container">
                    <span class="text-danger">{{$error}}</span>
                </div>
            @endif
                <form action="{{cms_route('login.post')}}" method="POST" role="form" id="login" class="login-form fade-in-effect">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="login-header">
                        <p>{{trans('auth.login_msg')}}</p>
                    </div>
                    <div class="form-group">
                        <label class="control-label">E-Mail</label>
                        <input type="text" class="form-control input-dark" name="email" id="email" autocomplete="off" tabindex="1" autofocus>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Password</label>
                        <input type="password" class="form-control input-dark" name="password" id="password" autocomplete="off" tabindex="2">
                    </div>
                    <div class="form-group">
                        <ul class="icheck-list">
                            <li>
                                <input tabindex="3" type="checkbox" name="remember" class="icheck" id="remember">
                                <label for="remember">{{trans('auth.remember_me')}}</label>
                            </li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-secondary btn-block text-left">
                            {{trans('auth.login')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script src="{{ asset('assets/libs/js/jquery-validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/libs/js/icheck/icheck.min.js') }}"></script>
<script type="text/javascript">
$(function() {
    // Reveal Login form
    setTimeout(function(){ $(".fade-in-effect").addClass('in'); }, 1);

    // Style Checkbox
    $('input.icheck').iCheck({
        checkboxClass: 'icheckbox_square-blue'
    });

    // Login Form Label Focusing
    $(".login-form .form-group:has(label)").each(function(i, e) {
        let $this = $(e),
            $label = $this.find('label'),
            $input = $this.find('input');

        $input.on('focus', function() {
            $this.addClass('is-focused');
        });

        $input.on('keydown', function() {
            $this.addClass('is-focused');
        });

        $input.on('blur', function() {
            $this.removeClass('is-focused');

            if($input.val().trim().length > 0) {
                $this.addClass('is-focused');
            }
        });

        $label.on('click', function() {
            $input.focus();
        });

        if($input.val().trim().length > 0) {
            $this.addClass('is-focused');
        }
    });

    // Validation
    $("form#login").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            }
        },
        messages: {
            email: {
                required: '{{trans('auth.required.email')}}',
                email: '{{trans('auth.invalid.email')}}'
            },
            password: {
                required: '{{trans('auth.required.password')}}'
            }
        },
    });
})
</script>
</body>
</html>
