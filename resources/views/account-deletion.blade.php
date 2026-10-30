<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="no-referrer">
    <title>Delete your account · NollyFlix</title>
    <style>
        *{box-sizing:border-box} body{margin:0;background:#0a0a0a;color:#eee;font:16px/1.6 system-ui,sans-serif} main{max-width:730px;margin:0 auto;padding:40px 22px 75px} .brand{color:#e50914;font-weight:900;letter-spacing:3px;text-decoration:none;font-size:25px} h1{line-height:1.2;font-size:32px;margin:30px 0 15px} h2{font-size:19px;margin-top:25px} p,li{color:#bbb} a{color:#fff} .panel{padding:24px;background:#171717;border:1px solid #333;border-radius:12px;margin-top:24px} label{display:block;font-weight:650} input[type=email]{width:100%;padding:13px;margin:8px 0 16px;background:#0a0a0a;border:1px solid #777;border-radius:5px;color:#fff;font:inherit} button{padding:13px 18px;background:#e50914;color:white;border:0;border-radius:5px;font:inherit;font-weight:700;cursor:pointer} input[type=checkbox]{margin-right:8px;width:18px;height:18px} .notice{padding:16px;border:1px solid #36885c;background:#10271a;border-radius:8px;color:#d8f5e4} .error{color:#ffb5b5} .muted{font-size:14px} :focus-visible{outline:3px solid #fff;outline-offset:3px}
    </style>
</head>
<body><main>
    <a class="brand" href="/">NOLLYFLIX</a>
    <h1>Delete your NollyFlix account</h1>
    <p>Request deletion of your account and associated personal data for both the NollyFlix website and mobile app. You do not need to install or sign in to the app to make a request.</p>
    @if(session('status')) <div class="notice" role="status">{{ session('status') }}</div> @endif
    @if($errors->any()) <div class="error" role="alert">{{ $errors->first() }}</div> @endif
    <h2>What happens next?</h2>
    <ol>
        <li>Enter the email address used for your NollyFlix account.</li>
        <li>Open our verification email and confirm your deletion request. The link expires in one hour.</li>
        <li>Our support team processes the verified request. Submitting a request does not immediately delete your account.</li>
    </ol>
    <h2>Before you continue</h2>
    <p>When deletion is completed, your profile, watchlist, viewing history and notification registrations will be removed or anonymized, and you will lose access to purchased movies and active rentals on the app and website.</p>
    <p>Transaction records and records required for legal, fraud-prevention or dispute-resolution obligations may need to be retained. Support will explain any applicable retention and its duration when handling your request. See our <a href="/pages/privacy-policy">Privacy Policy</a>.</p>
    <div class="panel">
        @if(!empty($invalidLink))
            <h2>That confirmation link is invalid or expired</h2>
            <p>It may also have stopped working because your email address or password changed.</p>
            <a href="{{ route('account-deletion.show') }}">Request a new confirmation link</a>
        @elseif(!empty($confirmationUrl))
            <h2>Confirm your deletion request</h2>
            <form action="{{ $confirmationUrl }}" method="post">
                @csrf
                <label><input type="checkbox" name="acknowledge" value="1" required>I understand that completed deletion removes access to my NollyFlix purchases, rentals and associated personal data.</label>
                <p><button type="submit">Confirm deletion request</button></p>
            </form>
        @else
            <form action="{{ route('account-deletion.store') }}" method="post">
                @csrf
                <label for="email">Account email address</label>
                <input id="email" name="email" type="email" autocomplete="email" maxlength="128" value="{{ old('email') }}" required>
                <button type="submit">Email me a confirmation link</button>
            </form>
        @endif
    </div>
    <p class="muted">Cannot access your account email, or need an update? Contact <a href="mailto:support@nollyflix.tv">support@nollyflix.tv</a>. We may need additional information to verify ownership. Never send us your password or card details.</p>
</main></body>
</html>
