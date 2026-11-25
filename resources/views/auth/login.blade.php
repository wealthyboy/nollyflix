@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/nollyflix-xd-login.css') }}?v=1">
@endsection

@section('content')
<main class="nfx-login-page">
    <div class="nfx-login-shell">
        <section class="nfx-login-intro" aria-label="Welcome to NollyFlix">
            <div class="nfx-login-intro-inner">
                <div class="nfx-login-eyebrow"><span></span> HOME OF THE BIGGEST NOLLYWOOD BLOCKBUSTERS</div>
                <h1>Welcome back to <span>NollyFlix.</span></h1>
                <p>Discover unforgettable Nollywood stories. Sign in to continue enjoying your favourite films.</p>
                <div class="nfx-login-intro-line" aria-hidden="true"></div>
            </div>
        </section>

        <section class="nfx-login-panel" aria-labelledby="nfx-login-heading">
            <div class="nfx-login-form-wrap">
                <div class="nfx-login-kicker">YOUR ACCOUNT</div>
                <h2 id="nfx-login-heading">Sign in to NollyFlix</h2>
                <p class="nfx-login-subheading">Enter your details to access your account.</p>
                <form method="POST" action="{{ route('login') }}" class="nfx-login-form">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter your password" required autocomplete="current-password">
                        @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="nfx-login-help-row">
                        <div class="checkbox-remember-pro">
                            <input type="checkbox" id="checkbox-remember"><label for="checkbox-remember">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}">Forgot your password?</a>
                    </div>
                    <button type="submit" class="btn nfx-login-submit">Sign In</button>
                    <div class="nfx-login-signup">Don't have an account? <a href="{{ route('register') }}">Sign Up Now!</a></div>
                </form>
            </div>
        </section>
    </div>
</main>
@endsection
