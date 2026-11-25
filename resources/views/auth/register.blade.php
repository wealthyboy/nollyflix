@extends('layouts.app')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/nollyflix-xd-register.css') }}?v=1">
@endsection

@section('content')
<main class="nfx-register-page">
    <div class="nfx-register-shell">
        <section class="nfx-register-intro" aria-label="Discover NollyFlix">
            <div class="nfx-register-intro-inner">
                <div class="nfx-register-eyebrow"><span aria-hidden="true"></span> HOME OF THE BIGGEST NOLLYWOOD BLOCKBUSTERS</div>
                <h1>Discover more with <span>NollyFlix.</span></h1>
                <p>Create an account to explore Nollywood stories and enjoy your favourite movies on your devices.</p>
                <div class="nfx-register-intro-line" aria-hidden="true"></div>
            </div>
        </section>

        <section class="nfx-register-panel" aria-labelledby="nfx-register-heading">
            <div class="nfx-register-form-wrap">
                <div class="nfx-register-kicker">JOIN NOLLYFLIX</div>
                <h2 id="nfx-register-heading">Create your account</h2>
                <p class="nfx-register-subheading">Enter your details to get started.</p>
                <form method="POST" action="/register" class="nfx-register-form">
                    @csrf
                    <div class="form-group">
                        <label for="first_name">First name</label>
                        <input id="first_name" type="text" class="form-control @error('name') is-invalid @enderror" name="first_name" placeholder="Enter your first name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus>
                        @error('name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last name</label>
                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" placeholder="Enter your last name" value="{{ old('name') }}" required autocomplete="name">
                        @error('name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Enter your email address" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Create a password" required autocomplete="new-password">
                        @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password-confirm">Confirm password</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm your password" required autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn nfx-register-submit">Sign Up</button>
                    <div class="nfx-register-signin">Already have an account? <a href="{{ route('login') }}">Sign In</a></div>
                </form>
            </div>
        </section>
    </div>
</main>
@endsection
