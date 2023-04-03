@extends('admin.layouts.appAuth')

@section('authContent')
    <div class="ibox-content ibox-content_login">
        @if (session('success'))
            <div class="alert alert-success" role="alert"> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert"> {{ session('error') }}
            </div>
        @endif
        <img src="{{ url(\Settings::get('application_logo')) }}" style="height: auto;width: 20%;">
        <h2 class="font-bold">OTP Login </h2>
        <div class="row">
            <div class="col-lg-12">
                <form method="POST" action="{{ url('admin/login') }}">
                    @csrf
                    <div class="form-group">
                        <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror"
                            name="otp" value="{{ old('otp') }}" required autocomplete="otp" autofocus
                            placeholder="Enter OTP">

                        @error('otp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('authStyles')
@endsection
@section('authScripts')
@endsection
