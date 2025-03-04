@if (auth('cms')->user()->hasLockScreen())
    <div id="lockscreen">
        <div class="login-container">
            <div class="row">
                <div class="col-sm-7">
                    <form role="form" action="{{cms_route('lockscreen.unlock')}}" method="post" class="lockscreen-form fade-in-effect">
                        <input type="hidden" name="_method" value="put">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="user-thumb">
                            <img src="{{cms_route('cmsUsers.photo', [auth('cms')->id()])}}" width="130" class="img-circle" alt="User Photo">
                        </div>
                        <div class="form-group">
                            <h3>Welcome back, {{auth('cms')->user()->first_name}}!</h3>
                            <p>Enter your password to access the admin.</p>
                            <div class="input-group">
                                <input type="password" class="form-control input-dark{{! $errors->isEmpty() ? ' error' : ''}}" name="password" id="password" placeholder="Password">
                                @if (! $errors->isEmpty())
                                    <label class="error">{{$errors->first()}}</label>
                                @endif
                                <span class="input-group-btn">
                                <button type="submit" class="btn btn-secondary">{{trans('auth.login')}}</button>
                            </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(function() {
                // Reveal a Login form
                setTimeout(function(){ $(".fade-in-effect").addClass('in'); }, 1);

                // Set Form focus
                $(".lockscreen-form .form-group:has(.form-control):first .form-control").focus();

                @if (! session()->has('includeLockscreen'))
                // Form validation and AJAX request
                $(".lockscreen-form").on('submit', function(e) {
                    e.preventDefault();

                    let password = $('#password', this);

                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        dataType: 'json',
                        data: $(this).serializeArray(),
                        success: function(data) {
                            if (data.result) {
                                @if ($cmsSettings->get('lockscreen'))
                                activateLockscreenTimer()
                                @endif

                                $('body > #lockscreen').fadeOut(400, function() {
                                    $(this).remove();
                                    $('body').removeClass('lockscreen-page');
                                });
                            } else {
                                password.addClass('error').after('<label class="error">'+data.message+'</label>');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status !== 429) {
                                alert(xhr.responseText);
                            } else {
                                alert(xhr.responseText + ' Retry after ' + xhr.getResponseHeader('Retry-after') + ' seconds.');
                            }
                        }
                    });

                    password.val('').siblings('label').remove();
                });
                @endif
            });
        </script>
    </div>
@endif
