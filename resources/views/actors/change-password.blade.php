@extends('layouts.profile')
@section('content')
<div id="content-pro"><div class="container custom-gutters-pro" style="max-width:760px;padding-top:40px;padding-bottom:60px;">
    <h1>Secure your contributor account</h1>
    <p>Welcome, {{ $user->fullname() }}. You signed in with a temporary password. Set your own password to open your movie dashboard.</p>
    @if ($errors->any()) <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ route('contributor.password.update') }}">@csrf
        <div class="form-group"><label>Temporary password</label><input type="password" autocomplete="current-password" required name="current_password" class="form-control"></div>
        <div class="form-group"><label>New password (minimum 12 characters)</label><input type="password" autocomplete="new-password" required minlength="12" name="password" class="form-control"></div>
        <div class="form-group"><label>Confirm new password</label><input type="password" autocomplete="new-password" required minlength="12" name="password_confirmation" class="form-control"></div>
        <button type="submit" class="btn btn-primary">Save password and open dashboard</button>
    </form>
</div></div>
@endsection
