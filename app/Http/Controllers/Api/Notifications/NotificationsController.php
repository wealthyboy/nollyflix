<?php

namespace App\Http\Controllers\Api\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PrivateUserResource;

use App\User;

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
        //update or create
        $user =  \Auth::user();
        
        $result = $user->update([
          'allow_notifications' => $request->notificationStatus  == false ?  1 : 0,
          'push_token' => $request->pushToken
        ]);

        if ($result){
            return new PrivateUserResource($request->user());
        }

        return response()->json([
            'status' => 'failed'
        ],422);
    
    }

}
