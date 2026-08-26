<?php

namespace App\Http\Controllers\Admin\MobileApi;

use App\Http\Controllers\Controller;
use App\MobileApiLog;
use Illuminate\Http\Request;

class MobileApiLogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = MobileApiLog::with('user')->latest();

        if ($request->filled('method')) $query->where('method', strtoupper($request->method));
        if ($request->filled('path')) $query->where('path', 'like', '%'.$request->path.'%');
        if ($request->filled('request_id')) $query->where('request_id', 'like', $request->request_id.'%');
        if ($request->filled('user_id')) $query->where('user_id', $request->user_id);
        if ($request->filled('platform')) $query->where('platform', $request->platform);
        if ($request->filled('result')) {
            if ($request->result === 'success') $query->whereBetween('status', [200, 399]);
            if ($request->result === 'client_error') $query->whereBetween('status', [400, 499]);
            if ($request->result === 'server_error') $query->where('status', '>=', 500);
            if ($request->result === 'slow') $query->where('duration_ms', '>=', 2000);
        }
        if ($request->filled('from')) $query->whereDate('created_at', '>=', $request->from);
        if ($request->filled('to')) $query->whereDate('created_at', '<=', $request->to);

        $since = now()->subDay();
        $stats = [
            'total' => MobileApiLog::where('created_at', '>=', $since)->count(),
            'errors' => MobileApiLog::where('created_at', '>=', $since)->where('status', '>=', 400)->count(),
            'server_errors' => MobileApiLog::where('created_at', '>=', $since)->where('status', '>=', 500)->count(),
            'slow' => MobileApiLog::where('created_at', '>=', $since)->where('duration_ms', '>=', 2000)->count(),
            'average_ms' => round((float) MobileApiLog::where('created_at', '>=', $since)->avg('duration_ms'), 0),
        ];

        $logs = $query->paginate(50)->appends($request->query());

        return view('admin.mobile_api.index', compact('logs', 'stats'));
    }

    public function show($id)
    {
        $log = MobileApiLog::with('user')->findOrFail($id);

        return view('admin.mobile_api.show', compact('log'));
    }
}
