<div class="form-group{{($error = $errors->first('role')) ? ' validate-has-error' : '' }}">
    <label class="col-sm-2 control-label required">Role:</label>
    <div class="col-sm-10">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-header"></i></span>
            {{ html()->text('role')->id('role')->class('form-control') }}
        </div>
        @if ($error)
            <span class="text-danger">{{$error}}</span>
        @endif
    </div>
</div>

<div class="form-group-separator"></div>

<div class="row">
    <div class="col-lg-6">
        <div class="form-group{{($error = $errors->first('full_access')) ? ' validate-has-error' : '' }}">
            <label class="col-lg-4 col-sm-2 control-label required">Full Access:</label>
            <div class="col-lg-8 col-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-ellipsis-h"></i></span>
                    {{ html()->select('full_access', ['' => '-- Full Access --', 0 => 'No', 1 => 'Yes'])
                    ->id('full_access' . $current->language)->class('form-control select')->data('lang', 1) }}
                </div>
                @if ($error)
                    <span class="text-danger">{{$error}}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <div class="col-sm-10 btn-action pull-right">
        <button type="submit" class="btn btn-secondary btn-icon-standalone" title="{{ $submit }}">
            <i class="fa fa-{{ $icon }}"></i>
            <span>{{ $submit }}</span>
        </button>
        <a href="{{ cms_route('permissions.index', ['role' => $current->id]) }}" id="permissions-btn"
           class="btn btn-orange btn-icon-standalone{{ ! $current->id || $current->full_access ? ' hidden' : '' }}" title="Permissions">
            <i class="{{icon_type('permissions')}}"></i>
            <span>Permissions</span>
        </a>
        <a href="{{ cms_route('cmsUserRoles.index') }}" class="btn btn-blue btn-icon-standalone" title="{{ trans('general.back') }}">
            <i class="fa fa-arrow-left"></i>
            <span>{{ trans('general.back') }}</span>
        </a>
    </div>
</div>
