<?php

namespace App\Http\Controllers\Api\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PrivateUserResource;
use App\PushDevice;

class NotificationsController extends Controller
{
    
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'notificationStatus' => 'required|boolean',
            'pushToken' => 'nullable|string|max:4096',
            'platform' => 'nullable|in:ios,android,web',
            'deviceName' => 'nullable|string|max:120',
            'appVersion' => 'nullable|string|max:40',
        ]);

        $user = $request->user();
        $enabled = (bool) $request->input('notificationStatus');
        $token = trim((string) $request->input('pushToken', ''));

        $result = $user->update([
            'allow_notifications' => $enabled ? 1 : 0,
            'push_token' => $token ?: null,
        ]);

        if ($token !== '') {
            PushDevice::updateOrCreate(
                ['token_hash' => hash('sha256', $token)],
                [
                    'user_id' => $user->id,
                    'token' => $token,
                    'platform' => $request->input('platform'),
                    'device_name' => $request->input('deviceName'),
                    'app_version' => $request->input('appVersion'),
                    'active' => $enabled,
                    'last_seen_at' => now(),
                ]
            );
        } elseif (! $enabled) {
            $user->pushDevices()->update(['active' => false]);
        }

        if ($result){
            return new PrivateUserResource($request->user());
        }

        return response()->json([
            'status' => 'failed'
        ],422);
    
    }

}
