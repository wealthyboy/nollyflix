@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/nollyflix-xd-register.css') }}?v=202611250915">
@endsection

@section('content')
<!-- NOLLYFLIX-REGISTER-UI-PATCH-20261125 -->
<div class="background-image nfx-auth-register">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div id="membership-plan-background">
                    <div class="membership-width-container">
                        <div class="membership-required-container">
                            <div class="registration-login-container">
                                <form method="POST" action="/register">
                                    @csrf
                                    <div class="aligncenter"><h1>Sign Up</h1></div>

                                    <div class="form-group">
                                        <label class="sr-only" for="first_name">First name</label>
                                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" required autocomplete="given-name" autofocus>
                                        @error('first_name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="last_name">Last name</label>
                                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}" required autocomplete="family-name">
                                        @error('last_name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="email">Email</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ old('email') }}" required autocomplete="email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="password">Password</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="new-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only" for="password-confirm">Confirm password</label>
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                                    </div>
                                    <div class="form-group aligncenter">
                                        <button type="submit" class="btn">Sign Up</button>
                                    </div>
                                    <div class="aligncenter">
                                        <a class="not-a-member-pro" href="{{ route('login') }}">Already have an account? <span>Login</span></a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
