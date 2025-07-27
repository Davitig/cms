<!-- Header -->
<div class="card mb-6">
    <div class="user-profile-header-banner">
        <img src="{{ cms_route('cms_users.cover', [$current->id]) }}" alt="Banner image" class="rounded-top user-cover"
        data-default="{{ asset('assets/img/pages/profile-banner.png') }}">
    </div>
    @if ($allowUserProfile ?? true)
        <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-5">
            <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                <img src="{{ cms_route('cms_users.photo', [$current->id]) }}" alt="user image"
                     class="d-block h-auto ms-0 ms-sm-6 rounded user-profile-img bg-white" />
            </div>
            <div class="flex-grow-1 mt-3 mt-lg-5">
                <div
                    class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
                    <div class="user-profile-info">
                        <h4 class="mb-2 mt-lg-6">{{$current->first_name}} {{$current->last_name}}</h4>
                        <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 my-2">
                            @if ($current->created_at)
                                <li class="list-inline-item d-flex gap-2 align-items-center">
                                    <i class="icon-base fa-regular fa-calendar-check icon-md"></i>
                                    <span class="fw-medium">Joined {{ $current->created_at->format('F Y') }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if ($allowCoverUpload ?? false)
        <div class="card-body">
            <div class="button-wrapper d-flex justify-content-end align-items-center gap-3">
                {{ html()->modelForm($current, 'post', cms_route('cms_users.image.store', [$current->id]))
                ->id('upload-cover')->class('d-flex align-items-center gap-3')
                ->data('ajax-form', 1)->data('error', 'prepend')->acceptsFiles()->open() }}
                {{ html()->hidden('image_type', 'cover') }}
                @error('cover')
                <div class="text-danger">{{ $message }}</div>
                @enderror
                <span>Max size of 2MB</span>
                <label for="cover_inp" class="btn btn-primary" tabindex="0">
                    <div class="loading-cover spinner-border spinner-border-sm text-white me-1 d-none"></div>
                    <span class="d-none d-sm-block">Upload new cover</span>
                    <i class="icon-base fa fa-upload d-block d-sm-none"></i>
                    <input type="file" name="cover" id="cover_inp" class="account-file-input image_inp_type"
                           data-type="cover" hidden>
                </label>
                {{ html()->form()->close() }}
                {{ html()->modelForm($current, 'delete', cms_route('cms_users.image.destroy', [$current->id]))
                ->class('delete-image')->data('type', 'cover')->open() }}
                {{ html()->hidden('image_type', 'cover') }}
                <button type="submit" class="btn btn-label-secondary account-image-reset">
                    <i class="icon-base fa fa-trash-arrow-up d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Remove</span>
                </button>
                {{ html()->form()->close() }}
            </div>
        </div>
    @endif
</div>
<!--/ Header -->
@push('head')
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}">
@endpush

