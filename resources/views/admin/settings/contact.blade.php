@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ cms_route('settings.index') }}">Settings</a>
            </li>
            <li class="breadcrumb-item active">Contact</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header fs-5">Contact Settings</div>
        <div class="card-body">
            {{ html()->modelForm($current, 'post', cms_route('settings.contact.save'))->data('ajax-form', 1)
            ->attribute('novalidate')->open() }}
            <div class="row g-6 mb-6">
                <div>
                    <label for="email_inp" class="form-label">E-mail</label>
                    {{ html()->text('email')->id('email_inp')->class('form-control') }}
                    @error('email')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="phone_inp" class="form-label">Phone</label>
                    {{ html()->text('phone')->id('phone_inp')->class('form-control') }}
                    @error('phone')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="address_inp" class="form-label">Address</label>
                    {{ html()->text('address')->id('address_inp')->class('form-control') }}
                    @error('address')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection
