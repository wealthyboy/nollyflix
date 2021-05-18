<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\PrivateUserResource;
use App\User;
use App\PasswordReset;
use App\Notifications\SendResetPasswordCode;
use Illuminate\Support\Facades\Notification;


use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function forgot(Request $request)
    {   
        //
        $user   = User::where('email',$request->email)->first();

        if (!$user){
            return response()->json([
                'errors' => [
                    'email' => ['Could not find your email.']
                ]
            ], 422);
        }

        $random_number = mt_rand(100000, 999999);
        $password_reset = new PasswordReset;
        $password_reset->email = $user->email;
        $password_reset->user_id  = $user->id;
        $password_reset->token  = $random_number;
        $password_reset->token_expires_at = now()->addMinutes(10);
        $password_reset->save();

        Notification::route('mail', $user->email)
        ->notify(new SendResetPasswordCode($user, $random_number));

        return $password_reset;
    }


    public function reset(Request $request){
        $code = PasswordReset::where("token",$request->code)->first();
        if (!$code){
            return response()->json([
                'errors' => [
                    'code' => ['Code is invalid']
                ]
            ], 422);
        }

        if ($code->token_expires_at->diffInMinutes() > 10){
            return response()->json([
                'errors' => [
                    'code' => ['Code is invalid']
                ]
            ], 422);
        }

        $user = User::find($code->user_id);
        $user->password =  bcrypt($request->password);
        $user->save();
        return response()->json([
            'status' => "Password updated"
        ],200);

    }

}
