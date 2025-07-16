@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Web Settings</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header fs-5">Web Settings</div>
        <div class="card-body">
            {{ html()->form('put', cms_route('web_settings.update'))->attribute('novalidate')->open() }}
            <div class="row g-6 mb-6">
                <div>
                    <label for="email_inp" class="form-label">E-mail</label>
                    {{ html()->text('email', $web_settings->get('email'))->id('email_inp')->class('form-control') }}
                </div>
                <div>
                    <label for="phone_inp" class="form-label">Phone</label>
                    {{ html()->text('phone', $web_settings->get('phone'))->id('phone_inp')->class('form-control') }}
                </div>
                <div>
                    <label for="address_inp" class="form-label">Address</label>
                    {{ html()->text('address', $web_settings->get('address'))->id('address_inp')->class('form-control') }}
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection
