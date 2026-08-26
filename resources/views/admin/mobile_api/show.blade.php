@extends('admin.layouts.app')

@section('pagespecificstyles')
<style>
    .detail-title { font-weight: 800; }
    .detail-grid dt { color: #777; font-size: 11px; text-transform: uppercase; letter-spacing: .05em; margin-top: 14px; }
    .detail-grid dd { font-size: 15px; font-weight: 600; word-break: break-word; }
    .payload { background: #111; color: #e8e8e8; border-radius: 8px; padding: 18px; max-height: 480px; overflow: auto; white-space: pre-wrap; word-break: break-word; }
    .error-box { background: #ffebee; color: #b71c1c; border-left: 4px solid #c62828; padding: 16px; margin-bottom: 20px; }
</style>
@endsection

@section('content')
<div class="row"><div class="col-md-12"><a href="{{ route('admin.mobile-api.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> All requests</a></div></div>
<div class="row">
    <div class="col-md-4"><div class="card"><div class="card-content detail-grid">
        <h3 class="detail-title">Request details</h3>
        <dl>
            <dt>Request ID</dt><dd><code>{{ $log->request_id }}</code></dd>
            <dt>Time</dt><dd>{{ optional($log->created_at)->format('d M Y, H:i:s') }}</dd>
            <dt>Request</dt><dd>{{ $log->method }} /{{ $log->path }}</dd>
            <dt>Status / duration</dt><dd>{{ $log->status ?: 'Exception' }} · {{ number_format((float) $log->duration_ms, 2) }} ms</dd>
            <dt>Customer</dt><dd>{{ optional($log->user)->email ?: ($log->user_id ? 'User #'.$log->user_id : 'Guest') }}</dd>
            <dt>App</dt><dd>{{ $log->platform ?: 'Unknown' }} {{ $log->app_version ? 'v'.$log->app_version : '' }} {{ $log->app_build ? '(build '.$log->app_build.')' : '' }}</dd>
            <dt>IP</dt><dd>{{ $log->ip ?: 'Unknown' }}</dd>
            <dt>User agent</dt><dd>{{ $log->user_agent ?: 'Unknown' }}</dd>
        </dl>
    </div></div></div>
    <div class="col-md-8">
        @if($log->error || $log->exception)<div class="error-box"><strong>{{ $log->exception ?: 'Request error' }}</strong><br>{{ $log->error }}</div>@endif
        <div class="card"><div class="card-content"><h4 class="detail-title">Request payload</h4><pre class="payload">{{ json_encode($log->request_payload ?: new stdClass, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre></div></div>
        <div class="card"><div class="card-content"><h4 class="detail-title">Response payload</h4><pre class="payload">{{ json_encode($log->response_payload ?: new stdClass, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre></div></div>
    </div>
</div>
@endsection
