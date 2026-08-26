@extends('admin.layouts.app')

@section('pagespecificstyles')
<style>
    .monitor-title { font-weight: 700; margin: 0; }
    .monitor-subtitle { color: #777; margin-top: 6px; }
    .monitor-stat { min-height: 112px; border-radius: 10px; }
    .monitor-stat .number { font-size: 28px; font-weight: 800; margin: 8px 0 0; }
    .monitor-stat .label-text { color: #777; font-weight: 600; text-transform: uppercase; font-size: 11px; letter-spacing: .06em; }
    .monitor-filters { padding: 18px; margin-bottom: 20px; background: #fafafa; border: 1px solid #eee; border-radius: 10px; }
    .monitor-filters .form-control { background: #fff; border: 1px solid #ddd; padding: 8px 10px; }
    .monitor-table th { font-weight: 800 !important; color: #222 !important; white-space: nowrap; }
    .monitor-table td { vertical-align: middle !important; }
    .monitor-path { max-width: 310px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-family: monospace; }
    .status-pill { display: inline-block; min-width: 52px; padding: 4px 8px; border-radius: 12px; color: #fff; font-weight: 800; text-align: center; }
    .status-ok { background: #2e7d32; } .status-warn { background: #ef6c00; } .status-bad { background: #c62828; } .status-none { background: #616161; }
    .method { font-weight: 800; font-family: monospace; }
    .empty-monitor { padding: 50px 15px !important; text-align: center; color: #777; }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-content">
                <h3 class="monitor-title">Mobile API Monitor</h3>
                <p class="monitor-subtitle">Every app request, response status, duration and backend failure. Statistics cover the last 24 hours.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @foreach([
        ['Requests', $stats['total'], ''],
        ['Errors', $stats['errors'], 'text-warning'],
        ['Server errors', $stats['server_errors'], 'text-danger'],
        ['Slow (2s+)', $stats['slow'], 'text-warning'],
        ['Average', $stats['average_ms'].' ms', '']
    ] as $stat)
    <div class="col-md-{{ $loop->last ? '4' : '2' }}">
        <div class="card monitor-stat"><div class="card-content">
            <div class="label-text">{{ $stat[0] }}</div>
            <div class="number {{ $stat[2] }}">{{ $stat[1] }}</div>
        </div></div>
    </div>
    @endforeach
</div>

<div class="row"><div class="col-md-12"><div class="card"><div class="card-content">
    <form method="get" action="{{ route('admin.mobile-api.index') }}" class="monitor-filters">
        <div class="row">
            <div class="col-md-2"><select name="method" class="form-control"><option value="">All methods</option>@foreach(['GET','POST','PUT','PATCH','DELETE'] as $method)<option value="{{ $method }}" {{ request('method') === $method ? 'selected' : '' }}>{{ $method }}</option>@endforeach</select></div>
            <div class="col-md-2"><select name="result" class="form-control"><option value="">All results</option><option value="success" {{ request('result') === 'success' ? 'selected' : '' }}>Successful</option><option value="client_error" {{ request('result') === 'client_error' ? 'selected' : '' }}>Client errors (4xx)</option><option value="server_error" {{ request('result') === 'server_error' ? 'selected' : '' }}>Server errors (5xx)</option><option value="slow" {{ request('result') === 'slow' ? 'selected' : '' }}>Slow (2s+)</option></select></div>
            <div class="col-md-3"><input name="path" value="{{ request('path') }}" class="form-control" placeholder="Endpoint, e.g. video/12/play"></div>
            <div class="col-md-2"><input name="request_id" value="{{ request('request_id') }}" class="form-control" placeholder="Request ID"></div>
            <div class="col-md-1"><select name="platform" class="form-control"><option value="">Device</option><option value="android" {{ request('platform') === 'android' ? 'selected' : '' }}>Android</option><option value="ios" {{ request('platform') === 'ios' ? 'selected' : '' }}>iOS</option></select></div>
            <div class="col-md-2"><button class="btn btn-rose btn-block">Filter</button></div>
        </div>
        <div class="row">
            <div class="col-md-2"><input type="number" name="user_id" value="{{ request('user_id') }}" class="form-control" placeholder="User ID"></div>
            <div class="col-md-2"><input type="date" name="from" value="{{ request('from') }}" class="form-control"></div>
            <div class="col-md-2"><input type="date" name="to" value="{{ request('to') }}" class="form-control"></div>
            <div class="col-md-2"><a href="{{ route('admin.mobile-api.index') }}" class="btn btn-default btn-block">Reset</a></div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover monitor-table">
            <thead><tr><th>Time</th><th>Status</th><th>Method</th><th>Endpoint</th><th>User</th><th>Device</th><th>Duration</th><th>Request ID</th><th></th></tr></thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ optional($log->created_at)->format('d M H:i:s') }}</td>
                    <td><span class="status-pill {{ !$log->status ? 'status-none' : ($log->status >= 500 ? 'status-bad' : ($log->status >= 400 ? 'status-warn' : 'status-ok')) }}">{{ $log->status ?: 'ERR' }}</span></td>
                    <td class="method">{{ $log->method }}</td>
                    <td class="monitor-path" title="{{ $log->path }}">/{{ $log->path }}</td>
                    <td>{{ optional($log->user)->email ?: ($log->user_id ? '#'.$log->user_id : 'Guest') }}</td>
                    <td>{{ $log->platform ?: 'Unknown' }}<br><small>{{ $log->app_version ? 'v'.$log->app_version : '' }}</small></td>
                    <td class="{{ $log->duration_ms >= 2000 ? 'text-danger' : '' }}"><strong>{{ number_format((float) $log->duration_ms, 0) }} ms</strong></td>
                    <td><code>{{ substr($log->request_id, 0, 8) }}</code></td>
                    <td><a href="{{ route('admin.mobile-api.show', $log->id) }}" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a></td>
                </tr>
            @empty
                <tr><td colspan="9" class="empty-monitor">No mobile requests match these filters. Deploy and run the migration to begin collecting data.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="text-center">{{ $logs->links() }}</div>
</div></div></div></div>
@endsection
