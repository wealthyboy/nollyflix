@extends('admin.layouts.app')

@section('content')
<h3>Account deletion requests</h3>
<p>These requests have been confirmed by email. A request is not proof of completed deletion.</p>
<div class="alert alert-warning" style="background:#fff3cd;color:#533f03;">Process each request using the account-deletion runbook. Remove associated personal data and revoke access; deleting only the user row or marking an account inactive is not sufficient. Do not delete an administrator or content-owner account without reviewing ownership and legal obligations.</div>
<div class="card"><div class="card-content table-responsive">
    <table class="table">
        <thead><tr><th>Reference</th><th>Account</th><th>Verified on</th><th>Review status</th></tr></thead>
        <tbody>
        @forelse($requests as $item)
            <tr>
                <td>{{ $item->reference }}</td>
                <td>@if($item->user) {{ $item->user->email }}<br><small>User #{{ $item->user_id }} · {{ $item->user->type }}</small> @else Account record removed @endif</td>
                <td>{{ $item->confirmed_at->format('d M Y H:i') }}</td>
                <td>{{ $item->user ? 'Awaiting processing' : 'Verify associated-data cleanup' }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No verified deletion requests yet.</td></tr>
        @endforelse
        </tbody>
    </table>
    {{ $requests->links() }}
</div></div>
@endsection
